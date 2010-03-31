<?php

    header("Content-type: text/xml");

    define('FRONT_END','./');
    define('BACK_END','admin/');
    require_once FRONT_END . 'config.php';

    require_once OBJECTS . 'Author.php';


    $author = new Author();

    $query = $author->query;

    $id = $_GET['id'];

    $query .= " AND a.firstname = '" . $_GET['firstname'] . "' AND a.lastname = '" . $_GET['lastname'] . "'";

    if($id)
    {
        $ids = implode(",",$id);
        $query .= " AND a.id NOT IN (" . $ids . ")";
    }

    $author->loadByQuery($query);
    
    $paper_counter = 0;
    $papers = "";
    $sm = '<?xml version="1.0" encoding="iso-8859-1"?>';
    $sm .= "<authors>\n";
    if($author->id)
    {
        do
        {
            $sm .= "<author>";
            $got_papers = false;
            $papers = "";
            $sm .= "<id>" . $author->id . "</id>";
            $sm .= "<firstname>" . $author->firstname . "</firstname>\n";
            $sm .= "<initial>" . $author->initial . "</initial>\n";
            $sm .= "<lastname>" . $author->lastname . "</lastname>\n";

            $conf_paper = $author->conference_papers;

            $sm .= "<papers>";

            if($conf_paper->id)
            {

                $got_papers = true;
                do
                {
                    $sm .= "<paper>" . $conf_paper->title . "</paper>\n";
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
                        $sm .= "<paper>" . $journal_paper->title . "</paper>";
                        $paper_counter++;

                    }while($journal_paper->next() && $paper_counter < 3);
                }
            }

            $sm .= "</papers>\n</author>\n";

        }while($author->next());

        $sm .= "</authors>\n";

        echo $sm;
    }

?>
