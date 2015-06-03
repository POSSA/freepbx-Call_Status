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

// "Name Resolution" for extensions Added by JAT on 6/1/2015

define('COMPANY_PREFIX', '+13312139'); // Used for "Name Resolution" for exensions

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

foreach(explode("\n", $ccount['data']) as $line)
	if ((preg_match("/call/i", $line) || preg_match("/processed/i", $line)) && !preg_match("/processed/i", $line) ) 
	{
	{
		$pieces = explode("!", $line);
		$spaceLoc = strpos($line, ' ');
		if ($spaceLoc === false) {
			$callCount = $line;
		} else {
			$callCount = substr($line, 0, $spaceLoc);
		}
	}
	}
?>
<div style="padding:10px;">
<h2>Active Calls (<?php echo $callCount; ?>)</h2>
<?php
if ($callCount != "0") {
?>
<table width="100%">
<tr>
	  <th style="text-align:left;">Duration</th>
	  <th style="text-align:left;">From</th>
	  <th style="text-align:left;">To</th>
</tr>
<?php
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
	  
			  $extFrom = getExtensionName($pieces[7]);
			  $extTo = getExtensionName($to[7]);
			  $channels = trim(substr($to[12], 0, strpos($to[12], '-'))) . " - ". trim(substr($pieces[12], 0, strpos($pieces[12], '-')));
			  
			  echo "<tr title='Channels: $channels'>";
			  echo "<td style='border-top:1px solid silver;' valign=top>" . gmdate("H:i:s", $pieces[11]) . "</td>";
			  echo "<td style='border-top:1px solid silver;' valign=top>" . $extFrom . "</td>";
			  echo "<td style='border-top:1px solid silver;' valign=top>" . $extTo . "</td>";
			  echo "</tr>";
		  }
	  }
	  echo "</table>";
}
// print_r($to);   //debug

	
	function getExtensionName($fullNumber) {
	  global $astman;
		if ($astman->connected()) {
			if(strlen($fullNumber) == 4) {
				  $ext = $fullNumber;
			} elseif (substr($fullNumber, 0, 9) == COMPANY_PREFIX) {
				  $ext = substr($fullNumber, -4);
			} else {
				  if (substr($fullNumber, 0, 1) == '+') // +19998887777
						$fullNumber = substr($fullNumber, 1); // Drop the +
				  if (strlen($fullNumber) == 11) // 19998887777
						$fullNumber = substr($fullNumber, 1); // Drop the 1
				  if (strlen($fullNumber) == 10) // 9998887777
				  	  return '(' . substr($fullNumber, 0, 3) . ') ' . substr($fullNumber, 3, 3) . '-' . substr($fullNumber, -4);
				  else
				  	  return $fullNumber;
			}
			$extInfo = $astman->Command("sip show user $ext");
			$extData = $extInfo['data'];
			$cidStartLoc = strpos($extData, 'Callerid') + 16;
			$cidEndLoc = strpos($extData, '"', $cidStartLoc);
			$cid = substr($extData, $cidStartLoc, $cidEndLoc - $cidStartLoc);
			return $ext . ' - ' . $cid;
	    } else
			return $fullNumber;
	}
?>
</div>
