<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConferencePaper
 *
 * @author martin
 */
require_once 'Author.php';
require_once 'AuthorConferencePaper.php';
require_once 'ConferenceSession.php';

class ConferencePaper extends Recordset {
    /**
     *
     * @var string  The main query for retrieving conference papers
     */
    var $cs;
        
    var $query = "SELECT cp.id, cp.id as conference_paper_id, cp.title, cp.start_page, cp.end_page, cp.create_date, cp.email, cp.approved,
                                cs.id as session_id, cs.name as session_name,
                                cm.start_date as `date`, cm.name as source_name, cm.id as conference_meeting_id
                                FROM (conference_paper cp, author a, author_conference_paper acp,conference_meeting cm)
                                LEFT JOIN conference_session cs ON cp.conference_session_id = cs.id
                                WHERE cp.id = acp.conference_paper_id AND a.id = acp.author_id AND cp.conference_meeting_id = cm.id  ";

    /**
     *
     * @var array   An array containing the authors of this conference paper
     */
    var $authors = array();

    var $orderBy = " cm.start_date DESC";

    /**
     *
     * @var ConferenceSession
     */


    /**
     * You should use the methods to load the conference meetings instead
     *
     */
    function ConferencePaper($id=0)
    {
        $id = (int) $id;
        parent::Recordset($this->query . " AND cp.id = $id","conference_paper");
    }

    function getConferencePapersByAuthorId($author_id)
    {
        $author_id = (int) $author_id;
        
        $this->query .= " AND a.id = $author_id";

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
     * @param ConferenceSession @todo
     */
    function setConferenceSession($conference_session)
    {
        $this->cs = $conference_session;
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
     * You should not be calling this explicityly. It will be called when you save this ConferencePaper
     */
    function saveAuthors()
    {
        $main_author = true;

        foreach($this->authors as $author)
        {
            $author->save();

            $acp = new AuthorConferencePaper($author->getId(),$this->getId());

            $acp->setValue('author_id',$author->getId());
            $acp->setValue('conference_paper_id',$this->getId());
            $acp->setValue('main_author',$main_author ? 1 : 0);

            $main_author = false;

            $acp->save();
        }
    }
}
?>
