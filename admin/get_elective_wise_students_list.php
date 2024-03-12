<?php
// import database connection
require_once '../config/db.php';

// Get elective wise students list
// SELECT r.JNTUNO, CONCAT(s.FirstName, ' ', s.LastName) AS Name, s.Department, s.Section, r.CourseCode, e.Subject FROM `results` r INNER JOIN students s ON r.JNTUNO = s.JNTUNO INNER JOIN electives e ON e.CourseCode = r.CourseCode WHERE r.CourseCode = '19CS001' ORDER BY s.Department, r.JNTUNO;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Developed By: Dhamareshwarakumar Gandikota -->
    <title>Elective Wise Students Data | Open-Elective | GMRIT</title>

    <!-- Favicons -->
    <link href="./NiceAdmin/assets/img/favicon.png" rel="icon">
    <link href="./NiceAdmin/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="./NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <!-- <link href="./NiceAdmin/assets/css/style.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <main id="main" class="main">
        <section class="container-fluid mt-5">
            <div class="row">
                <div class="col-12 col-md-8 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Elective Wise Registrations</h5>
                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Position</th>
                                        <th scope="col">Age</th>
                                        <th scope="col">Start Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Brandon Jacob</td>
                                        <td>Designer</td>
                                        <td>28</td>
                                        <td>2016-05-25</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Bridie Kessler</td>
                                        <td>Developer</td>
                                        <td>35</td>
                                        <td>2014-12-05</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>Ashleigh Langosh</td>
                                        <td>Finance</td>
                                        <td>45</td>
                                        <td>2011-08-12</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">4</th>
                                        <td>Angus Grady</td>
                                        <td>HR</td>
                                        <td>34</td>
                                        <td>2012-06-11</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">5</th>
                                        <td>Raheem Lehner</td>
                                        <td>Dynamic Division Officer</td>
                                        <td>47</td>
                                        <td>2011-04-19</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

    <!-- Vendor JS Files -->
    <script src="./NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="./NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="./NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>

    <!-- Template Main JS File -->
    <script src="./NiceAdmin/assets/js/main.js"></script>

</body>

</html>