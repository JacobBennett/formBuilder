formBuilder
===========

formBuilder is a libarary for CodeIgniter that will allow you to easily build out forms from a database table, populate that form using a record from the table, and then insert or update records in your table.

###Getting Started

* Place formBuilder.php into your library folder
* Load the library in your controller ```$this->load->library('formBuilder');```


##Usage

formBuilder has the ability to create, populate, update, and insert but each function can also be used independently. 

###Building a Form

######assign_vars(form_structure, form_values)

The first step in building a form is calling the __assign_vars__ function. This function allows us to set the form structure and the optional values that will pre-fill that form.

Both __form_structure__ and __form_values__ will accept a *string* values or an *associative array*. 

If passing a *string* value

* form_structure will assume your *string* is the name of the table you want to reference
* form_values will assume your *string* is the ID of the row you wish to use to populate the form

If passing an associative array

* form_structure will assume your *associative array* adheres to the following pattern
		```php
			array(
				'inputName' => 'inputType',
				'FirstName' => 'text',
				'PhoneNumber' => 'text',
				'EmailAddress' => 'email'
			)
		```
* form_values will assume your *associative array* adhees to the following pattern
		```php
			array(
				'inputName' => 'inputValue',
				'FirstName' => 'Jake',
				'PhoneNumber' => '555-786-4456',
				'EmailAddress' => 'dont@emailme.com'
			)
		```

```php
$this->formbuilder->assign_vars('policedepartments',array('State' => $data["file_info"]["cubsData"]["Other"]["Loss Location State"],'City' => $data["file_info"]["cubsData"]["Other"]["Loss Location City"]));
``` 
