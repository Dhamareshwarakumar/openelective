<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_otp($email, $name, $otp)
{
    $body = "<!DOCTYPE html>
    <html lang='en'>
    
    <head>
        <meta charset='UTF-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Open Elective Registration</title>
    
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
    
            body {
                background-color: rgba(255, 248, 220, 0.3);
            }
    
            #main {
                background-color: #fff;
                max-width: 720px;
                margin: auto;
                border-left: 1px solid #ccc;
                border-right: 1px solid #ccc;
                border-bottom: 5px solid #013b8f;
            }
    
            .header {
                background-color: #013b8f;
                height: 70px;
                width: 100%;
                border-radius: 5px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
    
            .container {
                padding: 1.25rem;
                max-width: 100%;
            }
    
            #description {
                text-align: justify;
                margin-top: 1.5rem;
                line-height: 1.3rem;
            }
    
            #info {
                text-align: justify;
            }
    
            table {
                margin: auto;
                margin-top: 20px;
            }
    
            table,
            th,
            td {
                border-collapse: collapse;
                border: 1px solid black;
                padding: 10px;
            }
        </style>
    </head>
    
    <body>
        <div id='main'>
            <div class='header'>
                <p style='color: #fff; font-size: 1.2rem; text-align: center;'>Confirm Open Elective Registration</p>
            </div>
            <div class='container'>
                <section id='info'>
                    <p id='description'>
                        <!--FIXME: Variable-->
                        Hello $name,<br>
                        Your Registration for GMRIT V Semester Open Elective is Initiated. Below is the
                        OTP for signup.
                    <h1 style='text-align: center; margin-top: 20px'>$otp</h1>
                    </p>
                </section>
            </div>
        </div>
    </body>
    
    </html>
    
    
    
    <?php
    ";

    require '../config/PHPMailer/src/Exception.php';
    require '../config/PHPMailer/src/PHPMailer.php';
    require '../config/PHPMailer/src/SMTP.php';

    $mail = new PHPMailer();

    try {
        //Server settings
        $mail->isSMTP();
        $mail->SMTPDebug = false;
        $mail->Host = 'gmritchapter.acm.org';
        $mail->Port = 465; // TCP port to connect to
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'admin@gmritchapter.acm.org'; // SMTP username
        $mail->Password = 'admin@gmrasc@123'; // SMTP password


        $mail->setFrom('admin@gmritchapter.acm.org', 'ACM GMRIT');
        $mail->addAddress($email, $name);
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $otp . " is OTP for Open Elective Registration";
        $mail->Body = $body;
        $mail->AltBody = 'This is the OTP for open elective registration ' . $otp;

        $mail->send();
        return 'success';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Function to generate OTP
function generateNumericOTP($n)
{

    // Take a generator string which consist of
    // all numeric digits
    $generator = "1357902468";

    // Iterate for n-times and pick a single character
    // from generator and append it to $result

    // Login for generating a random character from generator
    //	 ---generate a random number
    //	 ---take modulus of same with length of generator (say i)
    //	 ---append the character at place (i) from generator to result

    $result = "";

    for ($i = 1; $i <= $n; $i++) {
        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }

    // Return result
    return $result;
}





// Decoding Post request
$json = file_get_contents('php://input');
$data = json_decode($json);

// PHP Validations
// Checking For the Empty Values
if (empty($data->jntu_no)) {
    echo json_encode(array("jntu_no" => "JNTU Number should not be empty"));
    exit();
}

// import database connection
require_once "../config/db.php";


// ================ Checking for User in database ===================//
$sql = "SELECT FirstName, LastName, JNTUNO FROM students WHERE JNTUNO=?";

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
        $email = $row['JNTUNO'] . "@gmrit.edu" . ".in";

        // Create OTP and expiration time
        $otp = generateNumericOTP(4);
        $expires = date('U') + 300;      // Expires in 5 minutes

        // TODO: Remove Old Values if exist any
        // Insert otp and expiration time into database
        $sql = "INSERT INTO otp(otp, email, expires) VALUES('" . $otp . "','" . $email . "', '" . $expires . "')";
        if (!mysqli_query($connect, $sql)) {
            echo json_encode(array("err" => "Error: " . $sql . "<br>" . mysqli_error($connect)));
            exit();
        }

        // Send OTP via email
        $status = send_otp($email, $row['FirstName'] . " " . $row['LastName'], $otp);
        echo json_encode(array("msg" => $status));
        exit();
    } else {
        // User Not Found in Database
        echo json_encode(array("jntu_no" => "User Does not exist"));
        exit();
    }
}
