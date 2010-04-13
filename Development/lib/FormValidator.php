<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of form_validator
 *
 * @author martin
 */
class FormValidator {
    /**
     *
     * @var array   associative array to store the errors generated by this form
     */
    var $errors = array();

    var $messages = array();

    /**
     * Function checks if data is a number and that it is positive. If not, false is returned and an error message is added
     * to the messaging queue using addError function so that it can be printed with other errors.
     * @param <type> $label name of the label in the form where that data is entered in HTML doc
     * @param <type> $value value or the actualy data stored that we need to check
     * @return <type> bool - true if value can be evaluated to be a positive number, false if otherwise
     */
    function isPositiveNumber($label,  $value) {
        if (is_numeric($value) && $value > 0) {
            return true;
        }else {
            $this->addError($label, $label." must be a postive integer");
            return false;
        }
        /**
         * Function checks that the value is a number or not. If not, false is returned and an error message is added
         * to the messaging queue using addError function so that it can be printed with other errors.
         * @param <type> $label name of the in the HTML form that data represents
         * @param <type> $value the actual data to be tested from the text box or so in the HTML form
         * @return <type> bool - true if value can be evaluated as a number, false if otherwise
         */
        function isANumber($label,  $value) {
            if (is_numeric($value)) {
                return true;
            }else {
                $this->addError($label, $label." must be a number");
                return false;
            }
        }

        function num_diff_valid($label,  $num1, $num2) {
            if ($num1 > $num2) {
                $value = "invalid_order";
                $error = "Your start page is greater than your end page";
                $this->addError($value, $error);
                return false;
            }
            else return true;
        }
        /**
         * Links an error to a specific field in a form
         * @param string $value     the name of the field that this error will be linked to
         * @param string $error     the error, in plain English
         */

        function oracle_string($string,$confim_string) {
            if ($string != $confim_string) {
                $value = "email";
                $error="Emails do not match!!!";
                $this->addError($value, $error);
                return false;
            }
            return true;
        }

        function addError($value,$error) {
            if($this->errors[$value]) $value = "_" . $value;
            $this->errors[$value] = "<span class=\"form_error\">" . $error . "</span>";
        }

        function addMessage($value,$message) {
            $this->messages[$value] = "<span class=\"form_message\">" . $message . "</span>";
        }

        /**
         * This method checks two (2) values and determines whether they are the same or equal. This method
         * produces an error message and returns false if the values are not equal and only returns true
         * if the values are equal.
         * @param <type> $value1 One value that you want to compare
         * @param <type> $value2 The other value you want to compare to
         * @param <type> $label The name of the field on the actual page
         * @param <type> $error The error messages you want user to see when values are not equal
         * @return bool true if values are equal and false if not
         */
        function isEqual($value1, $value2, $label, $error) {
            if ($value1 != $value2) {
                $this->addError($label, $error);
                return false;
            } else {
                return true;
            }
        }

        /**
         * If there are errors within the form, returns true, otherwise false
         * @return bool
         */
        function hasErrors() {
            return sizeof($this->errors) > 0;
        }

        /**
         * Simply return the associative array containing the errors
         * @return array
         */
        function getErrors() {
            return $this->errors;
        }

        /**
         * Display the errors wherever this method is called. Each error appears on a new line
         */
        function listErrors() {
            foreach($this->errors as $key => $value) {
                echo $value."<br/>";
            }
        }

        function listMessages() {
            foreach($this->messages as $key => $value) {
                echo $value."<br/>";
            }
        }

        /**
         * Determine whether $value is an alpha string, containing only letters
         * @param string $value     the string that needs to be checked
         * @param string $label     the label of the field in the form
         * @return bool
         */
        function isAlpha($value,$label = "") {
            $pattern = "/^[a-zA-Z]+$/";
            if(preg_match($pattern, $value)) {
                return true;
            }
            else {
                return false;
                $this->addError($value,$label." should contain only letters");
            }
        }

        /**
         * Check if the datatype of the value specified violates the datatype of its corresponding
         * field in the database
         * @todo Check for violations against char, float, double, if permitted by MySql
         * @param string $table     The name of the table to which the field belongs
         * @param string $value     The string to be checked
         * @param string $label     The label of the field in the form
         * @return bool
         */
        function violatesDbType($table,$field,$value,$label = "") {
            $table = new Recordset('',$table,true,"DESCRIBE $table");

            do {
                if($table->getValue("Field") == $field) {
                    $type = $table->getValue("Field");

                    if(strpos($type,"int") !== false) {
                        if(!is_integer($value)) {
                            $this->addError($value,$label." must be a whole number");
                            return false;
                        }
                        else {
                            return true;
                        }
                    }

                }



            }while($table->next());

            return false;
        }

        /**
         * Simply check if a value is null or not
         * @param string $value     The value that needs to be checked
         * @param string $label     The label of the field in the form
         * @return bool
         */
        function isNull($value,$label = "") {
            if(trim($value) == "") {
                $this->addError($value,$label." is required");
                return true;
            }
            else {
                return false;
            }
        }

        /**
         * Check if the $value violates the NOT_NULL property of the
         * $field specified by $table
         * @param string $table     The database table to which $field belongs
         * @param string $field     The name of the field in the table $table
         * @param string $value     The value that needs to be checked
         * @param string $label     The label of the field in the form
         * @return bool
         */
        function violatesDbNull($table,$field,$value,$label = "") {
            $sql =& sql();
            $query = "SELECT {$field} FROM {$table}";
            $sql->query($query);
            $db_field = $sql->fetchField();

            if($db_field->not_null && trim($value) == "") {
                $this->addError($field,$label." is required");
                return true;
            }
            else {
                return false;
            }
        }

        /**
         * Checks if $value violates the unique constraint of the $field
         * within the table $table
         * @param <type> $table     The name of the table
         * @param <type> $field     The name of the field within the table
         * @param <type> $value     The value that needs to be checked
         * @param <type> $label     The label of the field in the form
         * @return <type>
         */
        function violatesDbUnique($table,$field,$value,$label = "") {
            $sql =& sql();

            $table_id = $table . '_id';
            $id = $_GET[$table_id];
            $value = $sql->escape($value);

            $query = "SELECT {$field} FROM {$table} WHERE id != '{$id}' AND $field = '{$value}' LIMIT 1";
            $sql->query($query);
            $db_field = $sql->fetchField();

            if($db_field->unique_key) {
                if($sql->count()) {
                    $this->addError($field,$label." \"".$value."\" already exists");
                    return true;
                }
            }

            return false;
        }

        /**
         * Checks if the length of $value violates the maximum length of its corresponding
         * field in the database
         * @param string $table     The table to which the $field belongs
         * @param string $field     The field that needs to be compared to $value
         * @param string $value     The value that needs to be checked
         * @param string $label     The label of the field in the form
         * @return bool
         */
        function violatesDbLength($table,$field,$value,$label = "") {
            $sql =& sql();
            $query = "SELECT {$field} FROM {$table} LIMIT 1";
            $sql->query($query);
            $length = $sql->fetchLength();

            if(strlen(trim($value)) > $length) {
                $this->addError($field,$label . " must be less than " . ($length + 1) . " characters long");
                return true;
            }
            else {
                return false;
            }
        }

        /**
         * Checks if a value violates any of the constraints supported by this class
         * @param string $table     The name of the table containing the field to be checked
         * @param string $field     The name of the field to be checked
         * @param string $value     The value to be checked
         * @param string $label     The label of the field in the form
         * @return bool
         */
        function violatesDbConstraints($table,$field,$value,$label="") {
            return $this->violatesDbNull($table,$field,$value,$label) ||
                    $this->violatesDbUnique($table,$field,$value,$label) ||
                    $this->violatesDbLength($table,$field,$value,$label) ||
                    $this->violatesDbType($table,$field,$value,$label);
        }

        /**
         * Checks if the string $value is a valid email address
         * @param string $field     The name of the corresponding field in the database
         * @param string $value     The variable containing the string to be checked
         * @param string $label     The label of the field in the form
         * @return bool
         */
        function isEmailAddress($field,$value,$label = "") {
            $pattern = "/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/";

            if(preg_match($pattern, $value)) {
                return true;
            }
            else {
                $this->addError($field,"\"$value\" is not a valid email address");
                return false;
            }
        }

        /**
         *
         * @param string $label1    The label of the START DATE in the form
         * @param string $label2    The label of the END DATE in the form
         * @param string $date1     The start date
         * @param string $date2     The end date
         * @return bool
         */
        function isValidDateRange($label1,$label2,$date1, $date2) {
            if(strpos($date1,"/") !== false) $date1 = strtotime($date1);
            if(strpos($date2,"/") !== false) $date2 = strtotime($date2);

            $date1 = (int) $date1;
            $date2 = (int) $date2;

            if($date2 >= $date1) {
                return true;
            }
            else {
                $this->addError($date1, $label1 . " must earlier than " . $label2);
                return false;
            }
        }

        function isValidCaptcha($private_key) {
            $resp = recaptcha_check_answer ($private_key,
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["recaptcha_challenge_field"],
                    $_POST["recaptcha_response_field"]);

            if (!$resp->is_valid) {
                $this->addError("recaptcha","The reCAPTCHA wasn't entered correctly. Go back and try it again.");
                return false;
            }
            else {
                return true;
            }
        }

        /**
         * Validates an ISBN number. @see ISBNtest.php
         *
         * @param string $field     The name of the field to be checked
         * @param string $isbn      The value of the ISBN
         * @param string $label     The label of the field in the form
         * @return mixed            The ISBN number if it is valid, otherwise returns false
         */
        function isValidIsbn($field,$isbn,$label) {
            if(!$isbn)  return false;

            $error = $label . " is not a valid ISBN number. ";

            $currISBN = new ISBNtest();
            $currISBN->set_isbn($isbn);

            if ($currISBN->valid_isbn10() === true) {
                return $currISBN->get_isbn10();
            }
            else {
                $this->addError($field,$error);

                return false;
            }

            if ($currISBN->valid_gtin14() === true) {
                return $currISBN->get_gtin14();
            }
            else {
                $this->addError($field,$error);

                return false;
            }

            if ($currISBN->valid_isbn13() === true) {
                return $currISBN->get_isbn13();
            }
            else {
                $this->addError($field,$error);

                return false;
            }

        }
    }

?>
