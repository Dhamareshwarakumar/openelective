<?php

session_start();


// Checking if user is logged in or not
if (!isset($_SESSION["user"])) {
    echo json_encode(array("err" => "Unauthorized"));
    exit();
}



// Import Database
include "../config/db.php";


// Get Active Time
require_once './get_active_time.php';
if ($current_time < $event_start_time) {
    // Display Event is not started
    echo json_encode(array("err" => "Event No started"));
    exit();
} else if ($current_time > $event_end_time) {
    // Display Event is completed
    echo json_encode(array("err" => "Event Completed"));
    exit();
}


// Decoding Post request
$json = file_get_contents('php://input');
$data = json_decode($json);


// input validation
if (empty($data->CourseCode)) {
    echo json_encode(array("err" => "Course Code Should not be empty"));
    exit();
}


// Check if user is already registered
$check_user = "SELECT JNTUNO FROM results WHERE JNTUNO='" . $_SESSION['user']['JNTUNO'] . "'";
$check_user_result = mysqli_query($connect, $check_user);

if (mysqli_num_rows($check_user_result) > 0) {
    // User Already Registered
    echo json_encode(array("err" => "User Already Registered"));
    exit();
}




// TODO: CHECK Seats Avaialability

// Get Section wise Regitration limit
require_once './get_max_strength_allowed.php';


// Retreiving list of all electives
$electives = "SELECT * FROM electives WHERE CourseCode ='" . $data->CourseCode . "'";
// $electives = "SELECT * FROM electives";
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



if (sizeof($allowed_departments) != 1) {
    echo json_encode(array("err" => "Invalid Request"));
    exit();
}

// GET Seats Left
$seats_left = array();

foreach ($allowed_departments as $CourseCode => $department) {
    // GET Total students registered for a course
    $total_registrations = "SELECT COUNT(JNTUNO) as total_registrations FROM results WHERE CourseCode='" . $CourseCode . "'";
    $total_registrations_result = mysqli_query($connect, $total_registrations);
    while ($row = mysqli_fetch_assoc($total_registrations_result)) {
        $total_registrations = $row['total_registrations'];
    }

    // GET Max Registrations allowed for a course
    $max_registrations = "SELECT Strength, Subject FROM electives WHERE CourseCode='" . $CourseCode . "'";
    $max_registrations_result = mysqli_query($connect, $max_registrations);
    while ($row = mysqli_fetch_assoc($max_registrations_result)) {
        $max_registrations = $row['Strength'];
        $CourseName = $row['Subject'];
    }

    // GET Count of students registered for a particular Course from a particular section
    $registered_count = "SELECT COUNT(JNTUNO) as registered_count FROM results WHERE CourseCode='" . $CourseCode . "' AND JNTUNO IN (SELECT JNTUNO FROM students WHERE Department='" . $_SESSION['user']['Department'] . "' AND Section='" . $_SESSION['user']['Section'] . "')";
    $registered_count_result = mysqli_query($connect, $registered_count);
    while ($row = mysqli_fetch_assoc($registered_count_result)) {
        $registered_count = $row['registered_count'];
    }

    // Maximum strength allowed for a particular course from particular section
    $limit = $max_strength_allowed[$CourseCode];


    // Final Seats left ...
    $seats_left[$CourseCode] = array("seats_left" => ($limit - $registered_count), "CourseName" => $CourseName, "department" => $department);
    if ($total_registrations >= $max_registrations) {
        $seats_left[$CourseCode] = 0;
    }
}


if ($seats_left[$data->CourseCode]['seats_left'] <= 0) {
    echo json_encode(array("err" => "Seats Over Please... Please Select Other Subjects"));
    exit();
}


// Send the user to the database

$sql = "INSERT INTO results (JNTUNO, CourseCode, time) VALUES ('" . $_SESSION['user']['JNTUNO'] . "','" . $data->CourseCode . "','" . $current_time . "')";
if (mysqli_query($connect, $sql)) {
    echo json_encode(array("msg" => "Success"));
} else {
    echo json_encode(mysqli_error($connect));
}
