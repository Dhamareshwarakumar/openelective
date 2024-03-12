<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <div class="text-center m-5">
        <div class="badge bg-primary">
            <h3>OTP STATUS</h3>
        </div>
    </div>
    <br>
    <div class="container success" id="success">
    </div>
    <?php
    require './db.php';
    $sql = "SELECT * FROM students where email_status = 0";
    $result = mysqli_query($conn, $sql);
    $array = array();
    while ($row =  mysqli_fetch_assoc($result)) {
        $email = $row['JNTUNO'] . "@gmrit.edu" . ".in";
        $name = $row['FirstName'] . " " . $row['LastName'];
        $otp = $row['Password'];
        if($row['email_status']==0){
        array_push($array, $row);
        }
    }
    ?>
    <script>
    let sucess = document.getElementById('success');
    let count = 0;
    students = <?php echo json_encode($array) ?>;
    students.forEach((ele) => {
        var name = ele['FirstName'] + " " + ele['LastName'];
        var otp = ele['Password'];
        var email = ele['JNTUNO'].trim() + '@gmrit.edu.in';
        console.log(email, name, otp);
        fetch('./send_otp.php', {
                method: 'POST',
                cache: 'no-cache',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    password: otp,
                    name: name
                })
            })
            .then(response => response.text())
            .then(response => {
                let p = document.createElement('p');
                p.innerText = response;
                sucess.appendChild(p);
            })
        /*count = count + 1;
        if (count % 100 == 0) {
            console.log("100 mails sent");
        }*/
    });
    </script>
</body>

</html>