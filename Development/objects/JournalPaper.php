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
     * @var string  The main query for retrieving conference meetings
     */
    var $query = "SELECT cm.id, cm.name, cm.city, s.id as state_id, s.name as state_name, s.abbreviation as state_code,
                                country.id as country_id, country.name as country_name, cm.publisher_website,
                                cm.conference_website, cm.isbn,
                                cm.start_date, cm.end_date, cm.approved, p.id as publisher_id,
                                p.name as publisher_name,
                                c.name as conference_name
                                FROM (conference_meeting cm, conference c, country)
                                LEFT JOIN state s ON cm.state_id = s.id
                                LEFT JOIN publisher p ON cm.publisher_id = p.id
                                WHERE c.approved = 1 AND cm.country_id = country.id AND cm.conference_id = c.id ";

    /**
     * You should use the methods to load the conference meetings instead
     *
     */
    function JournalPaper($id=0)
    {
        parent::Recordset($id,"journal_paper");
    }
}
?>
