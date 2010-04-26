<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JournalVolumeNumber
 *
 * @author martin
 */
class JournalVolumeNumber extends Recordset
{
    function JournalVolumeNumber($journal_id=0,$volume=0,$number=0)
    {
        $journal_id = (int) $journal_id;
        $volume = (int) $volume_id;
        $number = (int) $number;

        $query = "SELECT * FROM journal_volume_number WHERE number = $number AND journal_id = $journal_id
                    AND volume = $volume";

        parent::Recordset(0,"journal_volume_number");

        $this->loadByQuery($query);
    }

    function loadJournalVolumesByJournalId($journal_id)
    {
        $journal_id = (int) $journal_id;

        $query = "SELECT volume, id, journal_id FROM journal_paper
                    WHERE journal_id = $journal_id GROUP BY journal_id ";

        $this->loadByQuery($query);
    }
}
?>
