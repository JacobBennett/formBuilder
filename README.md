formBuilder
===========

formBuilder is a libarary for CodeIgniter that will allow you to easily build out forms from a database table, populate that form using a record from the table, and then insert or update records in your table.

###Getting Started

* Place formBuilder.php into your library folder
* Load the library in your controller ```$this->load->library('formBuilder');```


##Usage

formBuilder has the ability to create, populate, update, and insert but each function can also be used independently. 

###Building a Form

######assign_vars()
The first step in building a form is calling the 'assign_vars' function. This function allows us to set the form structure and the optional values that will pre-fill that form.


* Pass in the name of the table you wish to reference (string)
* Pass in an array of key value pairs where the key is the input name and the value is the type of field.

```php
<<<<<<< HEAD

$this->formbuilder->assign_vars('policedepartments',array('State' => $data["file_info"]["cubsData"]["Other"]["Loss Location State"],'City' => $data["file_info"]["cubsData"]["Other"]["Loss Location City"]));


``` 
