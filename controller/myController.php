<?php

class formBuilderController extends Controller {

		public function __construct()
	{
		parent::__construct();
		

		//LOAD REQUIRED MODELS AND HELPERS
		$this->load->database();
		$this->load->library('formBuilder');

	}

	public function index()
	{

		//new form
		$this->formbuilder->assign_vars(

			//table name
			'myTableName',

			//row id to populate form values
			'23'
		);

		//exclude these values
			$this->formbuilder->exclude_form_values(
					Array('timestamp', 'lastModifiedBy')
				);

		//hide these values
			$this->formbuilder->hide_form_values(
					Array('id')
				);

		//build the table 
			$data['my_form'] = $this->formbuilder->build_form();

		$this->load->view('myView', $data); 

	}

}