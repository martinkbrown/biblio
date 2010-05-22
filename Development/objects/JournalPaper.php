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
require_once 'JournalVolumeNumber.php';

class JournalPaper extends Recordset
{
    /**
     *
     * @var string  The main query for retrieving journal meetings
     */
    var $query = "SELECT jp.id, jp.id as journal_paper_id, jp.title, jp.start_page, jp.end_page, jp.create_date, jp.email, jp.approved, jp.volume, jp.number,
                                jvn.date as `date`, j.id as journal_id, j.name as source_name
                                FROM (journal_paper jp, author a, author_journal_paper ajp, journal j)
                                LEFT JOIN journal_volume_number jvn ON (jp.number = jvn.number AND jp.volume = jvn.volume AND jp.journal_id = j.id)
                                WHERE jp.id = ajp.journal_paper_id AND a.id = ajp.author_id AND j.id = jp.journal_id  ";

        /**
     *
     * @var array   An array containing the authors of this journal paper
     */
    var $authors = array();

    var $volumeNumber;

    var $orderBy = " jvn.date DESC";

    /**
     * You should use the methods to load the journal meetings instead
     *
     */
    function JournalPaper($id=0)
    {
        $id = (int) $id;
        if($id)
        {
            $this->query .= " AND jp.id = $id";
            parent::Recordset($this->query,"journal_paper");
        }
        else
        {
            parent::Recordset($this->query . " AND ajp.main_author=1","journal_paper");
        }

        $this->volumeNumber = new JournalVolumeNumber($this->journal_id,$this->volume,$this->number);
    }

    function approve()
    {
        if($this->id)
        {
            $this->setValue("approved",1);
            return parent::update();
        }
        
        return false;
    }

    function suspend()
    {
        if($this->id)
        {
            $this->setValue("approved",0);
            return parent::update();
        }

        return false;
    }

    function loadApprovedJournalPapers()
    {
        $this->query .= " AND jp.approved = 1";
        $this->loadByQuery($this->query);
    }

    function getJournalPapersByAuthorId($author_id)
    {
        $author_id = (int) $author_id;
        
        $this->query .= " AND a.id = $author_id";

        $this->loadByQuery($this->query . " ORDER BY " . $this->orderBy);
    }

    function loadJournalPapersByVolume($journal_id, $volume)
    {
        $journal_id = (int) $journal_id;
        $volume = (int) $volume;
        $this->query .= " AND jp.journal_id = $journal_id AND jp.volume = $volume ";
        $this->loadByQuery($this->query);
    }

    function loadJournalPapersByKeyword($keyword)
    {
        $sql =& sql();
        $keyword = $sql->escape($keyword);

        $this->query .= " AND jp.title LIKE '%{$keyword}%'";

        $this->loadByQuery($this->query . " ORDER BY " . $this->orderBy);
    }

    function setVolumeNumber($volumeNumber,$date)
    {
        $this->setValue("number",$volumeNumber);
        
        $this->volumeNumber = new JournalVolumeNumber($this->journal_id,$this->volume,$volumeNumber);
        $this->volumeNumber->setValue("journal_id",$this->journal_id);
        $this->volumeNumber->setValue("volume",$this->volume);
        $this->volumeNumber->setValue("number",$volumeNumber);
        $this->volumeNumber->setValue("date",mktime($date));
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
            $this->volumeNumber->save();
            return true;
        }

        else return false;

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
            $this->volumeNumber->save();
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

            $ajp->save();
        }
    }
}
?>
