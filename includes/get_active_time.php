<?php

// import database
// require_once './config/db.php';

// Fetching Current Date and Time
date_default_timezone_set("Asia/Calcutta");
$current_time = date("Y-m-d H:i:s");

// Fetching Event Start and End Date from Database
$event_time = $connect->query("select * from active_time")->fetch_array(MYSQLI_NUM);
$event_start_time = date("Y-m-d H:i:s", strtotime($event_time[1]));
$event_end_time = date("Y-m-d H:i:s", strtotime($event_time[2]));
