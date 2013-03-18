formBuilder
===========

formBuilder is a libarary for CodeIgniter that will allow you to easily build out forms from a database table, populate that form using a record from the table, and then insert or update records in your table.

###Getting Started

* Place formBuilder.php into your library folder
* Load the library in your controller `$this->load->library('formBuilder');`

##Building a Form

###assign_vars(form_structure, form_values)

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

__Note:__ If you do not want to prefill the form with form_values, simply pass in a `null` reference.

Here are a few examples

```php
//passing in a tablename and id 
$this->formBuilder->assign_vars('myTableName', '1');

//passing in your own structure and pre-fill values
$this->formBuilder->assign_vars(
	//form_structure
	array(
		'FirstName' => 'text',
		'EmailAddress' => 'email'
		),

	//form_values
	array(
		'FirstName' => 'Jake',
		'EmailAddress' => 'dont@emailme.com',
		)
	);	


//passing in a tablename with no prefiled values
$this->formBuilder->assign_vars('myTableName', null);

```

###exclude_form_values(array $excluded_values)

This function accepts an array of values you wish to exclude from your form. Each array value must correspond to the name of a column in your table or in your form_structure array.


###include_form_values(array $included_values)

This function is the inverse of __exclude_form_values__. If this function is used, only values listed in the array will be included in the form. Each array value must correspond to the name of a column in your table or in your form_structure array.

###hide_form_values(array $hidden_values)

As you have probably guessed by now, this function accepts an array of values that will become `type=hidden' inputs.  Each array value must correspond to the name of a column in your table or in your form_structure array.

###build_form()

This function will return the html output of your form inputs. __It will not output a `<form>` element or a `<input type=submit>` button.__

Usually we will run this function within the controller and assign the html output to a variable that will be passed to the desired view through the `$data[]` array.

For Example:
```php

$data['my_form_html'] = $this->formbuilder->build_form();

```

