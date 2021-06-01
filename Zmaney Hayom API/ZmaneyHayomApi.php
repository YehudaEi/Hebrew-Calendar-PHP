<?php

/**
 * Zmaney Hayom Calculator
 *
 * @package    Hebrew-Calendar-PHP
 * @copyright  Copyright (c) 2018-2020 Yehuda Eisenberg (https://YehudaE.net)
 * @author     Yehuda Eisenberg
 * @license    AGPL-3.0
 * @version    2.0
 * @link       https://github.com/YehudaEi/Hebrew-Calendar-PHP
 */

date_default_timezone_set("Asia/Jerusalem");

// Jerusalem lat & long
$lat    = 31.771959;
$long   = 35.217018;

$zenith = 90.5;

$sunrise    = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith);
$sunset     = date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith);
$zmanitMga  = ((($sunset + (72 * 60)) - ($sunrise - (72 * 60))) / 12) / 60;
$zmanitGra  = (($sunset - $sunrise) / 12) / 60;

function tsToTime($timestamp){
    return date("H:i", $timestamp);
}

$translate = array(
    "Zmanit (MGA)" => "שעה זמנית (מג\"א)",
    "Zmanit (GRA)" => "שעה זמנית (גר\"א)",
    "Date" => "תאריך",
    "Alot Hashahar" => "עלות השחר",
    "Talit And Tfilin" => "זמן טלית ותפילין",
    "Sunrise" => "זריחה",
    "End Kriat Shma (MGA)" => "סזק\"ש (מג\"א)",
    "End Tfilha (MGA)" => "סזת\"פ (מג\"א)",
    "End Kriat Shma (GRA)" => "סזק\"ש (גר\"א)",
    "End Tfilha (GRA)" => "סזת\"פ (גר\"א)",
    "Midnight" => "חצות",
    "Mincha Gdola" => "מנחה גדולה",
    "Plag Mincha" => "פלג המנחה",
    "Sunset" => "שקיעה",
    "Tzet HaCochavim" => "צאת הכוכבים"
);

$zmanim = array(
    "Zmanit (MGA)" => $zmanitMga,
    "Zmanit (GRA)" => $zmanitGra,
    "Date" => iconv ('WINDOWS-1255', 'UTF-8', jdtojewish(unixtojd(), true, CAL_JEWISH_ADD_GERESHAYIM)),
    "Alot Hashahar" =>          tsToTime($sunrise - (72 * 60)),
    "Talit And Tfilin" =>       tsToTime($sunrise - (45 * 60)),
    "Sunrise" =>                tsToTime($sunrise),
    "End Kriat Shma (MGA)" =>   tsToTime(($sunrise - (72 * 60)) + ($zmanitMga * (60 * 3))),
    "End Tfilha (MGA)" =>       tsToTime(($sunrise - (72 * 60)) + ($zmanitMga * (60 * 4))),
    "End Kriat Shma (GRA)" =>   tsToTime($sunrise + $zmanitGra * (60 * 3)),
    "End Tfilha (GRA)" =>       tsToTime($sunrise + $zmanitGra * (60 * 4)),
    "Midnight" =>               tsToTime($sunrise + $zmanitGra * (60 * 6)),
    "Mincha Gdola" =>           tsToTime($sunrise + $zmanitGra * (60 * 6.5)),
    "Plag Mincha" =>            tsToTime($sunset - $zmanitGra * (60 * 1.25)),
    "Sunset" =>                 tsToTime($sunset),
    "Tzet HaCochavim" =>        tsToTime($sunset + (20 * 60))
);

if(isset($_GET['output']) && $_GET['output'] == "html"){
    $html = "<table border='1' style='font-size: 35px;'><tr><th>שם</th><th>ערך</th></tr>";
    foreach ($zmanim as $name => $val){
        $html .= "<tr><td>" . $translate[$name] . "</td><td>" . $val . "</td></tr>";
    }
    $html .= "</table>";
    echo "<div align='right' dir='rtl'>" . $html . "</div>";
}
else{
    $res["en"] = $zmanim;
    foreach ($zmanim as $name => $val){
        $res["he"][$translate[$name]] = $val;
    }
    header("Contact-type: text/json");
    echo json_encode($res);
}
?>
