Form Field Explanations
================================================================================

Credits
-----------------------------------------------

Developer
-----------------------------------------------
Nicolaas [at] sunnysideup.co.nz

Requirements
-----------------------------------------------
see composer.json

Documentation
-----------------------------------------------




TO DO
-----------------------------------------------



Installation Instructions
-----------------------------------------------
1. Find out how to add modules to SS and add module as per usual.

2. Review configs and add entries to mysite/_config/config.yml
(or similar) as necessary.
In the _config/ folder of this module
you should to find some examples of config options (if any).

3. whenever you build a form in PHP, as per usual, add this stuff at the end:
	function MyForm() {
		//building form goes here
		$form = FormFieldExplanationExtension::add_explanations($form, $this->dataRecord);
		return $form;
	}

