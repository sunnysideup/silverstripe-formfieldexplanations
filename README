###############################################
Form Field Explanations
Pre 0.1 proof of concept
###############################################

Credits
-----------------------------------------------

Developer
-----------------------------------------------
Nicolaas [at] sunnysideup.co.nz

Requirements
-----------------------------------------------
SilverStripe 2.3.0 or greater.

Documentation
-----------------------------------------------




TO DO
-----------------------------------------------



Installation Instructions
-----------------------------------------------
1. Find out how to add modules to SS and add module as per usual.

2. copy configurations from this module's _config.php file
into mysite/_config.php file and edit settings as required.
NB. the idea is not to edit the module at all, but instead customise
it from your mysite folder, so that you can upgrade the module without redoing the settings.

3. whenever you build a form in PHP, as per usual, add this stuff at the end:
	function MyForm() {
		//building form goes here
		$form = FormFieldExplanationExtension::add_explanations($form, $this->dataRecord);
		return $form;
	}

