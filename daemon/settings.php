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
require_once(dirname(__FILE__) . '/feeds.php');

function loadSettings($file) {
    if (file_exists($file)) {
        $filedata = file_get_contents($file);
        $json = json_decode($filedata, true);
       

        foreach ($json as $akey => $aval) {

            if ($akey == "feeds") {
                $feeds = array();
                foreach ($aval as $akey2 => $aval2) {
                    $feed = new Feed();
                    foreach ($aval2 as $akey3 => $aval3) {
                        $feed->{$akey3} = $aval3;
                    }
                    $feeds[$akey2] = $feed;
                }

                $settings->feeds = $feeds;
            } else {
                $settings->{$akey} = $aval;
            }
        }
        return $settings;
    } else {
        return array();
    }
}

class settings {

    public $username = "admin";
    public $password = "password1";
    public $repeat = 60;
    public $debug = true;
   
    public $daemon = false;
    public $defaultsave = "/media/BitTorrent/";
    public $url = "http://jeffmund:8181/transmission/rpc";
    public $feeds;

  //  function __construct() {
   //     $this->feeds = array(
   //         array('name' => 'Mininova', 'url' => "www.mininova.org/rss.xml?sub=675", 'filter' => '', 'folder' => 'bigbang', 'usemagnet' => true, 'startpaused' => true),
    //    );
   // }

}

?>
