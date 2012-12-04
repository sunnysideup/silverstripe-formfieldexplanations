<?php

/**
 *@author nicolaas[at]sunnysideup.co.nz
 *@description contains a list of form field and their explantions
 *
 **/


class FormFieldExplanationExtension extends Extension{

	static $allowed_actions = array("addfieldexplanation");

	static function add_explanations($form, $datarecord) {
		$bt = defined('DB::USE_ANSI_SQL') ? "\"" : "`";
		$js = '
			var formFieldExplanationErrorMessage = new Array();';
		$dos = DataObject::get("FormFieldExplanation", "{$bt}ParentID{$bt} = ".$datarecord->ID);
		$explanations = array();
		if($dos) {
			foreach($dos as $do) {
				if($do->Explanation) {$explanations[$do->Name]["Explanation"] = $do->Explanation;}
				if($do->CustomErrorMessage) {$explanations[$do->Name]["CustomErrorMessage"] = $do->CustomErrorMessage;}
				if($do->CustomErrorMessageAdditional) {$explanations[$do->Name]["CustomErrorMessageAdditional"] = $do->CustomErrorMessageAdditional;}
				if($do->AlternativeFieldLabel) {$explanations[$do->Name]["AlternativeFieldLabel"] = $do->AlternativeFieldLabel;}
				if($do->ID) {$explanations[$do->Name]["ID"] = $do->ID;}
			}
		}
		$dos = $do = null;
		$dataFields = $form->fields();
		$extraFields = new FieldSet();
		if($dataFields){
			foreach($dataFields as $field) {
				if($field InstanceOf CompositeField) {
					self::find_composite_fields($field, $extraFields);
				}
			}
		}
		if($dataFields){
			foreach($dataFields as $field) {
				self::process_field($field, $explanations, $datarecord, $js);
			}
		}
		if($extraFields) {
			foreach($extraFields as $field) {
				self::process_field($field, $explanations, $datarecord, $js);
			}
		}
		// block prototype validation
		Requirements::javascript(THIRDPARTY_DIR."/jquery/jquery.js");
		Requirements::block("sapphire/javascript/Validator.js");
		Requirements::javascript("formfieldexplanations/javascript/Silvertripe-2.3-Validator.js");
		Requirements::javascript("formfieldexplanations/javascript/formfieldexplanations.js");
		Requirements::customScript($js, "FormFieldExplanationExtension");
		Requirements::themedCSS("formfieldexplanations");
		return $form;
	}

	protected static function find_composite_fields ($compositeField, &$extraFields) {
		$dataFields = $compositeField->FieldSet();
		if($dataFields){
			foreach($dataFields as $field) {
				if($field InstanceOf CompositeField) {
					self::find_composite_fields($field, $extraFields);
				}
				else {
					$extraFields->push($field);
				}
			}
		}
		return $extraFields;
	}

	protected static function process_field($field, $explanations, $datarecord, &$js) {
		$dos = $do = null;
		if($name = $field->Name()) {
			$message = '';
			if(isset($explanations[$name])) {
				if(isset($explanations[$name]["Explanation"])) {
					$message .= $explanations[$name]["Explanation"];
				}
				if($datarecord->canEdit() && isset($explanations[$name]["ID"])) {
					$message .= ' | '.self::CMSLink($datarecord->ID, $explanations[$name]["ID"]);
				}
			}
			elseif($datarecord->canEdit() && $name) {
				$title = $field->Title();
				if(!$title) {
					$title = $name;
				}
				$cleanTitle = strip_tags($title);
				if(class_exists("UserDefinedForm")) {
					$cleanTitle = str_replace(UserDefinedForm::$required_identifier, "", $cleanTitle);
					$cleanTitle = str_replace("+", "", $cleanTitle);
				}
				$message .= ' | <a href="'.$datarecord->Link().'addfieldexplanation/'.urlencode($name).'/'.urlencode($cleanTitle).'/" class="addFieldExplanation">customise field</a>';
			}
			$do = true;
			switch($field->class) {
				case "HeaderField":
					$do = false;
					break;
				default:
					break;
			}
			$id = $field->id();
			$message = str_replace("/", "\/", Convert::raw2js($message));
			if($do && $message && $name && $id) {
				$js .= "
				formfieldexplanations.add_info('".$name."', '".$message."', '".$id."');";
			}
		}
		$errorMessage = '';
		if(isset($explanations[$name]["CustomErrorMessage"])) {
			$errorMessage = $explanations[$name]["CustomErrorMessage"];
			if(isset($explanations[$name]["CustomErrorMessageAdditional"])) {
				$errorMessage .= '<span class="additionalValidationErrorMessage">'.$explanations[$name]["CustomErrorMessageAdditional"].'</span>';
			}
			$field->addExtraClass("customErrorMessage");
			$field->setCustomValidationMessage($errorMessage);
		}
		if($field->Required() && $errorMessage) {
			$js .= "
				formFieldExplanationErrorMessage['$name'] = '".str_replace("/", "\/", Convert::raw2js($errorMessage))."';";
		}
		if(isset($explanations[$name]["AlternativeFieldLabel"])) {
			$js .= "
				formfieldexplanations.replace_title('".$name."', '".str_replace("/", "\/", Convert::raw2js($explanations[$name]["AlternativeFieldLabel"]))."', '".$id."');";
		}
	}

	public function addfieldexplanation(HTTPRequest $HTTPRequest) {
		$bt = defined('DB::USE_ANSI_SQL') ? "\"" : "`";
		$fieldName = $HTTPRequest->param("ID");
		$fieldTitle = $HTTPRequest->param("OtherID");
		$obj = DataObject::get_one("FormFieldExplanation", "{$bt}Name{$bt} = '".$fieldName."' AND ParentID = ".$this->owner->ID);
		if(!$obj) {
			$obj = new FormFieldExplanation();
		}
		$obj->Name = $fieldName;
		$obj->Title = $fieldTitle;
		$obj->Explanation = "explanation to be added";
		$obj->ParentID = $this->owner->ID;
		$obj->write();
		if(Director::is_ajax()) {
			return self::CMSLink($this->owner->ID, $obj->ID);
		}
		else {
			Director::redirectBack();
		}
	}

	protected function editfieldexplanation(HTTPRequest $HTTPRequest) {
		$customiseArray = array(
			"Title" => "Test",
			"Form" => "FormTest"
		);
		//TO DO!!!!! link with DataObjectsorter
		return Array();
	}

	protected static function CMSLink ($pageID, $itemID) {
		if(class_exists("DataObjectOneRecordUpdateController")) {
			$link = DataObjectOneRecordUpdateController::popup_link(
				$className = "FormFieldExplanation",
				$recordID = $itemID,
				$linkText = "edit customisation"
			);
			return $link;
		}
		else {
			return '<a href="admin/show/'.$pageID.'" class="editFieldExplanation">edit description in CMS</a>';
		}
	}

	protected function array2json($array) {
		foreach($array as $key => $value)
			if(is_array( $value )) {
				$result[] = "$key:" . $this->array2json($value);
			} else {
				$value = (is_bool($value)) ? $value : "\"$value\"";
				$result[] = "$key:$value \n";
			}
		return (isset($result)) ? "{\n".implode( ', ', $result ) ."} \n": '{}';
	}

}
