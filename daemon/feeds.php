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
function loadFeeds($file) {
    if (file_exists($file)) {
        $filedata = file_get_contents($file);
        $json = json_decode($filedata, true);
        $feeds = array();


        foreach ($json as $akey => $aval) {
            $feed = new Feed();
            foreach ($aval as $akey2 => $aval2) {

                if ($akey2 == "torrents") {
                    $torrents = array();
                    foreach ($aval2 as $akey3 => $aval3) {

                        $torrent = new FeedTorrent();
                        foreach ($aval3 as $akey4 => $aval4) {
                            $torrent->{$akey4} = $aval4;
                        }
                        $torrents[$akey3] = $torrent;
                    }
                    $feed->{$akey2} = $torrents;
                } else {
                    $feed->{$akey2} = $aval2;
                }
            }
            $feeds[$akey] = $feed;
        }
        return $feeds;
    } else {
        return array();
    }
}

class FeedTorrent {

    public $title;
    public $guid;
    public $link;
    public $added = 0;
    public $magnet;
    public $results;
    public $hashstring;
    public $datedadded;
    
   

}

class Feed {

    public $url;
    public $folder;
    public $torrents;
    public $usemagnet;
    public $startpaused;
    public $filter;
    public $checkdate;

}

?>
