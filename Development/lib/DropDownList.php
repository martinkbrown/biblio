<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * DropDownList takes care of creating drop down lists. All it needs
 * to do so is the name of the field that needs to be displayed and
 * a Recordset object @see Recordset. A Recordset is prefered.
 * The name of the table may also be specified.
 *
 * @author martin
 */
class DropDownList {

    /**
     *
     * @var string  the name of the field to display in the drop down list
     */
    var $field;

    var $fieldName;

    /**
     *
     * @var array   an array containing extra values to display in the drop down list
     */
    var $extraValues = array();

    /**
     *
     * @var array   an array containing the values to be preselected in the drop down list
     */
    var $selected = array();

    /**
     *
     * @var string  whether this drop down list allows multiple selections or not
     */
    var $multiple = false;

    /**
     *
     * @var string  if it is multiple, how many values to show in the drop down list at one time
     */
    var $size;

    /**
     *
     * @var array   an array of HTML properties for the selectbox
     */
    var $properties = array();

    /**
     *
     * @var string  the drop down list represented in HTML
     */
    var $dropDownList;


    /**
     * Constructor
     * @param string $fieldToDisplay    the name of the field that will be used to display values in the drop down
     */
    function DropDownList($fieldToDisplay)
    {
        $this->field = $fieldToDisplay;
        $this->fieldName = $fieldToDisplay;
    }

    function setFieldName($name)
    {
        $this->fieldName = $name;
    }

    /**
     *
     * @param array $values     An array of extra values to display
     * @example array("","None")
     */
    function setExtraValues($values)
    {
        if(!is_array($values)) return;
        $this->extraValues = $values;
    }

    /**
     * Sets the DOM id of the drop down list
     * @param string $id
     */
    function setId($id)
    {
        $this->id = $id;
    }

    /**
     * General method to set the value of an HTML property
     * @param string $property      the name of the property, @example id|class
     * @param string $value
     */
    function setProperty($property,$value)
    {
        $this->properties[$property] = $value;
    }

    /**
     *
     * @param int $size     If the user can select multiple values, specify the size of the drop down
     */
    function isMultiple($size)
    {
        $this->multiple = "multiple size = \"{$size}\"";
    }

    /**
     * Selects certain value(s) when the page loads
     * @param mixed $selectedValues     An integer containing the id or an array of ids to be selected
     */
    function setSelectedValues($selectedValues)
    {
        if(!is_array($selectedValues))
        {
            array_push($this->selected,$selectedValues);
        }
        else
        {
            $this->selected = $selectedValues;
        }
    }

    /**
     * Returns a drop down list with results from a database table. This returns all results from the table
     * @param string $table     the database table
     * @return string           the drop down list ready to be displayed
     */
    function getDropDownFromTable($table)
    {
        $query = "SELECT id, {$this->field} FROM {$table} ORDER BY {$this->field}";
        $recordset = new Recordset($query,$table);

        return $this->getDropDownFromRecordset($recordset);
    }

    /**
     * Returns a drop down list with results from an array
     * @param array $array  @example array(0=>"January",1=>"February")
     * @return string       the drop down list ready to be displayed
     */
    function getDropDownFromArray($array)
    {

        foreach($array as $id=>$value)
        {
            $selected = in_array($id,$this->selected) ? "selected" : "";
            $this->dropDownList .= "\t<option value=\"{$id}\" {$selected}> " .
                        $value . "</option>\n";

        }

        $properties = "";

        foreach($this->properties as $key=>$value)
        {
            $properties .= " " . $key . "=\"{$value}\"";
        }
        
        $openTag = "<select name=\"{$this->fieldName}\" {$properties} {$this->multiple}>\n";
        $closeTag = "</select>";

        return $openTag . $this->dropDownList . $closeTag;
    }

    /**
     * Returns a drop down list with results from a Recordset
     * @param Recordset $recordset  A recordset that has already been loaded
     * @return string               the drop down list ready to be displayed
     */
    function getDropDownFromRecordset($recordset)
    {
        $this->addExtraValues();

        $array = array();

        if($recordset->id)
        {
            do
            {
                $id = $recordset->id;
                $value = $recordset->{$this->field};
                $array[$id] = $value;
            
            }while($recordset->next());
        }

        return $this->getDropDownFromArray($array);
    }

    /**
     * Adds the extra values specified to the drop down list
     */
    function addExtraValues()
    {
        foreach($this->extraValues as $key=>$value)
        {
            $this->dropDownList .= "\t<option value=\"\">{$value}</option>\n";
        }
    }
}
?>
