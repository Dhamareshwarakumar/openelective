<?php




// $host = "localhost";
// $dbuname = "OE_USER";
// $dbpassword = "GMRIT@OE2023";
// $dbname = "openElective_ACM";


// // $connect = mysqli_connect($host, $dbuname, $dbpassword, $dbname);
// $conn=mysqli_connect("localhost", "root", "","openElective_ACM");

// if (!$conn) {
//     // die("Connection Failed: " . mysqli_connect_error());     Commented becaose it is giving extra info to user/hacker
//     die("INTERNAL SERVER ERROR: Database Connection Failed");
// }


$host = "localhost";
$dbuname = "root";
$dbpassword = "";
$dbname = "openelective_new";


// $connect = mysqli_connect($host, $dbuname, $dbpassword, $dbname);
$conn=mysqli_connect("localhost", "gmritcha_acm", "{VP-IxD6@z=Z","gmritchapterhost_openelective_new");
if (!$conn) {
    die("INTERNAL SERVER ERROR: Database Connection Failed");
}