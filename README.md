formBuilder
===========

formBuilder is a libarary for CodeIgniter that will allow you to easily build out forms from a database table, populate that form using a record from the table, and then insert or update records in your table.

###Getting Started

* Place formBuilder.php into your library folder
* Load the library in your controller `$this->load->library('formBuilder');`

##Building a Form

###assign_vars(form_structure, form_values)

The first step in building a form is calling the __assign_vars__ function. This function allows us to set the form structure and the optional values that will pre-fill that form.

Both __form_structure__ and __form_values__ will accept a `STRING` value or an `ARRAY`. 

__If passing a `STRING` value__

* form_structure will assume your `STRING` is the name of the table you want to reference
* form_values will assume your `STRING` is the ID of the row you wish to use to populate the form

__If passing an `ARRAY`__

* form_structure will assume your `ARRAY` adheres to the following pattern
```php
	array(

		'inputName' => Array
			(
				'type' => 'text',
				'id' => 'inputName',
				'size' => '10',
				'placeholder' => 'some text here',
				'required' => true
			),

		'inputName2' => Array
			(
				'type' => 'number',
				'id' => 'inputName2',
				'size' => '10',
				'placeholder' => 'some text here',
				'required' => true
			)
	)
```
* form_values will assume your `ARRAY` adheres to the following pattern
```php
	array(
		'inputName' => 'inputValue'
	)
```

* __Note:__ If you do not want to prefill the form with form_values, simply pass in a `NULL` reference.

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

###Putting It Together

__Controller__

Here is a quick example of what you might place in your controller. This would generate a form, fill it with the values from the record in your database with `id=23`, exclude system generated inputs, and hide the `id` input

```php

public function viewMyForm() {
		//new form
		$this->formbuilder->assign_vars(

			//table name
			'certrequirements',

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

```

__View__

As stated in the `build_form()` description, the `<form>` and `<input type=submit>` markup are left to you. In your CodeIgniter view you might put the following.

__Note:__ the `data-table` property will be used during the save of the form. Further explanation can be found in the __Saving a Form__ secion.

```php
	<form id='my_form' data-table='myDatabaseTablename'>

		//echo out the html produced by build_form()
		//that was passed in through the controller

		<?= $my_form ?>

		<input type='submit' id='my_submit_button' />
	</form>

```

##Saving a Form

__Note:__ We will most often use AJAX to handle the saving of our forms. We find it to be a more native experience for users. The following implementation uses Jquery and AJAX to send our call from our page to our controller.

###save_form_values(string $tablename, array $post_vars, string $id)

Place this function within a new controller method.

```php
public function save_table($tablename = '', $id = 'false') {
	if ($post_vars = $this->input->post(NULL, TRUE)) {

		//will print true or false
		print_r(json_encode($this->formbuilder->save_form_values($tablename, $post_vars, $id)));
	}
}
```








When saving a form it is important to note that the keys of your `$_POST` vars must be names of columns in your table. This will only be a concern if you set the `$form_structure` value of `build_form()` using an `ARRAY` as oppossed to passing in the `STRING` value of the table you wish to target.