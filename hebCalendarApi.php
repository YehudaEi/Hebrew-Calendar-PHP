<?php
date_default_timezone_set("Asia/Jerusalem");

$lat = 32.08707;
$long = 34.88747;

$sunrise    = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP);//, $lat, $long) ;
$sunset     = date_sunset(time(), SUNFUNCS_RET_TIMESTAMP);//, $lat, $long) ;
$zmanit     = (($sunset - $sunrise)/12)/60;

$res = array();
$res['city']            = "Jerusalem";
$res['alot_hashahar']   = date("H:i",($sunrise-72*60));
$res['talit_tfilin']    = date("H:i",($sunrise-45*60));
$res['zriha']           = date("H:i", $sunrise);
$res['sof_kriaat_shma'] = date("H:i",($sunrise+$zmanit*60*3));
$res['sof_zman_tfila']  = date("H:i",($sunrise+$zmanit*60*4));
$res['hatzot_hayom']    = date("H:i",($sunrise+$zmanit*60*6));
$res['minha_gdola']     = date("H:i",($sunrise+$zmanit*60*6.5));
$res['plag_haminha']    = date("H:i",($sunset-$zmanit*60*1.25));
$res['shkiaa']          = date("H:i", $sunset);
$res['tzet_hakocavim']  = date("H:i", $sunset+20*60);

header("Contact-type: text/json");
echo json_encode($res);
exit;
?>
