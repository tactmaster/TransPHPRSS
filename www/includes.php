<?php
error_reporting(E_ALL);
require_once(dirname(__FILE__) . '/../daemon/commands.php');
require_once(dirname(__FILE__) . '/../daemon/php-transmission-class/class/TransmissionRPC.class.php');
require_once(dirname(__FILE__) . '/../daemon/settings.php');
require_once(dirname(__FILE__) . '/../daemon/feeds.php');
$settingsdir = dirname(__FILE__) ."/../settings/";
$settingsfile = $settingsdir."setting.json";
$feedsfile = $settingsdir."feeds.json";
$dateformat = "H:i:s d/m/Y";
if ($feedsfile)
{
$settings = loadSettings($settingsfile);
}
else
{
    $settings = saveSettings($settingsfile, new settings());
}


function findTorrents($torrent,$rpc)
{
    if (isset($torrent->hashString))
    {
        $hash = $torrent->hashString;
     } 
    
    $rpc->get($hash, array("id", "name", "status", "doneDate", "haveValid", "totalSize", "hashString", "percentDone"));
    foreach ($torrents as $torrent)
    {
        if ($torrent->hashString == $hash)
        {
            return $torrent;
            
        } 
    }
    return null; 
}
?>
