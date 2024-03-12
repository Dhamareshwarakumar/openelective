<?php

$subject = '20ME002';
require_once '../config/db.php';


$student_details = $connect->query("SELECT t1.JNTUNO, t2.FirstName, t2.LastName, t2.Department, t2.Section, t1.CourseCode, t3.Subject, t1.time FROM results t1 INNER JOIN students t2 on t1.JNTUNO = t2.JNTUNO INNER JOIN electives t3 on t1.CourseCode = t3.CourseCode WHERE t1.CourseCode = '" . $subject . "' ORDER BY t2.Department, t2.Section, t1.JNTUNO;");
$total_rows = $student_details->num_rows;
$columnHeader = '';
$columnHeader =  "JNTU No   " . "\t" . "First Name     " . "\t" . "Last Name    " . "\t" . "Department  " . "\t" . "Section     " . "\t" . "CourseCode     " . "\t" . "Elective Subject     " . "\t" . "Time   " . "\t";
$setData = "";
for ($i = 0; $i < $total_rows; $i++) {
    $each_student_details = $student_details->fetch_array(MYSQLI_NUM);
    $each_student_data =  $each_student_details[0] . "\t" . $each_student_details[1] . "\t" . $each_student_details[2] . "\t" . $each_student_details[3] . "\t" . $each_student_details[4] . "\t" . $each_student_details[5] . "\t" . $each_student_details[6] . "\t" . $each_student_details[7] . "\t" . "\n";
    $setData .= trim($each_student_data) . "\n";
}


$filename = "Final_report_" . $subject;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($columnHeader) . "\n" . $setData . "\n";
