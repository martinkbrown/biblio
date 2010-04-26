<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthorJournalPaper
 *
 * @author martin
 */
class AuthorJournalPaper extends Recordset
{
    function AuthorJournalPaper($author_id,$paper_id)
    {
        $author_id = (int) $author_id;
        $paper_id = (int) $paper_id;

        $query = "SELECT * FROM author_journal_paper WHERE author_id = $author_id AND journal_paper_id = $paper_id";

        parent::Recordset($query,"author_journal_paper");

        $this->loadByQuery($query);
    }

}
?>
