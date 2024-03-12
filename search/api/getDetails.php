<?php
require_once "../db.php";

$json = file_get_contents('php://input');
$data = json_decode($json);
$jntu = $data->jntuNo;
// $jntu = "19341A0555";
$fetchDetails = "select s1.CourseCode,s2.Subject from results s1, electives s2 where JNTUNO='".$jntu."' and s1.CourseCode=s2.CourseCode";
$detailsfetched = mysqli_query($conn,$fetchDetails);

while($row = mysqli_fetch_assoc($detailsfetched)){
    $subject = $row['Subject'];
    echo $subject;
}

?>