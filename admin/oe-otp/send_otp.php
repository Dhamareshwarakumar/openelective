<?php
$json = file_get_contents('php://input');
$data = json_decode($json);

$name = $data->name;
$email = $data->email;
$otp = $data->password;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\src\SMTP;

// require "./db.php";
// require './PHPMailer/src/Exception.php';
// require './PHPMailer/src/PHPMailer.php';
// require './PHPMailer/src/SMTP.php';

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
                        Your Registration for GMRIT VI Semester Open Elective is Initiated. Below is the OTP for login.
                    <h1 style='text-align: center; margin-top: 20px'>$otp</h1>
                    </p>
                    <br>
                    <p id='description'>Registration Link : <a href='https://gmritchapter.acm.org/openelective'><b>CLICK HERE</b></a></p>
                    <p id='description'>Stream : <b>6th Sem</b></p>
                    <p id='description'>Registrations will held on <b>13th January, at 08:00 PM</b></p>
                </section>
            </div>

        <table>
            <thead>
                <tr>
                    <th colspan='4'>&nbsp;&nbsp;&nbsp;<br> <br>&nbsp;&nbsp;&nbsp;<br><b>
                            <center>OPEN ELECTIVE -II<center><b>&nbsp;&nbsp;&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<br>CODE&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Course&nbsp;&nbsp;&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Offering Dept.&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<br>21BS002&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp; Advanced Numerical Techniques &nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>BSH&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<br>21BS003&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Functional Materials and Applications</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>BSH&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<br>21CE002&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Air Pollution and Environmental Impact Assessment</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>CIVIL&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<br>21CS002&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Fundamentals of Machine Learning</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>CSE&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<br>21IT002&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Fundamentals of Cloud Computing </td>
                    <td>&nbsp;&nbsp;&nbsp;<br>IT&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<br>21EC002&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Electronics for Agriculture </td>
                    <td>&nbsp;&nbsp;&nbsp;<br>ECE&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<br>21EE002&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Renewable Energy Sources</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>EEE&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<br>21ME002&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Principles of Entrepreneurship </td>
                    <td>&nbsp;&nbsp;&nbsp;<br>Mechanical&nbsp;&nbsp;&nbsp;</td>
                </tr>
            </tbody>
        </table>

        </div>
    </body>
    
    </html>
    
    ";

// $mail = new PHPMailer();

try {
    
    require "./db.php";
    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';
    
    $mail = new PHPMailer();
    
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
    $mail->Username   = 'openelectives@gmrit.edu.in';                     //SMTP username
    $mail->Password   = 'Newton@1234';                               //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       =  587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    //Recipients
    $mail->setFrom('openelectives@gmrit.edu.in', 'Open Electives - GMRIT');
    $mail->addAddress($email, $name);     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments


    //Content
    $mail->isHTML(true);
    $mail->Subject = $otp . " is your OTP for Open Elective Registration";
    $mail->Body = $body;
    $mail->AltBody = 'This is the OTP for open elective registration ' . $otp;
   // $mail->send();
    if($mail->send()){
        $jntu = explode("@",$email)[0];
        $updateMailStatus = "update students set email_status=1 where jntuno='$jntu'";
        $resultUpdateMailStatus = mysqli_query($conn,$updateMailStatus);
        if( $resultUpdateMailStatus){
        echo "Mail sent to " . $email . "";
        }
    }
    else{
    echo "---- Mail not sent to " . $email . "";
    }
    
    mysqli_close($conn);
} catch (Exception $e) {
    // require './db.php';
    // $sql = "insert into otpStatus values($email,'not inserted')";
    // $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    echo "catch Mail  not sent to " . $email . "";
    
}
