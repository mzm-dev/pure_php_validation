<?php
/*
 * the database connection
 */
include_once '../lib/validation.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validator->execute($_POST);//execute form request data
    $validator->set_rules('name', 'Name', 'required|alpha_spaces|exact_length[2]');
    $validator->set_rules('email', 'Email', 'required|valid_email', array(
        'required' => 'You have not provided {field}.',
        'valid_email' => 'Alamat {field} tidak sah.'
    ));
    $validator->set_rules('nric', 'No K/P', 'required|regex_match', array(
        'regex_match' => '{field} tidak sah.'
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
    Name : <input type="text" name="name">
    <table>
        <tr>
            <td>Name</td>
            <td><input type="text" name="name"></td>
        </tr>
        <tr>
            <td>E-mail</td>
            <td><input type="email" name="email"></td>
        </tr>
        <tr>
            <td>No Kad Pengenalan</td>
            <td><input type="text" name="nric" placeholder="cth : xxxxxx-xx-xxxx"></td>
        </tr>
    </table>
    <input type="submit">
</form>