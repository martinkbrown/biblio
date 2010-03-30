<?php

    define('FRONT_END','./');
    define('BACK_END','admin/');
    require_once FRONT_END . 'config.php';

    require_once OBJECTS . 'Author.php';

    $a = new Author();
    $a->setValue('firstname',$_GET['firstname']);
    $a->setValue('lastname',$_GET['lastname']);

    $author = $a->getSimilarAuthors();
    $paper_counter = 0;
    $papers = "";

    if($author->id)
    {
        do
        {
            $got_papers = false;
            $papers = "";
            $sm .= "<span class=\"did_you_mean\">Did you mean
                    <a href=\"javascript:;\">" . $author->firstname . " " . $author->initial . " " .
                                    $author->lastname . "</a>, author of ";

            $conf_paper = $author->conference_papers;

            if($conf_paper->id)
            {
                $got_papers = true;
                do
                {
                    $papers .= "\"<b>" . $conf_paper->title . "</b>\", ";
                    $paper_counter++;

                }while($conf_paper->next() && $paper_counter < 3);
            }

            if($counter < 3)
            {
                $journal_paper = $author->journal_papers;

                if($journal_paper->id)
                {
                    $got_papers = true;
                    do
                    {
                        $papers .= "\"<b>" . $journal_paper->title . "</b>\", ";
                        $paper_counter++;

                    }while($journal_paper->next() && $paper_counter < 3);
                }
            }

            $papers = substr($papers,0,strlen($papers)-2);

            $sm .= $papers . "?</span><br/>\n";

            if($got_papers) echo $sm;

        }while($author->next());
    }

?>
