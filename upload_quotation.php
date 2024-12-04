<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['quotation'])) {
    // Retrieve the job ID from the hidden input
    $job_id = $_POST['job_id'];
    
    // Handle file upload
    $file = $_FILES['quotation'];
    $fileName = $_FILES['quotation']['name'];
    $fileTmpName = $_FILES['quotation']['tmp_name'];
    $fileError = $_FILES['quotation']['error'];
    $fileSize = $_FILES['quotation']['size'];

    // Allowed file type (PDF only)
    $allowed = array('pdf');
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Check if the file is a PDF
    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) { // File size limit (1MB)
                // Generate a unique name for the file
                $fileNewName = uniqid('', true) . '.' . $fileExt;
                $fileDestination = 'assets/upload/' . $fileNewName;

                // Move the uploaded file to the server
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Database connection
                    $conn = new mysqli('localhost', 'root', '', 'spotengineer');
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Update the database with the file path
                    $stmt = $conn->prepare("UPDATE job_entry SET quotation = ? WHERE id = ?");
                    $stmt->bind_param("si", $fileDestination, $job_id);
                    if ($stmt->execute()) {
                        echo "Quatation uploaded successfully!";
                        // Redirect back to the page
                        header("Location: job-entry.php"); // Replace with your page name
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
