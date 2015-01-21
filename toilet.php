<head>
<meta http-equiv="refresh" content="30">
</head>

<?php

require_once 'conf/config.php';

date_default_timezone_set('UTC'); 

// you can add anoother curl options too
// see here - http://php.net/manual/en/function.curl-setopt.php
function do_login($url,$username,$password) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);             // Use POST method
  curl_setopt($ch, CURLOPT_POSTFIELDS, "username=".$username."&password=".$password);  // Define POST data values
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

$login_info = json_decode(do_login('myhome.britishgas.co.uk/v5/login',$username,$password), true);
$myHubId = $login_info['hubIds'][0];
//at this point we should really check there are not multiple hubs - but what ya gonna do anyway ? - lets take Hub[0]
//$headers = array("ApiSession: {$login_info['ApiSession']}");

  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, "myhome.britishgas.co.uk/v5/users/{$username}/widgets/alarm/overview");
  curl_setopt($ch, CURLOPT_COOKIE, "ApiSession={$login_info['ApiSession']}");  
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);

$alarm = json_decode($data, true);
foreach ($alarm['otherDevices'] as &$sensor) {
//  $state = $sensor['state'];
//    if (strcmp($state, "CLOSED") == 0) {
//      $state = "ENGAGED";
//    } else {
//      $state = "AVAILABLE";
//    }
  $toilets[$sensor['name']] = $sensor['state'];
}

?>

<table width=100%><tr>
<td><center><?php if (strcmp($toilets['Disabled'], "CLOSED") == 0) { ?><font size="10" color="red"><img src="img/disabled-busy.png"><br>BUSY<?php } else { ?><font size="10" color="green"><img src="img/disabled.png"><br>FREE <?php } ?> </center></td>
<td><center><?php if (strcmp($toilets['Gents1'], "CLOSED") == 0) { ?><font size="10" color="red"><img src="img/gents-busy.jpg"><br>BUSY<?php } else { ?><font size="10" color="green"><img src="img/gents.jpg"><br>FREE <?php } ?> </center></td>
<td><center><?php if (strcmp($toilets['Gents2'], "CLOSED") == 0) { ?><font size="10" color="red"><img src="img/gents-busy.jpg"><br>BUSY<?php } else { ?><font size="10" color="green"><img src="img/gents.jpg"><br>FREE <?php } ?> </center></td>
<td><center><?php if (strcmp($toilets['Ladies1'], "CLOSED") == 0) { ?><font size="10" color="red"><img src="img/ladies-busy.jpg"><br>BUSY<?php } else { ?><font size="10" color="green"><img src="img/ladies.jpg"><br>FREE <?php } ?> </center></td>
<td><center><?php if (strcmp($toilets['Ladies2'], "CLOSED") == 0) { ?><font size="10" color="red"><img src="img/ladies-busy.jpg"><br>BUSY<?php } else { ?><font size="10" color="green"><img src="img/ladies.jpg"><br>FREE <?php } ?> </center></td>
</tr></table>
