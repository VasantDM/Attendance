
<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];


    if (empty($name) || empty($email) || empty($password) || empty($mobile) || empty($gender) || empty($address)) {
        echo '0'; // Return '0' if any field is empty
    } else {
        // Check if the email already exists
        $checkEmailQuery = "SELECT * FROM register WHERE email = '$email'";
        $emailResult = mysqli_query($conn, $checkEmailQuery);

        if (mysqli_num_rows($emailResult) > 0) {
            echo '3'; // Return '3' if email already exists
        } else {
            // Insert the new user data
            $sql = "INSERT INTO register (name, email, password,mobile,gender,address)
                    VALUES ('$name', '$email', '$password','$mobile','$gender','$address')";
            $res = mysqli_query($conn, $sql);

            if ($res) {
                echo '1'; // Return '1' for successful registration
            } else {
                echo '2'; // Return '2' for database insertion error
            }
        }
    }
}
?>