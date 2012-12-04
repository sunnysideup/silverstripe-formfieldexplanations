<?php

/**
 *@author nicolaas[at]sunnysideup.co.nz
 *@description contains a list of form field and their explantions
 *
 **/


class FormFieldExplanation extends DataObject {

	public static $db = array(
		"Name" => "Varchar(255)",
		"Title" => "Varchar(255)",
		"Explanation" => "Varchar(255)",
		"AlternativeFieldLabel" => "Varchar(100)",
		"CustomErrorMessage" => "Varchar(100)",
		"CustomErrorMessageAdditional" => "Varchar(200)"
	);

	public static $has_one = array(
		"Parent" => "SiteTree"
	);

	public static $indexes = array(
		"Name" => true
	);

	public static $searchable_fields = array(
		"Title" => "PartialMatch"
	);

	public static $field_labels = array(
		"Name" => "Field Name",
		"Title" => "Label",
		"Explanation" => "Explanation",
		"AlternativeFieldLabel" => "Alternative Field Label (if any - replaces standard field label)",
		"CustomErrorMessage" => "Custom Error Message, shown when field is not entered",
		"CustomErrorMessageAdditional" => "Additional (sub-heading) Custom Error Message, shown when field is not entered"

	);
	public static $summary_fields = array(
		"Title" => "Title"
	);

	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName("ParentID");
		$fields->removeByName("Name");
		$fields->replaceField("Title", new LiteralField("Title", "<h3>Edit the details for ".$this->Title."</h3>"));
		return $fields;
	}

	function getFrontEndFields() {
		$fields = parent::getFrontEndFields();
		$fields->removeByName("ParentID");
		$fields->removeByName("Name");
		$fields->replaceField("Title", new LiteralField("Title", "<h3>Edit the details for ".$this->Title."</h3>"));
		return $fields;
	}

	public static $singular_name = "Form Field Explanation";

	public static $plural_name = "Form Field Explanations";


}