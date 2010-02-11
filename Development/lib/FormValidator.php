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
class FormValidator
{
    //put your code here
    var $errors = array();

    function addError($field,$error)
    {
        $this->errors[$field] = "<span class=\"form_error\">".$error."</span>";
    }

    function hasErrors()
    {
        return (bool) $this->errors;
    }

    function getErrors()
    {
        return $this->errors;
    }

    function listErrors()
    {
        foreach($this->errors as $key => $value)
        {
            echo $value."<br/>";
        }
    }

    function isAlpha($field,$title)
    {
            $pattern = "/^[a-zA-Z]+$/";
            if(preg_match($pattern, $_REQUEST[$field]))
            {
                    return true;
            }
            else
            {
                    return false;
                   $this->addError($field,$title." should contain only letters");
            }
    }

    function violatesDBType($table,$field,$title)
    {
        $table = new recordset('','',true,"DESCRIBE $table");

        do
        {
            if($table->getValue("Field") == $field)
            {
                $type = $table->getValue("Field");

                if(strpos($type,"int") !== false)
                {
                    if(!is_integer($_REQUEST[$field]))
                    {
                        $this->addError($field,$title." must be a whole number");
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }

            }

            $table->next();

        }while($table->getValue("Field") != $field);

        return false;
    }

    function violatesDBNull($table,$field,$title)
    {
        $sql =& sql();
        $query = "SELECT {$field} FROM {$table}";
        $sql->query($query);
        $db_field = $sql->fetchField();
        
        if($db_field->not_null && trim($_REQUEST[$field]) == "")
        {
            $this->addError($field,$title." is required");
            return false;
        }
        else
        {
            return true;
        }
    }

    function violatesDBUnique($table,$field,$title)
    {
        $sql =& sql();
        $query = "SELECT {$field} FROM {$table} WHERE $field = '{$_REQUEST[$field]}' LIMIT 1";
        $sql->query($query);
        $db_field = $sql->fetchField();

        if($db_field->unique_key)
        {
            if($sql->count())
            {
                $this->addError($field,$title." \"".$_REQUEST[$field]."\" already exists");
            }
        }

        return false;
    }

    function violatesDBLength($table,$field,$title)
    {
        $sql =& sql();
        $query = "SELECT {$field} FROM {$table} LIMIT 1";
        $sql->query($query);
        $length = $sql->fetchLength();

        if(strlen(trim($_REQUEST[$field])) > $length)
        {
            $this->addError($field,$title." must be less than ". $length." characters long");
        }
        else
        {
            return false;
        }
    }

    function isEmailAddress($field,$title)
    {
            $pattern = "/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/";

            if(preg_match($pattern, $_REQUEST[$field]))
            {
                    return true;
            }
            else
            {
                    $this->addError($field,$title." is not a valid email address");
                    return false;
            }
    }
}
?>
