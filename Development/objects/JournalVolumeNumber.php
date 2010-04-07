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
class JournalVolumeNumber
{
    function JournalVolumeNumber($journal_id=0,$volume_id=0,$number=0)
    {
        $journal_id = (int) $journal_id;
        $volume = 
        $query = "SELECT * FROM journal_volume_mumber WHERE number = '$author_id' AND journal_paper_id = '$paper_id'";

        parent::Recordset(0,"author_journal_paper");

        $this->loadByQuery($query);
    }
}
?>
