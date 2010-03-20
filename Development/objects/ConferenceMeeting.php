<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . LIB . 'Recordset.php';
require_once FRONT_END . LIB . 'Date.php';

/**
 * ConferenceMeeting provides you with several methods for accessing the conference_meeting table.
 * You can load all conference meetings, approved conference meetings, a specific conference meeting,
 * or a set of conference meetings that belong to a specific conference
 *
 * @author martin
 */
class ConferenceMeeting extends Recordset {

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
    function ConferenceMeeting($id=0)
    {
        parent::Recordset($id,"conference_meeting");
    }

    /**
     * Loads all conference meetings
     */
    function loadConferenceMeetings()
    {
        $this->loadByQuery($this->query . " ORDER BY cm.name");
    }

    function loadConferenceMeetingsByKeyword($keyword)
    {
        $this->query .= " AND cm.name LIKE '%{$keyword}%' ";
        $this->loadByQuery($this->query . " ORDER BY cm.name");
    }

    /**
     * Loads approved conference meetings if $approved is true
     * Loads unapproved conference meetings if $approved is fale
     * @param <type> $approved
     */
    function loadApprovedConferenceMeetings()
    {
        $this->query = $this->query . " AND cm.approved = '1'";
        $this->loadByQuery($this->query . " ORDER BY cm.name");
    }

    function loadUnApprovedConferenceMeetings()
    {
        $this->query = $this->query . " AND cm.approved = '0'";
        $this->loadByQuery($this->query . " ORDER BY cm.name");
    }


    /**
     * Loads a specific conference meeting by providing the conference meeting id
     * @param int $conferenceMeetingId
     */
    function loadConferenceMeetingById($conferenceMeetingId)
    {
        $conferenceMeetingId = (int) $conferenceMeetingId;
        $this->query = $this->query . " AND cm.id = '{$conferenceMeetingId}'";
        $this->loadByQuery($this->query . " ORDER BY cm.name");
    }

    /**
     * Loads conference that belong to a specific conference
     * @param int $conferenceId     The id of the conference
     */
    function loadConferenceMeetingsByConferenceId($conferenceId)
    {
        $conferenceId = (int) $conferenceId;
        $this->query = $this->query . " AND c.id = '{$conferenceId}'";
        $this->loadByQuery($this->query . " ORDER BY cm.name");
    }

    /**
     * Find out whether or not the conference is approved
     * @return bool
     */
    function isApproved()
    {
        return (bool) $this->getValue('approved');
    }

    /**
     * Private method. Sets the values to UNIX timestamp
     */
    function setDateValues()
    {
        $this->setValue('start_date',strtotime($this->getValue('start_date')));
        $this->setValue('end_date',strtotime($this->getValue('end_date')));
    }

    /**
     * Sets the date values to UNIX timestamp format before insertion.
     * Also makes sure that the conference meeting is initially not approved
     * @see Recordset::insert()
     * @return bool
     */
    function insert()
    {
        $this->setDateValues();
        
        return parent::insert();
    }

    /**
     * Sets the values to UNIX timestamp before updating
     * @see Recordset::update()
     * @return bool
     */
    function update()
    {
        $this->setDateValues();

        return parent::update();
    }

    /**
     * Formats the date values for display on a webpage because the dates
     * are in the UNIX timestamp format
     * @return bool
     */
    function next()
    {
        $result = parent::next();

        if($result)
        {
            $this->setValue('start_date',Date::getDate($this->getValue('start_date'),SHORT_DATE));
            $this->setValue('end_date',Date::getDate($this->getValue('end_date'),SHORT_DATE));
        }

        return $result;
    }
}
?>
