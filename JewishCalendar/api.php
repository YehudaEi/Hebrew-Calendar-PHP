<?php
session_name("JEWISH_CALENDAR_SESSION");
session_start();

$post = json_decode(file_get_contents("php://input"), true);
if(!isset($post) || empty($post)){
    echo '<script>messageAlert("המידע שהתקבל שגוי", "danger")</script>';
    die();
}

$time = strtotime($post['date'] ?? "now");
if(empty($time)){
    echo '<script>messageAlert("התאריך אינו תקין", "warning")</script>';
    die();
}

$name = $post['name'] ?? "";
if(!is_string($name)){
    echo '<script>messageAlert("יש לנו פה האקר 😉🙃", "info")</script>';
    die();
}
else if(mb_strlen($name) > 50){
    echo '<script>messageAlert("אורך השם הוא עד 50 תווים", "warning")</script>';
    die();
}

$night = $post['night'] ?? false;
if(!is_bool($night)){
    echo '<script>messageAlert("יש לנו פה האקר 😉🙃", "info")</script>';
    die();
}

$type = $post['type'] ?? false;
if($type !=  false && (!is_numeric($type) || $type < 0 || $type > 3)){
    echo '<script>messageAlert("יש לנו פה האקר 😉🙃", "info")</script>';
    die();
}

$hebDays = array('ראשון','שני','שלישי','רביעי','חמישי','שישי','שבת');

if($night){ $time += 86400; } //24*60*60

$_SESSION['JewishCalendarData'] = array(
    "time" => $time,
    "name" => $name,
    "type" => ($type - 1)
);

echo <<<HTML
<div class="d-flex justify-content-between" dir="rtl">
    <a class="m-0" href="export.php?mode=celander">ייצוא ל-Google Calendar</a>
    <a class="m-0" href="export.php?mode=ical">יצא ללוח שנה (.ics)</a>
    <a class="m-0" href="export.php?mode=excel">יצא ל-Excel</a>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">תאריך לועזי</th>
            <th scope="col">תאריך עברי</th>
            <th scope="col">יום בשבוע</th>
        </tr>
    </thead>
    <tbody>

HTML;

$currentHebDate = explode("/", jdtojewish(unixtojd($time)));
$nowHebDate = explode("/", jdtojewish(unixtojd()));

$tmpTime = jewishtojd($currentHebDate[0], $currentHebDate[1], $currentHebDate[2]);
$hebDate = iconv('WINDOWS-1255', 'UTF-8', jdtojewish($tmpTime, true, CAL_JEWISH_ADD_GERESHAYIM));
$date = date("d/m/Y", jdtounix($tmpTime));
$day = $hebDays[date("w", jdtounix($tmpTime))];
echo <<<HTML
        <tr class="table-info">
            <th scope="row">תאריך מקורי</th>
            <td>{$date}</td>
            <td>{$hebDate}</td>
            <td>{$day}</td>
        </tr>

HTML;

for($i = 0; $i < 20; $i++){
    $tmpTime = jewishtojd($currentHebDate[0], $currentHebDate[1], ($nowHebDate[2] + $i));
    $num = $i + 1;
    $hebDate = iconv('WINDOWS-1255', 'UTF-8', jdtojewish($tmpTime, true, CAL_JEWISH_ADD_GERESHAYIM));
    $gulDate = explode("/", jdtogregorian($tmpTime));
    $tmp = $gulDate[0];
    $gulDate[0] = $gulDate[1];
    $gulDate[1] = $tmp;
    $date = implode("/", $gulDate);
    $day = $hebDays[date("w", mktime(0, 0, 0, $gulDate[1], $gulDate[0], $gulDate[2]))];
    echo <<<HTML
        <tr>
            <th scope="row">{$num}</th>
            <td>{$date}</td>
            <td>{$hebDate}</td>
            <td>{$day}</td>
        </tr>

HTML;
}

echo <<<HTML
    </tbody>
</table>
HTML;
