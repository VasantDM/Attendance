<?php
    require 'connection.php';

    session_start();
if (isset($_REQUEST)) {

    $email = $_POST['email'];
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $action = $_POST['action'];
    
    // Find user by email
    $user_query = "SELECT * FROM register WHERE email = '$email'";
    $user_result = $conn->query($user_query);
    
    if ($user_result->num_rows == 0) {
        echo "User not found!";
        exit;
    }

    $user = $user_result->fetch_assoc();
    $register_id = $user['id'];

    if ($action == 'Check In') {
        // Insert check-in time
        $check_in_time = date('Y-m-d H:i:s');
        $attendance_query = "INSERT INTO logout (register_id, check_in_time) VALUES ('$register_id', '$check_in_time')";
        
        if ($conn->query($attendance_query) === TRUE) {
            
            echo "Checked in successfully at $check_in_time";
            // Optionally, send an email to notify the user
            // mail($email, "Check-In Confirmation", "You have checked in at $check_in_time");
        } else {
            echo "Error: " . $attendance_query . "<br>" . $conn->error;
        }
    } elseif ($action == 'Check Out') {
        // Get the latest check-in record that doesn't have a check-out time yet
        $attendance_query = "SELECT * FROM logout WHERE register_id = '$register_id' AND check_out_time IS NULL ORDER BY id DESC LIMIT 1";
        $attendance_result = $conn->query($attendance_query);
        
        if ($attendance_result->num_rows == 1) {
            $attendance = $attendance_result->fetch_assoc();
            $check_out_time = date('Y-m-d H:i:s');
            
            // Calculate total hours worked
            $check_in_time = new DateTime($attendance['check_in_time']);
            $check_out_time_dt = new DateTime($check_out_time);
            $interval = $check_in_time->diff($check_out_time_dt);
            $hours_worked = $interval->h + ($interval->i / 60);
            
            // Update check-out time and total hours worked
            $update_query = "UPDATE logout SET check_out_time = '$check_out_time', total_hours = '$hours_worked' WHERE id = " . $attendance['id'];
            
            if ($conn->query($update_query) === TRUE) {
                echo "Checked out successfully at $check_out_time. You worked for $hours_worked hours.";
                // Optionally, send an email to notify the user
                // mail($email, "Check-Out Confirmation", "You have checked out at $check_out_time after working for $hours_worked hours.");
            } else {
                echo "Error: " . $update_query . "<br>" . $conn->error;
            }
        } else {
            echo "No active check-in found!";
        }
    }
}
}
$conn->close();
?>
