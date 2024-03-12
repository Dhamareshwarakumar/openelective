<?php

$host = "localhost";
$dbuname = "root";
$dbpassword = "";
$dbname = "openelective_new";


// $connect = mysqli_connect($host, $dbuname, $dbpassword, $dbname);
$connect=mysqli_connect("localhost", "gmritcha_acm", "{VP-IxD6@z=Z","gmritchapterhost_openelective_new");
if (!$connect) {
    // die("Connection Failed: " . mysqli_connect_error());     Commented becaose it is giving extra info to user/hacker
    die("INTERNAL SERVER ERROR: Database Connection Failed");
}