<?php
session_start();

// Decoding Post request
$json = file_get_contents('php://input');
$data = json_decode($json);

$errors = array();

// PHP Validations
// Checking For the Empty Values
if (empty($data->jntu_no)) {
    $errors['jntu_no'] = "JNTU Number should not be empty";
} else if (strlen($data->jntu_no) != 10) {
    $errors['jntu_no'] = "Invalid JNTU Number";
}


// Checking For the Empty Values
if (empty($data->password)) {
    $errors['password'] = "Password should not be empty";
}

// send validation errors if any
if (sizeof($errors) != 0) {
    echo json_encode($errors);
    exit();
}

// import database connection
require_once "../config/db.php";

$sql = "SELECT * FROM students WHERE JNTUNO=?";

$stmt = mysqli_stmt_init($connect);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(array("err" => "SQL Error"));
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $data->jntu_no);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // User found in database
    if ($row = mysqli_fetch_assoc($result)) {
        // Check password
        if ($row['Password'] != $data->password) {
            echo json_encode(array("password" => "Invalid Password. Please enter correct password"));
            exit();
        }
        // Passwords Matched, Create Session
        $_SESSION['user'] = $row;
        echo json_encode(array("msg" => "success"));
        exit();
    } else {
        // User Not Found in Database
        echo json_encode(array("jntu_no" => "User Does not exist"));
        exit();
    }
}
