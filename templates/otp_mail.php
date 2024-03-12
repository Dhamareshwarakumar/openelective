<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <div id="main">
        <div class="header">
            <p style="color: #fff; font-size: 1.2rem; text-align: center;">Confirm Open Elective Registration</p>
        </div>
        <div class="container">
            <section id="info">
                <p id="description">
                    Hello ${name},<br>
                    Your Registration for GMRIT V Semester Open Elective is Initiated. Below is the
                    OTP for signup.
                <h1 style="text-align: center; margin-top: 20px">${OTP}</h1>
                </p>
            </section>
        </div>
    </div>
</body>

</html>



<?php
