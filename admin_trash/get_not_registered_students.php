<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats | Open Elective GMRIT</title>

    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        table {
            min-width: 768px;
        }
    </style>
</head>

<body>
    <?php

    require_once '../config/db.php';

    echo "<table><thead>";
    echo "<th>JNTU NO</th>";
    echo "<th>Department</th>";
    echo "<th>Section</th>";
    echo "<th>Password</th>";
    echo "</thead><tbody>";

    $not_registered_students = "SELECT * FROM students WHERE JNTUNO NOT IN (SELECT JNTUNO FROM results) ORDER BY Department, Section, JNTUNO;";
    $not_registered_students = mysqli_query($connect, $not_registered_students);
    while ($row = mysqli_fetch_assoc($not_registered_students)) {
        echo "<tr>";
        echo "<td>" . $row['JNTUNO'] . "</td>";
        echo "<td>" . $row['Department'] . "</td>";
        echo "<td>" . $row['Section'] . "</td>";
        echo "<td>" . $row['Password'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    ?>
</body>

</html>