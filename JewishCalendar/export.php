<?php
session_name("JEWISH_CALENDAR_SESSION");
session_start();

if(!isset($_SESSION['JewishCalendarData']) || !is_array($_SESSION['JewishCalendarData']) || !isset($_GET['mode']) || !is_string($_GET['mode'])|| empty($_GET['mode'])){
    header("Location: /JewishCalendar/");
    die();
}


$hebDays = array('ראשון','שני','שלישי','רביעי','חמישי','שישי','שבת');
$types = array("יום הולדת", "יום נישואין", "יארצייט");


$time = $_SESSION['JewishCalendarData']['time'];
$name = str_replace(["\"", ":", ",", "\n"], "_", $_SESSION['JewishCalendarData']['name']);
$type = $types[$_SESSION['JewishCalendarData']['type']];

$currentHebDate = explode("/", jdtojewish(unixtojd($time)));
$nowHebDate = explode("/", jdtojewish(unixtojd()));


if($_GET['mode'] == "ical"){
    $res = "BEGIN:VCALENDAR\nVERSION:2.0\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH";

    for($i = 0; $i < 20; $i++){
        $tmpTime = jewishtojd($currentHebDate[0], $currentHebDate[1], ($nowHebDate[2] + $i));
        $gulDate = explode("/", jdtogregorian($tmpTime));
        $tmp = $gulDate[0];
        $gulDate[0] = $gulDate[1];
        $gulDate[1] = $tmp;
        $date = implode("/", $gulDate);
        $res .= "\nBEGIN:VEVENT\nSUMMARY:".$type." ל".$name."\nSTATUS:CONFIRMED\nTRANSP:TRANSPARENT\nRRULE:INTERVAL=1;\nDTSTART:".$date."\nDTEND:".$date."\nEND:VEVENT";
    }

    $res .= "\nEND:VCALENDAR";
    header('Content-Type: application/plain');
    header('Content-Disposition: attachment;filename="תאריכים עבריים - '.$type." ל".$name.'.ics"');
    header('Cache-Control: max-age=0');
    
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    header ('Cache-Control: cache, must-revalidate');
    header ('Pragma: public');
    
    file_put_contents('php://output', $res);
}

if($_GET['mode'] == "celander"){
    $res = "BEGIN:VCALENDAR\nVERSION:2.0\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH";

    for($i = 0; $i < 20; $i++){
        $tmpTime = jewishtojd($currentHebDate[0], $currentHebDate[1], ($nowHebDate[2] + $i));
        $gulDate = explode("/", jdtogregorian($tmpTime));
        $tmp = $gulDate[0];
        $gulDate[0] = $gulDate[1];
        $gulDate[1] = $tmp;
        $date = implode("/", $gulDate);
        $res .= "\n\"".$type." ל".$name."\",".$date.",True";
    }

    header('Content-Type: application/plain');
    header('Content-Disposition: attachment;filename="תאריכים עבריים - '.$type." ל".$name.'.csv"');
    header('Cache-Control: max-age=0');
    
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    header ('Cache-Control: cache, must-revalidate');
    header ('Pragma: public');
    
    file_put_contents('php://output', $res);
}

if($_GET['mode'] == "celander"){

    foreach($data as $event){
        $event = explode("`",$event);
        if(strlen($event[0]) < 1) continue;
        $res .= "\"".$_SESSION['ExportType']." ל".$_SESSION['ExportName']."\",".$event[1].",True\n";
    }
    
    header('Content-Type: application/plain');
    header('Content-Disposition: attachment;filename="תאריכים עבריים - '.$_SESSION['ExportType']." ל".$_SESSION['ExportName'].'.csv"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    header ('Cache-Control: cache, must-revalidate');
    header ('Pragma: public');
    
    file_put_contents('php://output', $res);
    //header('Location: https://calendar.google.com/calendar/r/settings/export');
    exit;
}
elseif($_GET['mode'] == "excel"){
    require_once dirname(__FILE__) . "/PHPExcel/PHPExcel.php";
    
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("יהודה אייזנברג")
                                 ->setLastModifiedBy("יהודה אייזנברג")
                                 ->setTitle("תאריכים עבריים - ".$type." ל".$name)
                                 ->setSubject("תאריכים עבריים - ".$type." ל".$name)
                                 ->setDescription("תאריכים עבריים - ".$type." ל".$name)
                                 ->setKeywords("תאריכים עבריים - ".$type." ל".$name)
                                 ->setCategory("");
    
    $objPHPExcel->getActiveSheet()->setRightToLeft(true);

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', "תאריכים עבריים - ".$type." ל".$name)
                ->setCellValue('A3', "שנים מהיום")
                ->setCellValue('B3', "תאריך לועזי")
                ->setCellValue('C3', "תאריך עברי")
                ->setCellValue('D3', "יום בשבוע");

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

    $i = 4;

    for($j = 0; $j < 20; $j++){
        $tmpTime = jewishtojd($currentHebDate[0], $currentHebDate[1], ($nowHebDate[2] + $j));
        $num = $j + 1;
        $hebDate = iconv('WINDOWS-1255', 'UTF-8', jdtojewish($tmpTime, true, CAL_JEWISH_ADD_GERESHAYIM));
        $gulDate = explode("/", jdtogregorian($tmpTime));
        $tmp = $gulDate[0];
        $gulDate[0] = $gulDate[1];
        $gulDate[1] = $tmp;
        $date = implode("/", $gulDate);
        $day = $hebDays[date("w", mktime(0, 0, 0, $gulDate[1], $gulDate[0], $gulDate[2]))];

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $num)
                ->setCellValue('B'.$i, $date)
                ->setCellValue('C'.$i, $hebDate)
                ->setCellValue('D'.$i, $day);
        $i++;
    }

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, "קרדיט לאתר 'מחשבון תאריכים עבריים' - https://yehudae.net/JewishCalendar/");
    $objPHPExcel->getActiveSheet()->setTitle("תאריכים");
    
    $objPHPExcel->setActiveSheetIndex(0);
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="תאריכים עבריים - '.$type." ל".$name.'.xlsx"');
    header('Cache-Control: max-age=0');
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    header ('Cache-Control: cache, must-revalidate');
    header ('Pragma: public');
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
