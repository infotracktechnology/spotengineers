<?php
ob_start();
session_start();
include "config.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['quotation'])) {
    $job_id = $_POST['job_id'];
    $file = $_FILES['quotation'];
    $fileName = $_FILES['quotation']['name'];
    $fileTmpName = $_FILES['quotation']['tmp_name'];
    $fileError = $_FILES['quotation']['error'];
    $fileSize = $_FILES['quotation']['size'];
    $allowed = array('pdf');
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) {
                $fileNewName = uniqid('', true) . '.' . $fileExt;
                $fileDestination = 'assets/upload/' . $fileNewName;
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // $conn = new mysqli('localhost', 'root', '', 'spotengineer');
                    // if ($conn->connect_error) {
                    //     die("Connection failed: " . $conn->connect_error);
                    // }
                    $stmt = $con->prepare("UPDATE job_entry SET quotation = ? WHERE id = ?");
                    $stmt->bind_param("si", $fileDestination, $job_id);
                    if ($stmt->execute()) {
                        echo "Quatation uploaded successfully!";
                        header("Location: job-entry.php"); 
                    } else {
                        echo "Error updating database.";
                    }

                    $stmt->close();
                    $conn->close();
                } else {
                    echo "<script>alert('Error moving the file.');window.location.href='job-entry.php';</script>"; // Replace with your page nameError moving the file.";
                }
            } else {
                echo "<script>alert('File is too large!');window.location.href='job-entry.php';</script>"; // Replace with your page nameFile is too large!";
            }
        } else {
            echo "<script>alert('There was an error uploading your file.');window.location.href='job-entry.php';</script>"; // Replace with your page nameThere was an error uploading your file.";
        }
    } else {
        echo "<script>alert('Only PDF files are allowed!');window.location.href='job-entry.php';</script>";
    }
}
?>
