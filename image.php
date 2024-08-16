<?php
session_start();
require 'connection.php';

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if an image is captured from the camera
    if (isset($_POST['capturedImage']) && !empty($_POST['capturedImage'])) {
        $data = $_POST['capturedImage'];

        // Ensure data is a base64 encoded image
        if (strpos($data, 'data:image/png;base64,') === 0) {
            $data = str_replace('data:image/png;base64,', '', $data);
        } else if (strpos($data, 'data:image/jpeg;base64,') === 0) {
            $data = str_replace('data:image/jpeg;base64,', '', $data);
        } else {
            echo json_encode($response);
            exit;
        }
 
        $data = base64_decode($data);
        $file_name = rand() . date("h_i_sa");
        $filename = $file_name . '.png';
        $location = "image_proof/" . $filename;

        if (file_put_contents($location, $data)) {

           
            $sessionEmail = $_SESSION['email'];
            

            $sql = "INSERT INTO image_proof (session_email,user_photo) VALUES ('$sessionEmail','$filename')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $response['success'] = true;
            }
        }
    }
}

echo json_encode($response);
?>