formBuilder
===========

A library for CodeIgniter that assists in building forms from a database table

###Setup

place formBuilder.php into your library folder.


```php
//new pd form
	$this->formbuilder->assign_vars(
		'policedepartments',
		array(
			'State' => $data["file_info"]["cubsData"]["Other"]["Loss Location State"],
			'City' => $data["file_info"]["cubsData"]["Other"]["Loss Location City"]
			)
		);
		
		
//exclude these values
	$this->formbuilder->exclude_form_values(array('id', 'Last_Updated'));
		
		
//build the table 
	$data['pd_new_form'] = $this->formbuilder->build_form();
``` 
