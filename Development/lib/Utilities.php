<?php

class Utilities
{
    function redirect($location)
    {
        header("Location: ".$location);
    }
}

?>