<?php

require_once '../config/db.php';

$json = file_get_contents('php://input');
$data = json_decode($json);
$jntuNo = $data->jntu;

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


$password = generateNumericOTP(5);
$update = "UPDATE students SET Password='" . $password. "' WHERE JNTUNO='$jntuNo'";
$update_result = mysqli_query($connect, $update);
if($update_result){
echo "success";	
}
?>