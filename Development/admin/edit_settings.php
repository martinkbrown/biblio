<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$filename = 'settings/' . $_GET['target'] . '.txt';
$lines = file($filename);
$keys = array();

foreach($lines as $line)
{
    $setting = explode("=",$line);

    array_push($keys,trim($setting[0]));
}

if($_POST)
{
    $fp = fopen($filename,'w');

    foreach($keys as $key)
    {
        if($key)
        {
            fwrite($fp,$key . "=" . $_POST[$key] . "\n");
        }
    }

    fclose($fp);
}

echo $_GET['target'];

?>

<br/><br/>

<form name="settings" method="POST">
    <table>
    <?php

    foreach($lines as $line)
    {
        $setting = explode("=",$line);
        $key = trim($setting[0]);
        $value = trim($setting[1]);
        ?>
        <tr>
            <td>
                <?php echo $key ?>
            </td>
            <td>
                <input size="50" type="text" name="<?php echo $key ?>" value="<?php echo $_POST[$key] ? $_POST[$key] : $value ?>"/>
            </td>
        </tr>
        <?php
    }

    ?>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Save"/>
            </td>
        </tr>
    </table>
</form>