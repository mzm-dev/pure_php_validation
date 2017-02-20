# Pure PHP Class Validation
This class library useful for form or data submit form validation to PHP function. This library only for study and it can make extended for other library validation. This validation concept inspire from [CodeIgnater Validation Library](https://www.codeigniter.com/userguide3/libraries/form_validation.html#callbacks-your-own-validation-methods)

# Setup
  - [Download](https://github.com/mzm-dev/pure_php_validation/archive/master.zip)
  - Create folder 'library' in project folder or put in validation.php in your own library folder

# How to use
Create simple HTML Form
```html
<form method='post'>
    Name : <input type="text" name="name">
    E-mail : <input type="email" name="email">
    <input type="submit">
</form>
```

Include validation library in your php file (same file with html form)
```php
<?php
include_once 'lib/validation.php';
?>
```
Post form request conditions
```php
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

}
?>
```

Run Execute validation function, set rule for your input form
```php
<?php
$validator->execute($_POST);
$validator->set_rules('name_of_input', 'label_of_input', 'rule1|rule2|rule3');
if (!$validator->run()) {//validation run rule and error is not empty
    echo $validator->validation_errors();
} else { //error empty
    echo "Validated Pass";
}
?>
```

Full Code Example
```php
<?php
include_once 'lib/validation.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validator->execute($_POST);
    $validator->set_rules('name', 'Name', 'required|alpha_spaces|max_length[80]');
    $validator->set_rules('email', 'Email', 'required|valid_email', array(
        'required' => 'You have not provided {field}.',
        'valid_email' => '{field} is invalid. Please provide the real email address.'
    ));
    if (!$validator->run()) {//validation run rule and error is not empty
        echo $validator->validation_errors();
    } else { //error empty
        echo "Validated Pass";
    }
}
?>
<form method='post'>
    Name : <input type="text" name="name">
    E-mail : <input type="email" name="email">
    <input type="submit">
</form>
```

# Rule Reference
The following is a list of all the native rules that are available to use:

| Rule | Parameter | Example | Description |
| ------ | ------ | ------ | ------ |
| required | No | required | Returns FALSE if the request data is empty. |
| regex_match | Yes | regex_match[/regex/] | Returns FALSE if the request data does not match the regular expression. |
| min_length | Yes | min_length[3] | Returns FALSE if the request data is shorter than the parameter value. |
| max_length | Yes | max_length[12] | Returns FALSE if the request data is longer than the parameter value. |
| exact_length | Yes | exact_length[9] | Returns FALSE if the request data is not exactly the parameter value. |
| greater_than | Yes | greater_than[8] | Returns FALSE if the request data is less than or equal to the parameter value or not numeric. |
| greater_than_equal_to | Yes | greater_than_equal_to[8] | Returns FALSE if the request data is less than the parameter value, or not numeric. |
| less_than | Yes | less_than[8] | Returns FALSE if the request data is greater than or equal to the parameter value or not numeric. |
| less_than_equal_to | Yes | less_than_equal_to[4] | Returns FALSE if the request data is greater than the parameter value, or not numeric. |
| in_list | Yes | in_list[red,blue,green]  | Returns FALSE if the request data is not within a predetermined list. |
| alpha | No | alpha | Returns FALSE if the request data contains anything other than alphabetical characters. |
| alpha_spaces | No | alpha_spaces | Returns FALSE if the request data contains anything other than alphabetical characters with spaces. |
| alpha_numeric | No | alpha_numeric | Returns FALSE if the request data contains anything other than alpha-numeric characters. |
| alpha_numeric_spaces | No | alpha_numeric_spaces | Returns FALSE if the request data contains anything other than alpha-numeric characters or spaces. Should be used after trim to avoid spaces at the beginning or end. |
| alpha_dash |  No | alpha_dash |   Returns FALSE if the request data contains anything other than alpha-numeric characters, underscores or dashes. |
| numeric | No | numeric | Returns FALSE if the request data contains anything other than numeric characters. |
| integer | No | integer | Returns FALSE if the request data contains anything other than an integer. |
| decimal | No | decimal | Returns FALSE if the request data contains anything other than a decimal number. |
| is_natural | No | is_natural | Returns FALSE if the request data contains anything other than a natural number: 0, 1, 2, 3, etc. |
| is_natural_no_zero | No | is_natural_no_zero | Returns FALSE if the request data contains anything other than a natural number, but not zero: 1, 2, 3, etc. |
| valid_email | No | valid_email |  Returns FALSE if the request data does not contain a valid email address. |
| valid_emails | No | valid_emails | Returns FALSE if any value provided in a comma separated list is not a valid email. exp : amiruddin@domain.my,burhanuddin@domain.my,cinta@domain.my |