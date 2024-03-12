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

    <link rel="icon" href="img/gmrit_logo.png">
    <title>Login | Open Elective | GMRIT</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

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
                    <h3 class="text-center display-4 login-heading" style="margin: 15px;">
                        Student <span class="text-primary">Login</span>
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
                                <p class="text-center" style="font-size: 2rem">Open Elective <span class="text-primary">Registration</span></p>
                                <!-- Landing Section --Card -- Main Area -- Form -- Input Field -->
                                <!-- <div class="row justify-content-center">
                                    <img src="./img/google_logo.png" alt="google_logo" style="height: 300px; width:auto" class="d-none d-md-block">
                                    <button type="submit" name="google_login" value="login" class="btn btn-outline-primary btn-block" style="border-radius: 50px">Sign in with Google</button>
                                </div> -->
                                <!-- <div class="row justify-content-center"> -->
                                <!-- <img src="./img/google_logo.png" alt="google_logo" style="height: 300px; width:auto" class="d-none d-md-block"> -->
                                <div class="form-group">
                                    <input type="text" name="username" id="username" class="form-control" placeholder="JNTU Number" style="border-radius: 20px">
                                    <div class="feedback" style="display: none;">
                                        Feedback
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" style="border-radius: 20px">
                                    <div class="feedback" style="display: none;">
                                        Feedback
                                    </div>
                                    <small class="text-success">Please check your inbox for the password</small>
                                </div>
                                <button type="submit" name="google_login" value="login" class="btn btn-outline-primary btn-block" style="border-radius: 50px">Login</button>
                                <!-- FIXME: Remove Comments Later -->
                                <!-- <small><a href="signup.php">not a user? click here to sign up</a></small> -->

                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        const form = document.querySelector('form');
        const username = document.querySelector('#username');
        const password = document.querySelector('#password');

        function clearValidation(element) {
            // Clear valid and invalid classes
            element.classList = ['form-control'];
            feedback = element.parentElement.querySelector('.feedback');
            feedback.classList = ['feedback'];
            feedback.style.display = 'none';
        }

        function addErrorFeedback(element, message) {
            clearValidation(element);
            element.classList.add('is-invalid');
            feedback = element.parentElement.querySelector('.feedback');
            feedback.classList.add('invalid-feedback');
            feedback.innerText = message;
            feedback.style.display = 'block';
        }

        function addSuccessFeedback(element) {
            clearValidation(element);
            element.classList.add('is-valid');
            feedback = element.parentElement.querySelector('.feedback');
            feedback.classList.add('valid-feedback');
        }



        form.addEventListener('submit', e => {
            e.preventDefault();

            // Validate Username
            if (username.value.trim().length === 0) {
                addErrorFeedback(username, "JNTU Number Should not be empty");
            } else if (username.value.trim().length !== 10) {
                // FIXME: Add Regular Expression Here for checking
                addErrorFeedback(username, "Invalid JNTU Number");
            } else {
                addSuccessFeedback(username);
                username.value = username.value.toUpperCase();
            }

            // Validate Password
            if (password.value.trim().length === 0) {
                addErrorFeedback(password, "Password Should not be empty");
            } else {
                addSuccessFeedback(password);
            }

            // Submit the form
            fetch('includes/login.php', {
                    method: 'POST',
                    cache: "no-cache",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        jntu_no: username.value.trim(),
                        password: password.value.trim()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    clearValidation(username);
                    clearValidation(password);
                    if (data.jntu_no) {
                        addErrorFeedback(username, data.jntu_no);
                    }

                    if (data.password) {
                        addErrorFeedback(password, data.password)
                    }

                    if (data.err) {
                        alert(data.err);
                        // FIXME: Handle Errors
                    }

                    if (data.msg === "success") {
                        // Everything Went good, Redirect to index
                        window.location.href = "index.php";
                    }

                })
        })
    </script>
</body>

</html>