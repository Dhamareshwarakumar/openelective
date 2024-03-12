<?php
session_start();

// If User Not logged in redirect to Login Page
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
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
    <title>Open Elective | GMRIT</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="css/loader.css">

</head>

<body>
    <?php include 'navbar.php'; ?>
    <!-- <section class="instructions">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <div class="card">
                        <div class="card-header">
                            Things to be Noted
                        </div>
                        <div class="card-body">
                            <ol>
                                <li>Instructions Comes here</li>
                                <li>Instructions Comes here</li>
                                <li>Instructions Comes here</li>
                                <li>Instructions Comes here</li>
                                <li>Instructions Comes here</li>
                                <li>Instructions Comes here</li>
                                <li>Instructions Comes here</li>
                                <li>Instructions Comes here</li>
                                <li>Instructions Comes here</li>
                            </ol>

                            <div class="container-fluid mt-5">
                                <div class="row">
                                    <div class="col-12 col-md-8">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="agree_check" required checked>
                                                <label class="form-check-label" for="agree_check">
                                                    Agree to terms and conditions
                                                </label>
                                                <div class="feedback" style="display: none;">
                                                    You must agree before submitting.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="div col-12 col-md-4">
                                        <button class="btn btn-primary btn-block" id="proceed">Proceed</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <section class="main">
        <?php

        // import database
        require_once './config/db.php';

        // Get Active Time
        require_once './includes/get_active_time.php';


        if ($current_time < $event_start_time) {
            // Display Event is not started
        ?>
            <div class="container" style="margin-top:100px;">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4" style="margin-top:20px;">
                                    <!-- <img src="/assets/images/oops.png" alt="oops" class="img-fluid" /> -->
                                </div>
                            </div>
                            <h1 style="text-align:center;">
                                Registrations are not started...<br>
                                Please visit on or after <?php echo $event_start_time ?>
                            </h1>
                            <span style="text-align:center;">Note: If start time is achieved please refresh the page!!!</span>
                            <!-- TODO:ADD Countdown: -->
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else if ($current_time > $event_end_time) {
            // Display Event is completed
        ?>
            <div class="container" style="margin-top:100px;">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4" style="margin-top:20px;">
                                    <!-- <img src="/assets/images/oops.png" alt="oops" class="img-fluid" /> -->
                                </div>
                            </div>
                            <h1 style="text-align:center;">Registrations are closed..!</h1>
                            <span style="text-align:center;">Note: Please contact administration for more information!!!</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            // Already Registered
            $sql = "SELECT * FROM results WHERE JNTUNO='" . $_SESSION['user']['JNTUNO'] . "'";
            $result = mysqli_query($connect, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                $course = "SELECT Subject FROM electives where CourseCode='" . $row['CourseCode'] . "'";
                $course_result = mysqli_query($connect, $course);
                $course_row = mysqli_fetch_assoc($course_result);

            ?>
                <div class="container">
                    <div class="row justify-content-center align-items-center" style="height: 80vh;">
                        <div class="col-12 col-md-10">
                            <div class="card mt-5">
                                <div class="card-body">
                                    <h3 class="text-center"><span class="text-primary d-block d-md-inline">HELLO</span> <?php echo $_SESSION['user']['FirstName'] . " " . $_SESSION['user']['LastName'] ?></h3>
                                    <p class="text-center">You are registered for the course
                                        <strong class="text-primary d-block"><?php echo $course_row['Subject']; ?></strong> @ <?php echo $row['time']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                exit();
            }
            ?>
            <!-- Display Form -->
            <div class="loader loading" style="display: none;">Loading...</div>
            <div class="container main mt-5">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-danger" style="text-align:center;">Open Elective Registrations</h3>
                            </div>
                            <div class="card-body">
                                <form name="form2" class="form2" method="post" action="#">
                                    <!-- All Details of the user are fetched from the session -->
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="JNTUNO" id="JNTUNO" placeholder="Enter JNTU Number" readonly="readonly" value='<?php echo $_SESSION['user']['JNTUNO']; ?>'>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="FirstName" id="FirstName" placeholder="FirstName" readonly="readonly" value='<?php echo $_SESSION['user']['FirstName']; ?>'>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="LastName" id="LastName" placeholder="LastName" readonly="readonly" value='<?php echo $_SESSION['user']['LastName']; ?>'>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="Email" id="Email" placeholder="Email" readonly="readonly" value='<?php echo $_SESSION['user']['Email']; ?>'>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="Department" id="Department" placeholder="Department" readonly="readonly" value='<?php echo $_SESSION['user']['Department']; ?>'>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="Section" id="Section" placeholder="Section" readonly="readonly" value='<?php echo $_SESSION['user']['Section']; ?>'>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="Gender" id="Gender" placeholder="Gender" readonly="readonly" value='<?php echo $_SESSION['user']['Gender']; ?>'>
                                    </div>
                                    <!-- Display the Departments of available Electives -->
                                    <div class="form-group">
                                        <select class="form-control" name="Elevtivedepartment" id="Elevtivedepartment">
                                            <!-- <option value='ECE'>ECE</option>
                                            <option value='CSE'>CSE</option>
                                            <option value='IT'>IT</option>
                                            <option value='MECH'>MECHANICAL</option>
                                            <option value='CIVIL'>CIVIL</option>
                                            <option value='EEE'>EEE</option>
                                            <option value='CHEMICAL'>CHEMICAL</option>
                                            <option value='POWER'>POWER</option>
                                            <option value='BS&H'>BS&H</option> -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control select2" name="Elevtivesub" id="Elevtivesub">
                                            <option value="" disabled selected>Elective Subject</option>
                                        </select>
                                        <span id="elective_error" style="color:red"></span>
                                    </div>

                                    <center><button type="submit" class="btn btn-outline-primary button">Submit</button></center>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-order-md-first">
                        <div class="card-body table-responsive" id="statistics">
                            <table id="example1" class="table table-head-fixed  table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Remaining Seats</th>
                                        <th>Subject</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                                <tbody id="insert_data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }

        ?>
    </section>

    <script>
        // let agree_check = document.querySelector('#agree_check');
        // let proceed = document.querySelector('#proceed');
        // let feedback = document.querySelector('.feedback');

        // agree_check.addEventListener('change', e => {
        //     if (agree_check.checked) {
        //         proceed.classList.remove('disabled');
        //     } else {
        //         proceed.classList.add('disabled');
        //     }
        // })

        // proceed.addEventListener('click', e => {
        //     if (!agree_check.checked) {
        //         agree_check.classList.add('is-invalid');
        //         feedback.classList.add('invalid-feedback');
        //         feedback.style.display = 'block';
        //     } else {
        //         document.querySelector('.instructions').style.display = 'none';
        //         document.querySelector('.main').style.display = 'block';
        //     }
        // })


        const Electivedepartment = document.querySelector('#Elevtivedepartment');
        const Elevtivesub = document.querySelector('#Elevtivesub');
        const elective_error = document.querySelector('#elective_error');
        const loader = document.querySelector('.loader');

        function set_loader() {
            loader.style.display = "block";
        }

        function reset_loader() {
            loader.style.display = "none";
        }

        function get_seats_left() {
            fetch('includes/get_seats_left.php')
                .then(response => response.json())
                .then(data => {
                    var str = ``;
                    var string = ``;
                    for (let i of Object.keys(data)) {
                        if (data[i].department === document.getElementById('Elevtivedepartment').value && data[i].seats_left !== 0) {
                            str += `<option value="` + i + `">` + data[i].CourseName + `</option>`;
                        }
                        string += `
                    <tr>
                        <td>` + data[i].seats_left + `</td>
                        <td>` + data[i].CourseName + `</td>
                        <td>` + data[i].department + `</td>
                    </tr>`;
                    }
                    // FIXME: Network Resource is wasting 
                    document.getElementById("Elevtivesub").innerHTML = str;
                    document.getElementById('insert_data').innerHTML = string;
                    document.getElementById('statistics').style.display = "block";
                })
                .catch(err => console.error(err))
                .finally(() => reset_loader())
        }


        // Retreive Seats Left to get registered for that particular student
        set_loader();
        get_seats_left();

        // Fetch Allowed Subjects from the electives
        set_loader();
        fetch('includes/get_allowed_subjects.php')
            .then(response => response.json())
            .then(data => {
                let duplicates = [];
                let options = `<option value="" disabled selected>Elective Departments</option>`;
                for (let key of Object.keys(data)) {
                    if (!duplicates.includes(data[key])) {
                        options += `<option value='${data[key]}'>${data[key]}</option>`;
                        duplicates.push(data[key]);
                    }
                }
                Electivedepartment.innerHTML = options;
            })
            .catch(err => alert(err))
            .finally(() => {
                reset_loader();
            })


        // Reload on Department Change
        document.getElementById('Elevtivedepartment').addEventListener('change', e => {
            get_seats_left();
        })


        // Form Submission
        document.querySelector('form').addEventListener('submit', e => {
            e.preventDefault();

            // Validating Elective Subject
            if (Elevtivesub.value.length === 0) {
                elective_error.style.display = 'block';
                elective_error.innerHTML = 'Course Should not be empty';
            } else {
                set_loader();
                fetch('includes/submit_form.php', {
                        method: "POST",
                        cache: 'no-cache',
                        headers: {
                            "Content-Type": 'application/json'
                        },
                        body: JSON.stringify({
                            CourseCode: document.querySelector('#Elevtivesub').value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.msg) {
                            window.location.href = 'index.php';
                        } else {
                            console.log(data.err);
                            alert(data.err);
                            window.location.href = 'index.php';
                        }
                    })
                    .catch(err => console.log(err))
                    .finally(() => {
                        reset_loader();
                    })
            }
        })
    </script>

    <!-- Boostrap Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>