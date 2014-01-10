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



// FreePBX bootstrap loader
if (!@include_once(getenv('FREEPBX_CONF') ? getenv('FREEPBX_CONF') : '/etc/freepbx.conf')) {
      include_once('/etc/asterisk/freepbx.conf');
}

// These lines modifed from original to use FreePBX $astman class
if($astman->connected()) { 
	$result = $astman->Command("core show channels concise");
	$ccount = $astman->Command("core show channels count");
}
//echo $result['data'];  //debug

$data = array();
echo "<table class='table table-striped table-bordered table-condensed'>";
echo "<tr class='heading';><td>call length</td><td>from</td><td>to</td><td>Channel(s)</td></tr>";

foreach(explode("\n", $result['data']) as $line)
	if (preg_match("/Up/i", $line) && preg_match("/!Dial!/i", $line)) 
{
	{
		/* summary of $pieces and $to array values
			$pieces[0] = channel
			$pieces[1] = context
			$pieces[2] = extension (which could be 's' on FreePBX system)
			$pieces[3] = priority
			$pieces[4] = state
			$pieces[5] = application
			$pieces[6] = data
			$pieces[7] = callerid number
			$pieces[8] = 
			$pieces[9] = 
			$pieces[10] = peer account
			$pieces[11] = duration
			$pieces[12] = bridged channel
		*/
		$pieces = explode("!", $line);

		// use regular expression to search thru concise channel data to determine the CID and trunk for the "to" end of the call
		// regex works for Asterisk 11 & 1.8
		$regex = "~".preg_quote($pieces[12],"~")."!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!~";
		preg_match($regex,$result['data'],$to);

		echo "<tr>";
		echo "<td class='large';>" . gmdate("H:i:s", $pieces[11]) . "</td>";
		echo "<td class='large';>" . $pieces[7] . "</td>";
		echo "<td class='large';>" . $to[7] . "</td>";
		echo "<td class='large';>" . $pieces[12] . "</br>". $to[12] ."</td>";
		echo "</tr>";
	}
}
echo "</table>";
// print_r($to);   //debug
	foreach(explode("\n", $ccount['data']) as $line)
	if ((preg_match("/call/i", $line) || preg_match("/processed/i", $line)) && !preg_match("/processed/i", $line) ) 
	{
	{
		$pieces = explode("!", $line);
		echo $line . "<br />";
	}
	}
?>
