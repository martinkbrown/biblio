<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Recordset.php';

/**
 * Description of Grid
 *
 * @author martin
 */
class Grid {
    //put your code here

    /**
     *
     * @var string  The actual grid, in HTML
     */
    var $grid;

    /**
     *
     * @var string  The navigation bar at the bottom of the grid, in HTML
     */
    var $pagination;

    /**
     *
     * @var array   The columns that will be displayed in the grid
     */
    var $columns = array();

    /**
     *
     * @var array   The fields that a part of the Recordset's query
     */
    var $fields = array();

    /**
     *
     * @var string  The column that the grid will be sorted by
     */
    var $sortBy;

    /**
     *
     * @var int     How the grid will be sorted
     */
    var $sortOrder = SORT_ASC;

    /**
     *
     * @var int     The record number that the grid starts at. This is important for pagination
     */
    var $startingRecord = 0;

    /**
     *
     * @var int     How many results per page will be displayed
     */
    var $resultsPerPage = 5;

    /**
     *
     * @var int     How many page numbers to display in the navigation bar
     */
    var $maxPageNumbers = 20;

    /**
     *
     * @var int     The number of results in the grid
     */
    var $results;

    /**
     *
     * @var string  The full URL
     */
    var $queryString;

    /**
     *
     * @var string  The URL formatted for creating pagination links
     */
    var $queryStringPagination;

    /**
     *
     * @var string  The URL formatted for creating sorting links
     */
    var $queryStringSort;

    /**
     *
     * @var bool    True if you want to number the results
     */
    var $gridNumbered = false;

    /**
     *
     * @var bool    True if you want to display the column titles
     */
    var $gridTitles = true;

    /**
     *
     * @var bool    True if you want to alternate CSS class names for rows
     */
    var $gridAlternate_rows = true;

    /**
     *
     * @var bool    True if you want to have a navigation bar at the bottom of the grid
     */
    var $gridPaginate = true;

    /**
     *
     * @var bool    True if you want the grid to be sortable by column
     */
    var $gridSortable = true;

    /**
     * @var bool    True if you want checkboxes at the end of the grid, as well as a select all/none option
     */
    var $gridSelect = false;

    /**
     * Constructor
     */
    function Grid()
    {
        $this->queryString = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
        $this->queryStringPagination = str_replace('&pageNo=' . $_GET['pageNo'],"",$this->queryString);
        $this->queryStringSort = str_replace('&sortBy=' . $_GET['sortBy'],"",$this->queryString);

        if($_GET['sortBy'])
        {
            $this->sortBy = $_GET['sortBy'];
        }
    }

    /**
     *
     * @param Recordset $recordset  A Recordset object from which the Grid will be created
     * @return string               The Grid, in HTML
     */
    function createGridFromRecordset($recordset)
    {
        if($recordset->id)
        {
            $array = array();

            $haveFields = false;

            do
            {
                $row = array();

                //prepare the array to be passed to Grid::createGridFromArray()
                foreach($recordset->getValues() as $key=>$value)
                {
                    $row[$key] = $value;
                    if(!$haveFields)    array_push($this->fields,$key);
                }

                foreach($recordset->getForeignValues() as $key=>$value)
                {
                    $row[$key] = $value;
                    if(!$haveFields)    array_push($this->fields,$key);
                }

                $array[$recordset->id] = $row;

                $haveFields = true;

            }while($recordset->next());
        }

        return $this->createGridFromArray($array);
    }

    /**
     *
     * @param array $array      An array of associative arrays. @example array(array("name"=>"Name"),array("city"=>"City"))
     */
    function createGridFromArray($array)
    {
        //get the number of results in the array
        $this->results = sizeof($array);

        if($_GET['pageNo'])
        {
            $this->startingRecord = ($_GET['pageNo'] - 1) * $this->resultsPerPage;
        }

        if(!$this->results)
        {
            $this->grid = "No results found";
            return;
        }

        //if sortBy is not specified, then sort by the first column
        if(!$this->sortBy)
        {
            $keys = array_keys($this->columns);
            $this->sortBy = $keys[0];
        }

        $this->grid = "<table>\n";

        $stringOfColumns = "\\$" . $this->sortBy . "," . $this->sortOrder . "," . SORT_STRING . ",\\$" . "id";

        //this will store the ids of the results
        $id = array();

        //create an array for each column to store the results
        foreach($this->fields as $k)
        {
            $$k = array();
            if($k != $this->sortBy)    $stringOfColumns .= ",\\$" . $k;
        }

        foreach($array as $key=>$row)
        {
            //store the ids of each result in the $id array
            $id = array_merge($id,array($key=>$key));

            //store the results of each column
            foreach($this->fields as $k)
            {
                $$k = array_merge($$k,array($key=>$row[$k]));
            }
        }

        //sort the grid
        eval("array_multisort({$stringOfColumns});");

        if($this->gridTitles)
        {
            $this->setGridTitles();
        }

        //iterate the selected results
        for($i = $this->startingRecord; $i < ($this->results) && ($i < ($this->resultsPerPage + $this->startingRecord)); $i++)
        {
            $this->grid .= "\t<tr>\n";

            //display the item number to the left of the grid
            if($this->gridNumbered)
            {
                $this->grid .= "\t\t<td>" . ($i+1) . "</td>\n";
            }

            //prepare an array that contains a record
            $row = array();
            $row['id'] = $id[$i];

            //create the array for the a single row first
            foreach($this->fields as $key)
            {
                $v = $$key;
                $row[$key] = $v[$i];
            }

            //display the values
            foreach($this->columns as $key=>$value)
            {
                //build the string for the handler method
                $method = "handle" . strtoupper($key[0]) . substr($key,1);

                $this->grid .= "\t\t<td>";

                //check if a handler exists for the specified column
                if(method_exists($this, $method))
                {
                    eval("\$this->grid .= \$this->\$method(\$row);");
                }
                else
                {
                    $this->grid .= $row[$key];
                }

                $this->grid .= "</td>\n";
            }

            //checkboxes
            if($this->gridSelect)
            {
                if(is_array($row['id']))
                {
                    $checked = in_array($row['id'],$_POST['ids']) ? "checked" : "";
                }
                $this->grid .= "\t\t<td>\n\t\t\t
                                <input type=\"checkbox\" name=\"ids[]\" value=\"{$row['id']}\" {$checked}\"/>
                            \n\t\t</td>";
            }

            $this->grid .= "\t</tr>\n";
        }

        $this->grid .= "</table>\n";

        unset($array);

        if($this->gridPaginate)
        {
            $this->setPagination();
        }
    }
    /**
     * Add a navigation bar to the grid
     */
    function setPagination()
    {
        //only show the navigation bar if not all results can fit on the page
        if($this->results > ($this->resultsPerPage - $this->startingRecord))
        {
            $this->pagination = "<p>\n";

            $pageNo = $_GET['pageNo'];
            if(!$pageNo)    $pageNo = 1;

            //show a link to the previous page if it's not the first page
            if($pageNo > 1)
            {
                $this->pagination .= "\t<span>\n\t\t<a href=\"". $this->queryStringPagination .
                                      "&pageNo=". ($pageNo-1). "\">Previous</a></span>\t";
            }

            $dots = false;

            $mid = $this->maxPageNumbers / 2;

            //show the page numbers as links
            for($i = 0; (($i * $this->resultsPerPage) < $this->results); $i++ )
            {

                if((($this->results / $this->resultsPerPage) > $this->maxPageNumbers))
                {
                    if($i >= ($pageNo + $mid - 1) || $i <= ($pageNo - $mid - 1))
                    {
                        continue;
                    }
                }

                $this->pagination .= "\t<span>\n";

                if($pageNo != ($i+1))  $this->pagination .= "\t\t<a href=\"". $this->queryStringPagination .
                                      "&pageNo=". ($i+1). "\">";

                $this->pagination .= ($i+1);

                if($pageNo != ($i+1))  $this->pagination .= "</a>\n";

                $this->pagination .= "\t</span>";
            }

            //show a link to the next page if you're not on the last page
            if($pageNo * $this->resultsPerPage < $this->results)
            {
                $this->pagination .= "\t<span>\n\t\t<a href=\"". $this->queryStringPagination .
                                      "&pageNo=". ($pageNo+1). "\">Next</a></span>\t";
            }

            $this->pagination .= "</p>";
        }

        $this->grid .= $this->pagination;
    }

    /**
     * Shows the title of each column in the Grid
     */
    function setGridTitles()
    {
        if($this->gridTitles)
        {
            $this->grid .= "\t<tr>\n";

            //blank for the table header
            if($this->gridNumbered)
            {
                $this->grid .= "\t\t<th>&nbsp;</th>\n";
            }

            foreach($this->columns as $key=>$value)
            {
                $this->grid .= "\t\t<th>";

                //make the title a link if the Grid is sortable
                if($this->gridSortable && in_array($key,$this->fields))
                {
                    $this->grid .= "<a href=\"" . $this->queryStringSort . "&sortBy=" . $key . "\">";
                }

                $this->grid .= $value;

                if($this->gridSortable && in_array($key,$this->fields))
                {
                    $this->grid .= "</a>";
                }
                
                $this->grid .= "</th>\n";
            }

            //checkboxes
            if($this->gridSelect)
            {
                $this->grid .= "\t\t<th>\n\t\t\t
                                    <input type=\"checkbox\" id=\"check_all\" name=\"check_all\"
                                    onclick=\"
                                    var ids=document.getElementsByName('ids[]');
                                    for(i=0;i<ids.length;i++)
                                    {
                                        ids[i].checked=this.checked;
                                    }\"
                                    />
                                \n\t\t</th>";
            }

            $this->grid .= "\t</tr>\n";
        }
    }

    /**
     *
     * @return string       The Grid, in HTML
     */
    function getGrid()
    {
        return $this->grid;
    }

    /**
     * Formats the title of the specified column
     * @param string $field     The field or column that needs to be formatted
     * @param string $label     The actual caption that will be displayed as the column title
     */
    function setColumnTitle($field,$label)
    {
        $this->columns[$field] = $label;
    }

    /**
     * Specify the field by which the Grid should be sorted
     * @param string $field     The field by which the grid should be sorted
     */
    function setSortField($field)
    {
        $this->sortBy = $field;
    }

    /**
     * Specify how the Grid should be sorted
     * @param string $order     @example SORT_ASC | SORT_DESC
     */
    function setSortOrder($order)
    {
        $this->sortOrder = $order;
    }

    /**
     * Specify which record is the first in the Grid
     * This is important for pagination
     * @param int $recordNumber
     */
    function setStartinRecord($recordNumber)
    {
        $this->startingRecord = $recordNumber;
    }

    /**
     * The number of results that should be displayed in the Grid at one time
     * @param int $resultsPerPage
     */
    function setResultsPerPage($resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;
    }

    /**
     * Set the maximum number of pages to be displayed in the navigation bar
     * @param int $maxPageNumbers
     */
    function setMaxPageNumbers($maxPageNumbers)
    {
        $this->maxPageNumbers = $maxPageNumbers;
    }

    /**
     * Specify that the grid should have checkboxes
     */
    function setGridSelect()
    {
        $this->gridSelect = true;
    }

    /**
     * The total number of results in the Grid in all pages combined
     * @return int
     */
    function getNumberOfResults()
    {
        return $this->results;
    }
}
?>
