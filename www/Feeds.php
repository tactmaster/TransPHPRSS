<?php
require_once(dirname(__FILE__) . '/includes.php');

$feeds = loadFeeds($feedsfile);
$rpc = new TransmissionRPC($settings->url, $settings->username, $settings->password);
?>


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="style.css" type="text/css" />
        <title></title>
    </head>
    <body>
        <h1>Transmission</h1>
        <?php
        try
        {
        $result = $rpc->sstats();
        //  print "GET SESSION STATS... [{$]\n";
        ?>


        <?php
        if ($result->result == "success") {
            print "<p>Connection Successful</p>";
            $torrentresults = $rpc->get(array(), array("id", "name", "status", "doneDate", "haveValid", "totalSize", "hashString", "percentDone"));
            $torrents = $torrentresults->arguments->torrents;
            ?>
            <pre>
                <?php print_r($torrents); ?>
            </pre>
            <?php
        } else {
            print "<p>Connection Failed</p>";
        }
        } catch (Exception $e)
        
        {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        foreach ($feeds as $name => $feed) {
            ?>

            <h2><?php echo $name; ?></h2>
            <p>
                Url:<a href="<?php echo $feed->url; ?>">
    <?php echo $feed->url; ?></a><br />
                Filter: <?php echo $feed->filter; ?> <br/>
                Folder: <?php echo $feed->folder; ?><br/>
                Using Magnet:  <?php echo ($feed->usemagnet) ? 'Yes' : 'No'; ?> <br/>
                Last Check on: <?php $checkdate = new DateTime($feed->checkdate);
                echo $checkdate->format($dateformat); ?>
            <table border="0">
                <tr><th>Title</th><td></td><th>Adding Status</th><th>Added</th><th>Added On</th><th>Done</th></tr>
                <tbody>                  
    <?php foreach ($feed->torrents as $guid => $torrent) { ?>
                        <tr>
                            <td>  <?php echo $torrent->title; ?>

                            <td> 
        <?php
        echo '<a href="' . $torrent->link . '"><img src="icons/link.png" /></a>';
        if (isset($torrent->magnet)) {

            echo '<a href="' . $torrent->magnet . '"><img src="images/icon-magnet.gif" /></a>';
        }
        ?>     </td> <td>  <?php echo $torrent->results; ?>    </td>
                            <td>  <img src="<?php echo ($torrent->added) ? 'icons/accept.png' : 'icons/error.png'; ?>"/>     </td>
                            <td> 
                                 <?php
$date = new DateTime($torrent->datedadded);
                echo $date->format($dateformat);

; ?>    </td>
                            <td>  <?php $transtorrent = findTorrents($torrents,$torrent,$rpc);
                            if (!is_null($transtorrent))
                            {
                                if (isset($transtorrent->percentDone))
                                {
                                print ($transtorrent->percentDone*100)."%";
                                }
                                else  { print "0%";}
                            }
                                    ?>    </td>


                        </tr>
    <?php } ?>
                </tbody>
            </table>



        </p>
    <?php
}
?>





</body>
</html>
