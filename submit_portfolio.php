<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio_db";
$port = 3307; 

$conn = new mysqli($servername, $username, $password, $dbname,$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture form data
$name = $_POST['name'];
$bio = $_POST['bio'];
$skills = $_POST['skills'];
$interests = $_POST['interests'];
$linkedin = $_POST['linkedin'];
$github = $_POST['github'];

// Handle profile picture upload
$profile_pic = null;
if ($_FILES['profile_pic']['name']) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $profile_pic = $target_dir . basename($_FILES["profile_pic"]["name"]);
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profile_pic);
}

// Prepared statement to insert data into the database
$stmt = $conn->prepare("INSERT INTO portfolio_users (name, bio, skills, interests, profile_pic, linkedin, github) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)");

// Bind parameters
$stmt->bind_param("sssssss", $name, $bio, $skills, $interests, $profile_pic, $linkedin, $github);

// Execute the statement
if ($stmt->execute()) {
    $userId = $stmt->insert_id;
    header("Location: portfolio.php?id=" . $userId);
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
