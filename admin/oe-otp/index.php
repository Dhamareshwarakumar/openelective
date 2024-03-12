<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./db.php";
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

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
                        Your Registration for GMRIT VI Semester Open Elective is Initiated. Below is the
                        OTP for login.
                    <h1 style='text-align: center; margin-top: 20px'>$otp</h1>
                    </p>
                    <br>
                    <p id='description'>The registrations will start from <b>27th November 2022, at 06:00 AM</b></p>
                </section>
            </div>
        </div>
    </body>
    
    </html>
    
    ";

    $mail = new PHPMailer();

    try {
        //Server settings
        // $mail->isSMTP();
        // $mail->SMTPDebug = false;
        // $mail->Host = 'gmritchapter.acm.org';
        // $mail->Port = 465; // TCP port to connect to
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        // $mail->SMTPAuth = true; // Enable SMTP authentication
        // $mail->Username = 'admin@gmritchapter.acm.org'; // SMTP username
        // $mail->Password = 'admin@gmrasc@123'; // SMTP password


        // $mail->setFrom('admin@gmritchapter.acm.org', 'ACM GMRIT');
        // $mail->addAddress($email, $name);
        // $mail->isHTML(true); // Set email format to HTML
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Mailer = 'smtp';
        $mail->Host       = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'acm@gmrit.edu.in';                     //SMTP username
        $mail->Password   = 'RamLeela@123';                               //SMTP password
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       =  587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        //Recipients
        $mail->setFrom('acm@gmrit.edu.in', 'ACM GMRIT');
        $mail->addAddress($email, $name);     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments


        //Content
        $mail->isHTML(true);
        $mail->Subject = $otp . " is OTP for Open Elective Registration";
        $mail->Body = $body;
        $mail->AltBody = 'This is the OTP for open elective registration ' . $otp;

        $mail->send();
    } catch (Exception $e) {
        require './db.php';
        $sql = "insert into otpStatus values($email,'not inserted')";
        $result = mysqli_query($conn, $sql);
    }
}




// ================ Checking for User in database ===================//
$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);
while ($row =  mysqli_fetch_assoc($result)) {
    // $stmt = mysqli_stmt_init($connect);
    // if (!mysqli_stmt_prepare($stmt, $sql)) {
    //     echo json_encode(array("err" => "SQL Error"));
    //     exit();
    // } else {
    //     mysqli_stmt_bind_param($stmt, "s", $data->jntu_no);
    //     mysqli_stmt_execute($stmt);
    //     $result = mysqli_stmt_get_result($stmt);

    //     // User found in database
    //     if ($row = mysqli_fetch_assoc($result)) {
    $email = $row['JNTUNO'] . "@gmrit.edu" . ".in";

    // TODO: Remove Old Values if exist any
    // Insert otp and expiration time into database

    // Send OTP via email
    $status = send_otp($email, $row['FirstName'] . " " . $row['LastName'], $row['Password']);
    echo $email . '<br>';
}