<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class formBuilder {

	protected $form_structure = null;
	protected $form_values = null;

	public function assign_vars($form_structure, $form_values){
		//pass vars by reference
		$tablename =& $form_structure;
		$id =& $form_values;
		
		//set all properties
		$this->form_structure = $form_structure;
		if(gettype($form_structure) == "string") {$this->form_structure = $this->get_form_structure($tablename);}

		$this->form_values = $form_values;
		if(gettype($form_values) == "string") {$this->form_values = $this->get_form_values($tablename, $id);}

	}

	public function build_form() {

		//instantiate return values
		$return_values = array();

		//loop through form structure and output values
		foreach ($this->form_structure as $key => $value) {

			//Set build function to call
			$build_type = $this->determine_build($value['type']);
			
			if ($value['type'] == 'hidden') {
				$return_values["hidden_values"][] = $this->$build_type($key, $value);
			}else{
				$return_values["display_values"]["<label for='{$key}'>". $this->pretty_label($key) ."</label>"] = $this->$build_type($key, $value);
			}

		}

		return $return_values;

	}

	private function build_input($input_name, array $input_data) {

		extract($input_data);

		return 	"<input ".
				"id='{$id}' ".
				"name='{$input_name}' ".

				// if no type is set default to text
				"type='" . (isset($type)?$type:'text') . "' ".

				"value='" . (isset($this->form_values[$input_name])?$this->form_values[$input_name]:'') . "' ".
				// "placeholder='{$placeholder}' ".
				"maxlength='{$size}' />";
	}

	private function build_input_textarea($input_name, array $input_data) {

		extract($input_data);

		return 	"<textarea ".
				"id='{$id}' ".
				"name='{$input_name}' >".
				(isset($this->form_values[$input_name])?$this->form_values[$input_name]:'') .
				// "placeholder='{$placeholder}' ".
				"</textarea>";
	}


	/*
		Unset all fields from form_structure listed in the excluded_values array
	*/
	public function exclude_form_values(array $excluded_values) {
		foreach ($excluded_values as $value) {

			if (isset($this->form_structure[$value])) {
				unset($this->form_structure[$value]);
			} else {
				//show error
				echo 'exclude_form_values array item not found in form_structure';
			}
		}
	}

	/*
		Remove all values not in included_values from form_structure
	*/
	public function include_form_values(array $included_values) {
		//instantiate temporary holding array
		$temp_array = array();

		foreach ($included_values as $value) {
			if (isset($this->form_structure[$value])) {
				$temp_array[$value]=$this->form_structure[$value];
			} else {
				//show error
				echo 'include_form_values array item not found in form_structure';
			}
		}

		$this->form_structure = $temp_array;

	}

	/*
		Set type eq to hidden for values listed in hidden_values
	*/
	public function hide_form_values(array $hidden_values) {
		foreach ($hidden_values as $value) {
			if (isset($this->form_structure[$value])) {
				$this->form_structure[$value]['type'] = "hidden";
			} else {
				//show error
				echo 'hide_form_values array item not found in form_structure';
			}
		}
	}

	//Determine what build function to use based on the type of field
	private function determine_build($type) {

		switch ($type) {
			case 'text':
			case 'number':
			case 'hidden':

				return "build_input";

				break;
			
			default:
				return "build_input_" . $type;
				break;
		}

	}


/*/////////////////////
DATABASE METHODS
////////////////////*/

	public function get_form_structure($tablename) {
		
		$CI =& get_instance();

		$fields = $CI->db->field_data($tablename);

		// Setup the data in the format our form_builder needs it

		$returnArray = array();
		foreach($fields as $ky => $val) {
			// print_r($ky);
			// print_r($val);


			// First, handle the field type
			switch($val->type) {
				case "varchar":
					$returnArray[$val->name]["type"] = "text";
				break;
				case "int":
					$returnArray[$val->name]["type"] = "number";
				break;
				case "text":
					$returnArray[$val->name]["type"] = "textarea";
				break;
				default:
					$returnArray[$val->name]["type"] = "text";
			}

			// Handle the id
			$returnArray[$val->name]["id"] = $val->name;

			// Handle the length
			$returnArray[$val->name]["size"] = $val->max_length;

			// Handle the placeholder
			$returnArray[$val->name]["placeholder"] = str_replace("_", " ", $val->name);

			// Set whether its required
			$returnArray[$val->name]["required"] = null;


		}
		return $returnArray;
	}

	public function get_form_values($tablename = '', $id = '') {
		
		$CI =& get_instance();

		$CI->db->escape_str($tablename);
		$CI->db->escape_str($id);

		$CI->db->from($tablename);
		$CI->db->where('id', $id);
		$query = $CI->db->get();

		$outputArray = array();
		if ($query->num_rows() == "1") {
			
			foreach($query->row_array() as $ky => $val) {
				$outputArray[$ky] = $val;
			}			
		}
		if(!empty($outputArray)) {
			return $outputArray;
		} else {
			return false;
		}

	}

	public function save_form_values($tablename = '', $postvars = array(), $id = 'false') {
		
		$CI =& get_instance();

		if($tablename != '' && !empty($postvars)) {
			// We have something here, go ahead and continue on

			// Escape the values passed, just to be safe
			$CI->db->escape_str($tablename);
			$CI->db->escape_str($id);


			foreach($postvars as $tmpKY => $tmpVAL) {
				// Escape each field just to be sure everything is safe for the database
				$CI->db->escape_str($tmpVAL);
				
				// Set each of the values we're going to push into the database
				$sql[$tmpKY] = $tmpVAL;

				// Send the fields that we want to "clean" to clean_database_values() before sending them to the database
				$sql[$tmpKY] = $this->clean_database_values($sql[$tmpKY], $tmpKY);
				
			}

			if($id != 'false') {
				// We have an ID, so go ahead and process and update
				$CI->db->where('id', $id);

				if($CI->db->update($tablename, $sql)) {
					// print_r($CI->db->last_query());
					// exit;
					$results = true;
				} else {
					$results = false;
				}

			} else {
				// We do not have an ID, so just run the insert

				// We are going to do an insert, so even if it is in the POST vars, just exclude it
				// unset($sql['id']);
				if($CI->db->insert($tablename, $sql)) {
					// print_r($CI->db->last_query());
					// exit;
					$results = true;
				} else {
					$results = false;
				}
			}

			// Return
			return $results;
		} else {
			return false;
		}

	}


/*/////////////////////
ANCILARRY METHODS
	- Providing necessary support to the primary activities or operation of an organization, institution, industry, or system.
////////////////////*/

	// Clean the database value before insert/update
	private function clean_database_values($value, $fieldname) {
		// These are the items we wish to remove from certain fields before we save them
		$cleanArray = array("(",")"," ","-",".",",");

		// Only do this if the fields we are working with contain the following words
		if(strstr(strtoupper($fieldname), "PHONE") || strstr(strtoupper($fieldname), "FAX")) {
			return str_replace($cleanArray, "", $value);
		} else {
			return $value;
		}
	}

	private function pretty_label($value) {
		return str_replace("_", " ", $value);
	}

}
