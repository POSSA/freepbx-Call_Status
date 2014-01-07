<?php
/*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** 
// These lines are part of the original code
// removed for FreePBX compatibility

require_once('./phpagi/phpagi-asmanager.php');

$asm = new AGI_AsteriskManager();
 if($asm->connect())
 { 
  $result = $asm->Command("core show channels concise");
  $ccount = $asm->Command("core show channels count");
 }
$asm->disconnect();
 *** *** *** *** *** *** *** *** *** *** *** *** *** *** ***/



// FreePBX bootstrap loaded added
if (!@include_once(getenv('FREEPBX_CONF') ? getenv('FREEPBX_CONF') : '/etc/freepbx.conf')) {
      include_once('/etc/asterisk/freepbx.conf');
}

// These lines modifed from original to use FreePBX $astman class
if($astman->connected()) { 
	$result = $astman->Command("core show channels concise");
	$ccount = $astman->Command("core show channels count");
}


      $data = array();
      echo "<table class='table table-striped table-bordered table-condensed'>";
      echo "<tr class='heading';><td>call length</td><td>from</td><td>to</td><td>trunk</td></tr>";
      foreach(explode("\n", $result['data']) as $line)
      if (preg_match("/Up/i", $line) && preg_match("/!Dial!/i", $line)) {
      {
          $pieces = explode("!", $line);
          echo "<tr>";
          echo "<td class='large';>" . gmdate("H:i:s", $pieces[11]) . "</td>";
          echo "<td class='large';>" . $pieces[7] . "</td>";
          echo "<td class='large';>" . $pieces[2] . "</td>";
          echo "<td class='large';>" . $pieces[12] . "</td>";
          echo "</tr>";
      }
      }
      echo "</table>";

      foreach(explode("\n", $ccount['data']) as $line)
      if (preg_match("/call/i", $line) || preg_match("/processed/i", $line)) {
      {
          $pieces = explode("!", $line);
          echo $line . "<br />";
      }
      }
?>
