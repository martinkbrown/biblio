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
    function AuthorConferencePaper($author_id,$paper_id)
    {
        $author_id = (int) $author_id;
        $paper_id = (int) $paper_id;

        $query = "SELECT * FROM author_conference_paper WHERE author_id = $author_id AND conference_paper_id = $paper_id";

        parent::Recordset(0,"author_conference_paper");

        $this->loadByQuery($query);
    }
}
?>
