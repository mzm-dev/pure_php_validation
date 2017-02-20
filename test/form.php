<?php
/*
 * the database connection
 */
include_once '../lib/validation.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validator->execute($_POST);//execute form request data

    //Set Rule for input Name
    $validator->set_rules('name', 'Name', 'required|alpha_spaces|max_length[80]');

    //Set Rule for input Email
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
    Name : <input type="text" name="name"><br/>
    E-mail : <input type="email" name="email"><br/>
    <input type="submit">
</form>