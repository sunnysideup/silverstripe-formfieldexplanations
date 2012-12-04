<?php

/**
 *@author nicolaas[at]sunnysideup.co.nz
 *@description contains a list of form fields and their explantions, is added to SiteTree
 *
 **/


class FormFieldExplanationDecorator extends DataObjectDecorator{

	protected static $show_fields_in_cms = false;
		static function set_show_fields_in_cms($v) {self::$show_fields_in_cms = $v;}
		static function get_show_fields_in_cms() {return self::$show_fields_in_cms;}

	public function extraStatics() {
		return array (
			'has_many' => array(
				'FormFieldExplanation' => 'FormFieldExplanation'
			)
		);
	}

	function updateCMSFields(FieldSet &$fields) {
		$bt = defined('DB::USE_ANSI_SQL') ? "\"" : "`";
		if(DataObject::get_one("FormFieldExplanation", "{$bt}ParentID{$bt} = ".$this->owner->ID)) {
			$fields->addFieldToTab("Root.Content.FormExplanations", $this->getFormFieldExplanationHasManyTable());
		}
		return $fields;
	}


	function getFormFieldExplanationHasManyTable() {
		$bt = defined('DB::USE_ANSI_SQL') ? "\"" : "`";
		$field = new HasManyComplexTableField(
			$controller = $this->owner,
			$name = "FormFieldExplanation",
			$sourceClass = "FormFieldExplanation",
			$fieldList = array("Title" => "Title"),
			$detailFormFields = null,
			$sourceFilter = "{$bt}ParentID{$bt} =".$this->owner->ID,
			$sourceSort = "",
			$sourceJoin = ""
		);
		$field->setPermissions(array("edit", "delete"));
		$field->setParentClass($this->owner->class);
		$field->relationAutoSetting = true;
		return $field;
	}
}
