<?php

session_start();


// If User Already logged in redirect to Home page
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Open Elective | GMRIT</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <form method="POST">
        <!-- Page Color Divider -->
        <div class="page-divider"></div>
        <!-- Landing Section -->
        <div class="container">
            <div class="row adminlogin_landing">
                <!-- Landing Section -- Card -->
                <div class="card adminlogin_landing_card">
                    <h3 class="text-center display-4" style="margin: 15px;">
                        Student <span class="text-primary">Signup</span>
                    </h3>

                    <!-- Landing Section -- Card -- Main Area -->
                    <div class="container ">
                        <div class="row align-items-center justify-content-center adminlogin_landing_card_main">
                            <!-- Landing Section --Card -- Main Area -- Image -->
                            <div class="col-md-6">
                                <img src="./img/login.png" alt="Login SVG" style="width: 100%; height: auto" />
                            </div>
                            <!-- Landing Section --Card -- Main Area -- Form -->
                            <div class="col-12 col-md-6">
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <input type="text" name="jntu_no" id="jntu_no" class="form-control" placeholder="Enter JNTU Number" style="border-radius: 50px">
                                        <div class="feedback" style="display: none;">
                                            Error
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <button type="submit" name="send_otp" id="send_otp" value="Send OTP" class="btn btn-outline-primary btn-block" style="border-radius: 50px"> <i class="fas fa-paper-plane"></i> Send OTP</button>
                                    </div>

                                    <small><a href="login.php">Already a user? click here to Login</a></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
    <script>
        const sendOtpButton = document.querySelector('#send_otp');
        const jntuNo = document.querySelector('#jntu_no');
        const feedback = document.querySelector('.feedback');
        const form = document.querySelector('form');

        function clearValidation() {
            // Clear valid and invalid classes
            jntuNo.classList = ['form-control'];
            feedback.classList = ['feedback'];
            feedback.style.display = 'none';
        }

        form.addEventListener('submit', e => {
            e.preventDefault();
            clearValidation();

            // check if jntu number is properly filled or not
            if (jntuNo.value.length !== 10) {
                jntuNo.classList.add('is-invalid');
                feedback.classList.add('invalid-feedback');
                feedback.innerText = 'Enter Valid JNTU Number';
                feedback.style.display = 'block';
            } else {
                jntuNo.classList.add('is-valid');
                feedback.classList.add('invalid-feedback');
                fetch('includes/send_otp.php', {
                        method: 'POST',
                        cache: 'no-cache',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        // FIXME: Change to uppercase
                        body: JSON.stringify({
                            jntu_no: jntuNo.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.jntu_no) {
                            clearValidation();
                            jntuNo.classList.add('is-invalid');
                            feedback.classList.add('invalid-feedback');
                            feedback.innerText = data.jntu_no;
                            feedback.style.display = 'block';
                        } else {
                            if (data.err) {
                                alert(err)
                                // FIXME: Handle err responses
                            }

                        }
                    })
                    .catch(err => console.log(err))
            }
        })
    </script>
</body>

</html>