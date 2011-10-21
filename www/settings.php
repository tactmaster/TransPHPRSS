<?php
require_once(dirname(__FILE__) . '/includes.php');

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
         <h1>Settings</h1>
          <form>
              <table>
                  <tr><td> <label>Username: </label></td><td><input type="text" name="username" value="<?php print $settings->username; ?>" /></td><tr>
           <tr><td>   <label>Password: </label></td><td><input type="text" name="password" value="<?php print $settings->password; ?>" /></td><tr>       
           <tr><td>   <label>Refresh time: </label></td><td><input type="number" name="repeat" value="<?php print $settings->repeat; ?>" /></td><tr>        
       
           <tr><td>   <label>Base Save Location: </label></td><td><input type="text" name="defaultsave" value="<?php print $settings->defaultsave; ?>" /></td><tr>
           <tr><td>   <label>Transmission RPC URL </label></td><td><input type="url" name="url" size="50" value="<?php print $settings->url; ?>" /></td><tr>  
            </table>
            <input type="submit" value="Save Settings" />
        </form>
        
         <form>
             <h1>Feeds</h1>
        <table>
               <tr><th>Name</th><th></th><th>URL</th><th>Use magnet</th><th>Start paused</th><th>Folder</th><th>Filter</th></tr>
                <tr><td>Frendly Name for your Feed</td><td></td><td></td><td>Use instead of downloading torrent file</td><td></td><td>relative to base location</td><td>Case insensivity filter on title</td></tr>
           <?php
           foreach ($settings->feeds as $name => $feed) {
               
            ?>
<tr>
  
    <td><?php echo $name; ?></td>
    <td><img src="icons\textfield_rename.png" /></td>
          <td><input type="url" name="url" size="50" value="<?php print $feed->url; ?>" /></td>   
       <td><input type="checkbox" <?php echo ($feed->usemagnet) ?  'checked="true"' : ''; ?> /> </td>      
       <td><input type="checkbox" <?php echo ($feed->startpaused) ?  'checked="true"' : ''; ?> /> </td>             
 <td><input type="text" name="username" value="<?php echo $feed->folder; ?>" /></td>     
  <td><input type="text" name="username" value="<?php echo $feed->filter; ?>" /></td>
    <td><img alt="Delete" src="icons\feed_delete.png" /></td>
</tr>     
        
            <?php } ?>
<tr>
    <td>    </td>
 <td></td>
          <td><input type="url" name="url" size="50" value="" /></td>   
       <td><input type="checkbox"  /> </td>      
       <td><input type="checkbox"  /> </td>             
 <td><input type="text" name="username" value="" /></td>     
  <td><input type="text" name="username" value="" /></td>
    <td><img alt="Add" src="icons\feed_add.png" /></td>
</tr>  
             </table>
             <input type="submit" value="Save Feeds" />
        </form>
    </body>
</html>
