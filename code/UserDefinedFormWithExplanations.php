<?php
/**
 *
 *
 * @author nicolaas [at] sunny side up . co . nz
 */

class UserDefinedFormWithExplanations extends UserDefinedForm {

	static $hide_ancestor = "UserDefinedForm";

	static $db = array();

}


class UserDefinedFormWithExplanations_Controller extends UserDefinedForm_Controller {

	public function init() {
		parent::init();
	}

	public function Form() {
		$form = parent::Form();
		$form = FormFieldExplanationExtension::add_explanations($form, $this->dataRecord);
		return $form;
	}

}
