<?php
require_once(dirname(__FILE__) . '/includes.php');

$feeds = loadFeeds($feedsfile);
?>


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <link rel="stylesheet" href="style.css" type="text/css" />
        <title></title>
    </head>
    <body>

<pre>
        <?php
        
        print_r($feeds);
        ?>
</pre> <?php
        foreach ($feeds as $name => $feed) {
            ?>

            <h2><?php echo $name; ?></h2>
            <p>
                Url:<a href="<?php echo $feed->url; ?>">
                <?php echo $feed->url; ?></a><br />
                Filter: <?php echo $feed->filter;  ?> <br/>
                Folder: <?php echo $feed->folder; ?><br/>
                Using Magnet:  <?php echo ($feed->usemagnet) ?  'Yes' : 'No'; ?> <br/>
                Last Check on: <?php $checkdate = new DateTime($feed->checkdate);
                echo $checkdate->format(DATE_RFC822);?>
           <table border="0">
               <tr><th>Title</th><td></td><th>Adding Status</th><th>Added</th><th>Added On</th></tr>
                    <?php foreach ($feed->torrents as $guid => $torrent) { ?>
                        <tr>
                            <td>  <?php echo $torrent->title; ?>
                            
                            <td> 
                            <?php
                            echo '<a href="'.$torrent->link.'"><img src="icons/link.png" /></a>';
                            if (isset($torrent->magnet )) {
                                
                                echo '<a href="'.$torrent->magnet.'"><img src="images/icon-magnet.gif" /></a>'; 
                                
                            }?>     </td> <td>  <?php echo $torrent->results; ?>    </td><td>  <input type="checkbox" <?php echo ($torrent->added) ?  'checked="true"' : ''; ?> readonly="true"/>     </td><td>  <?php echo $torrent->datedadded; ?>    </td>
                            
                 
    
                        </tr>
                    <?php } ?>
                </table>


              
            </p>
            <?php
        }
        ?>





</body>
</html>
