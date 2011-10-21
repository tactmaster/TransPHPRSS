<?php
error_reporting(E_ALL);
require_once(dirname(__FILE__) . '/../daemon/commands.php');
require_once(dirname(__FILE__) . '/../daemon/settings.php');
require_once(dirname(__FILE__) . '/../daemon/feeds.php');
$settingsdir = dirname(__FILE__) ."/../settings/";
$settingsfile = $settingsdir."setting.json";
$feedsfile = $settingsdir."feeds.json";
if ($feedsfile)
{
$settings = loadSettings($settingsfile);
}
else
{
    $settings = saveSettings($settingsfile, new settings());
}
?>
