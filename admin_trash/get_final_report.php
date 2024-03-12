<?php
require_once '../config/db.php';

$subjects = $connect->query("SELECT CourseCode FROM electives");
$all_subjects = $subjects->num_rows;

// Prepare the Excel file content
$excelData = [];

for ($j = 0; $j < $all_subjects; $j++) {
    $current_Subject = $subjects->fetch_array(MYSQLI_NUM);
    $subject = $current_Subject[0];

    $student_details = $connect->query("SELECT t1.JNTUNO, t2.FirstName, t2.LastName, t2.Department, t2.Section, t1.CourseCode, t3.Subject, t1.time FROM results t1 INNER JOIN students t2 on t1.JNTUNO = t2.JNTUNO INNER JOIN electives t3 on t1.CourseCode = t3.CourseCode WHERE t1.CourseCode = '" . $subject . "' ORDER BY t2.Department, t2.Section, t1.JNTUNO;");
    $total_rows = $student_details->num_rows;
    $columnHeader = '';
    $columnHeader =  "JNTU No   " . "\t" . "First Name     " . "\t" . "Last Name    " . "\t" . "Department  " . "\t" . "Section     " . "\t" . "CourseCode     " . "\t" . "Elective Subject     " . "\t";
    $setData = "";

    for ($i = 0; $i < $total_rows; $i++) {
        $each_student_details = $student_details->fetch_array(MYSQLI_NUM);
        $each_student_data =  $each_student_details[0] . "\t" . $each_student_details[1] . "\t" . $each_student_details[2] . "\t" . $each_student_details[3] . "\t" . $each_student_details[4] . "\t" . $each_student_details[5] . "\t" . $each_student_details[6] . "\t" . "\n";
        $setData .= trim($each_student_data) . "\n";
    }

    $excelData[$subject] = ucwords($columnHeader) . "\n" . $setData . "\n";
}

// Set the headers for file download
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=Final_report_OE.zip");

$zip = new ZipArchive();
$zip->open('Final_report_OE.zip', ZipArchive::CREATE);

// Add each subject's Excel data as a separate file in the ZIP archive
foreach ($excelData as $subject => $data) {
    $filename = "Final_report_OE_$subject.xls";
    $zip->addFromString($filename, $data);
}

$zip->close();

// Send the ZIP archive to the browser
readfile('Final_report_OE.zip');

// Delete the ZIP archive after download
unlink('Final_report_OE.zip');
?>
