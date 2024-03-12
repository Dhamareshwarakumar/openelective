<?php

session_start();

// Check user login status
if (!isset($_SESSION['user'])) {
    echo json_encode(array("err" => "Unauthorized"));
    exit();
}

// import Database
require_once "../config/db.php";

// Retreiving list of all electives
$electives = "SELECT * FROM electives";
$electives_result = mysqli_query($connect, $electives);
$allowed_departments = array();

while ($row = mysqli_fetch_assoc($electives_result)) {
    // Extract Comma Seperated Not allowed barnches into an array
    $not_allowed = explode(',', $row['NotAllowedBranches']);
    // Check if current user department is in not allowed list, if not add to allowed list
    if (!in_array($_SESSION['user']['Department'], $not_allowed)) {
        $allowed_departments[$row['CourseCode']] = $row['Department'];
    }
}

echo json_encode($allowed_departments);
