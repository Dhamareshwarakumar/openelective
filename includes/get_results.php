<?php

// Excel file name for download 
$fileName = "Open-Elective-Results-" . date('Ymd') . ".xlsx";

// Headers for download 
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel");

$flag = false;
foreach ($data as $row) {
    if (!$flag) {
        // display column names as first row 
        echo implode("\t", array_keys($row)) . "\n";
        $flag = true;
    }
    // filter data 
    array_walk($row, 'filterData');
    echo implode("\t", array_values($row)) . "\n";
}

exit;
