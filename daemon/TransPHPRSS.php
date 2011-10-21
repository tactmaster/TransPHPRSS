#!/usr/bin/php
<?php
/*
  This file is part of TransPHPRSS.

  TransPHPRSS is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Foobar is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Foobar.  If not, see <http://www.gnu.org/licenses/>.

 */
error_reporting(E_ALL & E_STRICT);

require_once(dirname(__FILE__) . '/commands.php');
require_once(dirname(__FILE__) . '/settings.php');
require_once(dirname(__FILE__) . '/feeds.php');
// Include RPC class
require_once( dirname(__FILE__) . '/php-transmission-class/class/TransmissionRPC.class.php' );
$settingsdir = dirname(__FILE__) . "/../settings/";

$settingsfile = $settingsdir . "setting.json";
$feedsfile = $settingsdir . "feeds.json";

// echo "Loading Settings\n";
//$settings = new Settings();
$settings = loadSettings($settingsfile);
//saving the setting effectively upgreades the settings.
saveSettings($settingsfile, $settings);

if ($settings->daemon) {
    require_once "System/Daemon.php";
    $appName = "TransPHPRSS";
    $myemail = "edmundwatson@gmail.com";
//
//// Bare minimum setup
    System_Daemon::setOption("appName", $appName);
    System_Daemon::setOption("authorEmail", $myemail);

    System_Daemon::start();
    System_Daemon::log(System_Daemon::LOG_INFO, "Daemon: '" .
            System_Daemon::getOption("appName") .
            "' spawned! This will be written to " .
            System_Daemon::getOption("logLocation"));
}

do {
    loginfo("Starting RSS Check", $settings);

    $settings = new Settings();
    $settings = loadSettings($settingsfile, $settings);
    saveSettings($settingsfile, $settings);

    $feeds = loadFeeds($feedsfile);
    saveSettings($feedsfile, $feeds);

    foreach ($settings->feeds as $name => $feed) {
        loginfo("Checking feed: " . $name, $settings);
        //if (!isset($feed->startpaused)) {
        //  $feed['startpaused'] = false;
        // $settings->feeds["name"]["startpaused"] = false;
        //}
        //   if (!isset($feed['usemagnet'])) {
        //  $feed['usemagnet'] = false;
        // $settings->feeds["name"]["usemagnet"] = false;
        //}
        //if (!isset($feed['folder'])) {
        //   $feed['folder'] = "";
        //   $settings->feeds["name"]["folder"] = "";
        //}
        if (!isset($feeds[$name])) {
            $feeds[$name] = new Feed();
        }
        //TO DO Better copy
        $feeds[$name]->url = $feed->url;
        $feeds[$name]->folder = $feed->folder;
        $feeds[$name]->filter = $feed->filter;
        $feeds[$name]->usemagnet = $feed->usemagnet;
        $feeds[$name]->startpaused = $feed->startpaused;
        if (!isset($feeds[$name]->torrents)) {
            $feeds[$name]->torrents = array();
        }

        $reader = new XMLReader();
        try {
            if ($reader->open($feed->url)) {
$initem = false;
                while ($reader->read()) {
                    switch ($reader->nodeType) {
                        case (XMLREADER::ELEMENT):
                            if ($reader->localName == "item") {
                                $torrent = new FeedTorrent();
                                $todownload = false;
                                $initem = true;
                                //  echo read_string($reader);
                            } elseif ($initem) {
                                if ($reader->localName == "title") {
                                    $torrent->title = read_string($reader);
                                    if (isset($feed->filter)) {
                                       if (preg_match('/' . $feed->filter . '/i', $torrent->title)) {
                                            $todownload = true;
                                        }
                                    } else {
                                        $todownload = true;
                                    }
                                     
                                } elseif ($reader->localName == "link") {
                                    //print_r($reader);
                                    // if not set by enclourse first. 
                                    // 
                                    // mini mova has a html link but the torrent file is in the enclosoure.
                                    if (!isset($torrent->link)) {
                                        $torrent->link = read_string($reader);
                                    }
                                    // echo read_string($reader);
                                } elseif ($reader->localName == "guid") {
                                    $torrent->guid = read_string($reader);
                                    //  echo read_string($reader);
                                } elseif ($reader->localName == "enclosure") {
                                    $torrent->link = $reader->getAttribute("url");
                                    //  echo read_string($reader);
                                } elseif ($reader->localName == "magnetURI") {
                                    $torrent->magnet = read_string($reader);
                                    //  echo read_string($reader);
                                };
                            }
                            break;
                        case (XMLREADER::END_ELEMENT):
                            if ($reader->localName == "item") {
                                $initem = false;
                                if ($todownload) {
                                    if (!isset($torrent->guid)) {
                                        $torrent->guid = $torrent->title;
                                    }
                                    if (isset($feeds[$name]->torrents[$torrent->guid])) {
                                        $torrent->added = $feeds[$name]->torrents[$torrent->guid]->added;
                                        $torrent->hashstring = $feeds[$name]->torrents[$torrent->guid]->hashstring;
                                        $torrent->results = $feeds[$name]->torrents[$torrent->guid]->results;
                                        $torrent->datedadded = $feeds[$name]->torrents[$torrent->guid]->datedadded;
                                    } else {
                                        $time = new DateTime("now");
                                        $torrent->datedadded = $time->format(DATE_W3C);
                                    }
                                    $feeds[$name]->torrents[$torrent->guid] = $torrent;
                                }
                            };
                            break;
                    }
                }
            } else {
                logwarning(' XMLReader error ' . $feed['url'], $settings);
            }
        } catch (Exception $e) {
            logerror(' XMLReader error ' . $e->getMessage(), $settings);
        }
        $time = new DateTime("now");
        $feeds[$name]->checkdate = $time->format(DATE_W3C);
    }

    //print_r($feedouts);
    // print(indent(json_encode($feedouts)));
    saveSettings($feedsfile, $feeds);
    saveSettings($settingsfile, $settings);

    //  print "Starting RPC " . $settings->url . "\n";
// create new transmission communication class
    try {
        $rpc = new TransmissionRPC($settings->url, $settings->username, $settings->password);
        $rpc->debug = false;
        //   $result = $rpc->sstats();
        //  print "GET SESSION STATS... [{$result->result}]\n";
// Set authentication when needed
        //   $rpc->username = $settings->username;
        //   $rpc->password = $settings->password;
        //  print "Starting Add RPC \n";
//// Loop through filtered results, add torrents and set download path to $series_folder/$show (e.g: /tmp/Futurama);
        foreach ($feeds as $name => $feed) {
            foreach ($feed->torrents as $guid => $torrent) {

                $target = $settings->defaultsave . "/" . $feed->folder;

                if ($torrent->added) {

                    // print "Not Adding: {$torrent->title}..{$torrent->link} \n";
                } else {




                    try {
                        $optionsarray = array('paused' => $feed->startpaused);
                        if ($feed->usemagnet) {
                            //  print "Adding with magnet " . $torrent->magnet . "\n";
                            $result = $rpc->add((string) $torrent->magnet, $target, $optionsarray); // Magic happens here :)
                        } else {
                            $result = $rpc->add((string) $torrent->link, $target, $optionsarray);
                        }
                        // print_r($result);
                        if ($result->result == "duplicate torrent") {
                            
                            $torrent->added = true;
                        } elseif ($result->result == "success") {
                            $torrent->hashstring = $result->arguments->torrent_added->hashString;
                            $torrent->added = true;
                            loginfo("Added: {$torrent->title}", $settings);
                        } else {

                            logwarning("Failed to add : {$torrent->title}" . ":" . $result->result . " Using Magnet:" . $feed->usemagnet, $settings);
                        }
                        //print "[{$result->result}]";
                        $torrent->results = $result->result;
                        // print "\n";
                    } catch (Exception $e) {
                        logerror(' Caught exception: ' . $e->getMessage(), $settings);
                    }
                }
            }
        }
    } catch (TransmissionRPCException $e) {

        loginfo("Failed to connect to TranRPC " . $e->getMessage(), $settings);
    }
    saveSettings($feedsfile, $feeds);
    loginfo("Finished RSS check", $settings);


    if ($settings->daemon)
        sleep(60 * $settings->repeat);
} while ($settings->daemon)
?>



