<?php
require_once(dirname(__FILE__) . '/includes.php');
if (file_exists($feedsfile))
{
$feeds = loadFeeds($feedsfile);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" href="style.css" type="text/css" />
        
    </head>
    <body>
        <a href="Feeds.php">Feeds</a>
        <a href="FeedsFile.php">FeedsFiles</a>
        <a href="settings.php">Settings</a>
    <pre><?php 
    if (file_exists($feedsfile))
{
    ?></pre>
    <table border="1">
         
            <?php
            foreach ($feeds->feeds as $name => $feed) {
                ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><a href="<?php echo $feed->url; ?>"><?php echo htmlentities ($feed->url); ?> </a></td>
                    <td><?php echo $feed->folder; ?></td>   
                    <td>
                        
                        
                        
                    </td>
                    
                    <td></td>


                </tr>
                <?php
            }
            ?>
        </table>

<? } else { ?>

No feeds checked.
        
        <? }
?>

    </body>
</html>


