<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats</title>
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

    $max_strength_allowed = array();

    // Getting Total Sections Count
    // CSE A, CSE B, CSE C, Civil A, Civil B, IT A ....
    $sections_count = "SELECT COUNT(DISTINCT Department, Section) as sections_count FROM students";
    $sections_count_result = mysqli_query($connect, $sections_count);
    $row = mysqli_fetch_assoc($sections_count_result);
    $sections_count = $row['sections_count'];


    // Gettings Max Number of Allowed Students for each course from each section
    $electives = "SELECT * FROM electives";
    $electives_result = mysqli_query($connect, $electives);
    while ($row = mysqli_fetch_assoc($electives_result)) {
        $count = 0;
        $not_allowed = explode(',', $row['NotAllowedBranches']);
        // Finding Count of not allowed sections
        foreach ($not_allowed as $branch) {
            $not_allowed_sections_count = "SELECT COUNT(DISTINCT Section) as not_allowed_sections_count FROM students WHERE Department='" . $branch . "'";
            $not_allowed_sections_count_result = mysqli_query($connect, $not_allowed_sections_count);
            while ($not_allowed_sections_count_row = mysqli_fetch_assoc($not_allowed_sections_count_result)) {
                $count += $not_allowed_sections_count_row['not_allowed_sections_count'];
            }
        }
        $max_limit = $row['Strength'] / ($sections_count - $count);
        $max_strength_allowed[$row['CourseCode']] = ceil($max_limit);
    }


    $CourseCode = "SELECT DISTINCT CourseCode, Subject FROM electives ORDER BY CourseCode";
    $CourseCode_result = mysqli_query($connect, $CourseCode);
    while ($course = mysqli_fetch_assoc($CourseCode_result)) {
        $sections = "SELECT DISTINCT Department, Section from students ORDER BY Department";
        $sections_result = mysqli_query($connect, $sections);
        $count = 0;
        echo "<table>";
        echo "<thead>";
        echo "<th>Registered</th>";
        echo "<th>Total</th>";
        echo "<th>Coures</th>";
        echo "<th>Department</th>";
        echo "<th>Section</th>";
        echo "</thead>";
        while ($section = mysqli_fetch_assoc($sections_result)) {
            $output = "SELECT Count(*) as count FROM results t1 INNER JOIN students t2 on t1.JNTUNO = t2.JNTUNO INNER JOIN electives t3 on t1.CourseCode = t3.CourseCode WHERE t1.CourseCode = '" . $course['CourseCode'] . "' AND t2.Department = '" . $section['Department'] . "' AND Section = '" . $section['Section'] . "'";
            $output_result = mysqli_query($connect, $output);
            echo "<tbody><tr>";
            while ($row = mysqli_fetch_assoc($output_result)) {
                $count += $row['count'];
                echo "<td>" . $row['count'] . "</td>";
                echo "<td>" . $max_strength_allowed[$course['CourseCode']] . "</td>";
                echo "<td>" . $course['Subject'] . "</td>";
                echo "<td>" . $section['Department'] . "</td>";
                echo "<td>" . $section['Section'] . "</td>";
            }
            echo "</tr></tbody>";
        }
        echo "</table>";
        echo "Total: " . $count . "<br>";
    }


    ?>

    <script>
        setTimeout(() => {
            window.location.reload();
        }, 5000)
    </script>
</body>

</html>