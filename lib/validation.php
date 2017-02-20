<?php

/**
 * Form Validation Class
 *
 * @package    Validation
 * @author     Mohamad Zaki Mustafa
 * @link       https://github.com/mzm-dev/pure_php_validation/
 * @contact    mohdzaki04@gmail.com
 * @fb         https://www.facebook.com/akifiz
 * @tw         https://twitter.com/mzmfizaki
 * @version       1.0.0
 *
 *
 *
 * Rule Reference
 *
 * Rule                     Parameter   Example                     Description
 *
 *
 * required                 No          required                    Returns FALSE if the request data is empty.
 *
 * regex_match              Yes         regex_match[/regex/]        Returns FALSE if the request data does not match the regular expression.
 *
 * min_length               Yes         min_length[3]               Returns FALSE if the request data is shorter than the parameter value.
 *
 * max_length               Yes         max_length[12]              Returns FALSE if the request data is longer than the parameter value.
 *
 * exact_length             Yes         exact_length[8]             Returns FALSE if the request data is not exactly the parameter value.
 *
 * greater_than             Yes         greater_than[8]             Returns FALSE if the request data is less than or equal to the parameter value or not numeric.
 *
 * greater_than_equal_to    Yes         greater_than_equal_to[8]    Returns FALSE if the request data is less than the parameter value, or not numeric.
 *
 * less_than                Yes         less_than[8]                Returns FALSE if the request data is greater than or equal to the parameter value or not numeric.
 *
 * less_than_equal_to       Yes         less_than_equal_to[8]       Returns FALSE if the request data is greater than the parameter value, or not numeric.
 *
 * in_list                  Yes         in_list[red,blue,green]     Returns FALSE if the request data is not within a predetermined list.
 *                                                                  Default in list is characters if number should be used is_natural or is_natural_no_zero
 *
 * alpha                    No          alpha                       Returns FALSE if the request data contains anything other than alphabetical characters.
 *
 * alpha_spaces             No          alpha_spaces                Returns FALSE if the request data contains anything other than alphabetical characters with spaces.
 *
 * alpha_numeric            No          alpha_numeric               Returns FALSE if the request data contains anything other than alpha-numeric characters.
 *
 * alpha_numeric_spaces     No          alpha_numeric_spaces        Returns FALSE if the request data contains anything other than alpha-numeric characters or spaces.
 *                                                                  Should be used after trim to avoid spaces at the beginning or end.
 *
 * alpha_dash               No          alpha_dash                  Returns FALSE if the request data contains anything other than alpha-numeric characters, underscores or dashes.
 *
 * numeric                  No          numeric                     Returns FALSE if the request data contains anything other than numeric characters.
 *
 * integer                  No          integer                     Returns FALSE if the request data contains anything other than an integer.
 *
 * decimal                  No          decimal                     Returns FALSE if the request data contains anything other than a decimal number.
 *
 * is_natural               No          is_natural                  Returns FALSE if the request data contains anything other than a natural number: 0, 1, 2, 3, etc.
 *
 * is_natural_no_zero       No          is_natural_no_zero          Returns FALSE if the request data contains anything other than a natural number, but not zero: 1, 2, 3, etc.
 *
 * valid_email              No          valid_email                 Returns FALSE if the request data does not contain a valid email address.
 *
 * valid_emails             No          valid_emails                Returns FALSE if any value provided in a comma separated list is not a valid email.
 *                                                                  exp : amiruddin@domain.my,burhanuddin@domain.my,cinta@domain.my
 */
class Validator
{

    protected $success = true;
    protected $error = false;
    /**
     * Validation data for the current form submission
     *
     * @var array
     */
    protected $_field_data = array();
    /**
     * Array of validation errors
     *
     * @var array
     */
    protected $_error_array = array();
    /**
     * Array of validation errors
     *
     * @var array
     */
    protected $_error_list = array();

    /**
     * Execute the Validator
     *
     * @param $request
     */
    public function execute($request)
    {
        $this->_field_data = $request;
    }

    /**
     * @param $field
     * @param string $label
     * @param array $rules
     * @param array $error
     */
    public function set_rules($field, $label = '', $rules = array(), $error = array())
    {
        $param = null;
        $rules = preg_split('/\|(?![^\[]*\])/', $rules);
        foreach ($rules as $ruleKey => $ruleFunction) {//panggil nama function
            $fieldValue = $this->_field_data[$field]; //Field Value
            if (preg_match('/(.*?)\[(.*)\]/', $ruleFunction, $rules_matches)) {//find if rule with parameter
                $ruleFunction = $rules_matches[1];
                $param = $rules_matches[2];
            }
            if (!$this->{$ruleFunction}($fieldValue, $param)) { //call function rule $this->required();
                if (is_array($error) && !empty($error) && isset($error[$ruleFunction])) {//Error is array and not empty and also error key exists
                    $error_msg = $error[$ruleFunction];
                } else {//used as defined error message
                    $error_msg = $this->error_message("error_message_$ruleFunction");
                }
                $label = ($label === '') ? $field : $label;// If the field label wasn't passed we use the field name
                $this->build_error_list($field, str_replace(['{field}', '{param}'], [$label, $param], $error_msg));
                break;
            }
        }
    }

    /**
     * @return bool
     */
    public function run()
    {
        $total_errors = count($this->_error_array);
        return $total_errors == 0 ? true : false;
    }

    /**
     * @param $field
     * @param $error_msg
     */
    public function build_error_list($field, $error_msg)
    {
        $this->_error_array[] = array('field' => $field, 'error' => $error_msg);
    }

    /**
     * @return string
     */
    public function validation_errors()
    {
        $error_list = array();
        foreach ($this->_error_array as $list) {
            $error_list[] = $list['error'];
        }
        return "<ul><li>" . implode("</li><li>", array_values($error_list)) . "</li></ul>";
    }

    /**
     * Required
     *
     * @param string
     * @return bool
     */
    private function required($str = null)
    {
        return is_array($str)
            ? (empty($str) === FALSE)
            : (trim($str) !== '');
    }

    /**
     * Performs a Regular Expression match test.
     *
     * @param    string
     * @param    string  regex
     * @return    bool
     */
    private function regex_match($str = null, $regex = null)
    {
        if ($str && $regex) {
            return (bool)preg_match($regex, $str);
        }
        return false;
    }

    /**
     * Minimum Length
     *
     * @param    string
     * @param    string
     * @return    bool
     */
    private function min_length($str, $val)
    {

        if (!is_numeric($val)) {
            return FALSE;
        }
        return ($val <= mb_strlen($str));

    }

    /**
     * Max Length
     *
     * @param    string
     * @param    string
     * @return    bool
     */
    private function max_length($str, $val)
    {
        if (!is_numeric($val)) {
            return FALSE;
        }
        return ($val >= mb_strlen($str));
    }

    /**
     * Exact Length
     *
     * @param    string
     * @param    string
     * @return    bool
     */
    private function exact_length($str, $val)
    {
        if (!is_numeric($val)) {
            return FALSE;
        }
        return (mb_strlen($str) === (int)$val);
    }

    /**
     * Greater than
     *
     * @param    string
     * @param    int
     * @return    bool
     */
    private function greater_than($str, $min)
    {
        return is_numeric($str) ? ($str > $min) : FALSE;
    }

    /**
     * Equal to or Greater than
     *
     * @param    string
     * @param    int
     * @return    bool
     */
    private function greater_than_equal_to($str, $min)
    {
        return is_numeric($str) ? ($str >= $min) : FALSE;
    }

    /**
     * Less than
     *
     * @param    string
     * @param    int
     * @return    bool
     */
    private function less_than($str, $max)
    {
        return is_numeric($str) ? ($str < $max) : FALSE;
    }

    /**
     * Equal to or Less than
     *
     * @param    string
     * @param    int
     * @return    bool
     */
    private function less_than_equal_to($str, $max)
    {
        return is_numeric($str) ? ($str <= $max) : FALSE;
    }

    /**
     * Value should be within an array of values
     *
     * @param    string
     * @param    string
     * @return    bool
     */
    private function in_list($value, $list)
    {
        return in_array($value, explode(',', $list), TRUE);
    }

    /**
     * Alpha
     *
     * @param    string
     * @return    bool
     */
    private function alpha($str)
    {
        return ctype_alpha($str);
    }

    /**
     * Alpha
     *
     * @param    string
     * @return    bool
     */
    private function alpha_spaces($str)
    {
        return (bool)preg_match('/^[a-zA-Z ]+$/i', $str);
    }

    /**
     * Alpha-numeric
     *
     * @param    string
     * @return    bool
     */
    private function alpha_numeric($str)
    {
        return ctype_alnum((string)$str);
    }

    /**
     * Alpha-numeric w/ spaces
     *
     * @param    string
     * @return    bool
     */
    private function alpha_numeric_spaces($str)
    {
        return (bool)preg_match('/^[A-Z0-9 ]+$/i', $str);
    }

    /**
     * Alpha-numeric with underscores and dashes
     *
     * @param    string
     * @return    bool
     */
    private function alpha_dash($str)
    {
        return (bool)preg_match('/^[a-z0-9_-]+$/i', $str);
    }

    /**
     * Numeric
     *
     * @param    string
     * @return    bool
     */
    private function numeric($str)
    {
        return (bool)preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $str);
    }

    /**
     * Integer
     *
     * @param    string
     * @return    bool
     */
    private function integer($str)
    {
        return (bool)preg_match('/^[\-+]?[0-9]+$/', $str);
    }

    /**
     * Decimal number
     *
     * @param    string
     * @return    bool
     */
    private function decimal($str)
    {
        return (bool)preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str);
    }

    /**
     * Is a Natural number  (0,1,2,3, etc.)
     *
     * @param    string
     * @return    bool
     */
    private function is_natural($str)
    {
        return ctype_digit((string)$str);
    }

    /**
     * Is a Natural number, but not a zero  (1,2,3, etc.)
     *
     * @param    string
     * @return    bool
     */
    private function is_natural_no_zero($str)
    {
        return ($str != 0 && ctype_digit((string)$str));
    }

    /**
     * Valid Email
     *
     * @param    string
     * @return    bool
     */
    private function valid_email($str)
    {
        if (function_exists('idn_to_ascii') && sscanf($str, '%[^@]@%s', $name, $domain) === 2) {
            $str = $name . '@' . idn_to_ascii($domain);
        }
        return (bool)filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Valid Emails
     *
     * @param    string
     * @return    bool
     */
    private function valid_emails($str)
    {
        if (strpos($str, ',') === FALSE) {
            return $this->valid_email(trim($str));
        }
        foreach (explode(',', $str) as $email) {
            if (trim($email) !== '' && $this->valid_email(trim($email)) === FALSE) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * Valid Base64
     *
     * Tests a string for characters outside of the Base64 alphabet
     * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
     *
     * @param    string
     * @return    bool
     */
    private function valid_base64($str)
    {
        return (base64_encode(base64_decode($str)) === $str);
    }

    /**
     *
     * @param $msg
     * @return mixed
     */
    private function error_message($msg)
    {
        $lang['error_message_isset'] = 'The {field} field must have a value.';
        $lang['error_message_error_message_not_set'] = 'Unable to access an error message corresponding to your field name {field}.';
        $lang['error_message_required'] = 'The {field} field is required.';
        $lang['error_message_regex_match'] = 'The {field} field is not in the correct format.';
        $lang['error_message_min_length'] = 'The {field} field must be at least {param} characters in length.';
        $lang['error_message_max_length'] = 'The {field} field cannot exceed {param} characters in length.';
        $lang['error_message_exact_length'] = 'The {field} field must be exactly {param} characters in length.';
        $lang['error_message_less_than'] = 'The {field} field must contain a number less than {param}.';
        $lang['error_message_less_than_equal_to'] = 'The {field} field must contain a number less than or equal to {param}.';
        $lang['error_message_greater_than'] = 'The {field} field must contain a number greater than {param}.';
        $lang['error_message_greater_than_equal_to'] = 'The {field} field must contain a number greater than or equal to {param}.';
        $lang['error_message_in_list'] = 'The {field} field must be one of: {param}.';
        $lang['error_message_alpha'] = 'The {field} field may only contain alphabetical characters.';
        $lang['error_message_alpha_spaces'] = 'The {field} field may only contain alphabetical characters and spaces only.';
        $lang['error_message_alpha_numeric'] = 'The {field} field may only contain alpha-numeric characters.';
        $lang['error_message_alpha_numeric_spaces'] = 'The {field} field may only contain alpha-numeric characters and spaces.';
        $lang['error_message_alpha_dash'] = 'The {field} field may only contain alpha-numeric characters, underscores, and dashes.';
        $lang['error_message_numeric'] = 'The {field} field must contain only numbers.';
        $lang['error_message_is_numeric'] = 'The {field} field must contain only numeric characters.';
        $lang['error_message_integer'] = 'The {field} field must contain an integer.';
        $lang['error_message_decimal'] = 'The {field} field must contain a decimal number.';
        $lang['error_message_is_natural'] = 'The {field} field must only contain digits.';
        $lang['error_message_is_natural_no_zero'] = 'The {field} field must only contain digits and must be greater than zero.';
        $lang['error_message_valid_email'] = 'The {field} field must contain a valid email address.';
        $lang['error_message_valid_emails'] = 'The {field} field must contain all valid email addresses.';
        return $lang[$msg];
    }
}

$validator = new Validator();