<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthorConferencePaper
 *
 * @author martin
 */
class AuthorConferencePaper extends Recordset
{
    function AuthorConferencePaper()
    {
        parent::Recordset(0,"author_conference_paper");
    }

    function AuthorConferencePaper($author_id,$paper_id)
    {
        $query = "SELECT * FROM author_conference_paper WHERE author_id = '$author_id' AND conference_paper_id = '$paper_id'";
        $this->loadByQuery($query);
    }
}
?>
