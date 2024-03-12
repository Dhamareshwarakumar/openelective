<?php

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
    $max_limit =  10 + $row['Strength'] / ($sections_count - $count);
    $max_strength_allowed[$row['CourseCode']] = ceil($max_limit);
}
