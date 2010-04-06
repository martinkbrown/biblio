<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JournalPaper
 *
 * @author martin
 */
class JournalPaper extends Recordset
{
    /**
     *
     * @var string  The main query for retrieving journal meetings
     */
    var $query = "SELECT jp.id, jp.title, jp.start_page, jp.end_page, jp.create_date, jp.email, jp.approved, jp.volume, jp.number,
                                jvn.date as `date`, j.name as source_name
                                FROM (journal_paper jp, author a, author_journal_paper ajp, journal j)
                                LEFT JOIN journal_volume_number jvn ON (jp.number = jvn.number AND jp.volume = jvn.volume AND jp.journal_id = jvn.journal_id)
                                WHERE jp.id = ajp.journal_paper_id AND a.id = ajp.author_id AND j.id = jp.journal_id  ";

        /**
     *
     * @var array   An array containing the authors of this journal paper
     */
    var $authors = array();

    var $orderBy = " jvn.date DESC";

    /**
     * You should use the methods to load the journal meetings instead
     *
     */
    function JournalPaper($id=0)
    {
        parent::Recordset($this->query . " AND jp.id = '$id'","journal_paper");
    }

    function getJournalPapersByAuthorId($author_id)
    {
        $this->query .= " AND a.id = '$author_id'";

        $this->loadByQuery($this->query . " ORDER BY " . $this->orderBy);
    }

    /**
     *
     * @param Author $author    An Author object. Should contain the information about one of the paper's authors
     */
    function addAuthor($author)
    {
        array_push($this->authors,$author);
    }

    /**
     *
     * @return bool     You should use the save() function
     */
    function insert()
    {
        if(parent::insert())
        {
            $this->saveAuthors();
        }

        else return false;

        //return $this->cs->save();
    }

    /**
     *
     * @return bool     You should use the save() function
     */
    function update()
    {
        if(parent::update())
        {
            $this->saveAuthors();
        }

        else return false;

        //return $this->cs->save();
    }

    /**
     * You should not be calling this explicitly. It will be called when you save this JournalPaper
     */
    function saveAuthors()
    {
        $main_author = true;

        foreach($this->authors as $author)
        {
            $author->save();

            $ajp = new AuthorJournalPaper($author->getId(),$this->getId());

            $ajp->setValue('author_id',$author->getId());
            $ajp->setValue('journal_paper_id',$this->getId());
            $ajp->setValue('main_author',$main_author ? 1 : 0);

            $main_author = false;

            $acp->save();
        }
    }
}
?>
