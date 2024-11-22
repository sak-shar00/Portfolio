<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio_db";
$port=3307;

$conn = new mysqli($servername, $username, $password, $dbname,$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM portfolio_users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['name']); ?>'s Portfolio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #333;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .portfolio-container {
            max-width: 600px;
            width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .portfolio-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
        }
        .portfolio-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="portfolio-container">
    <?php if ($row['profile_pic']): ?>
        <img src="<?php echo htmlspecialchars($row['profile_pic']); ?>" alt="Profile Picture" class="profile-pic">
    <?php endif; ?>
    
    <h1><?php echo htmlspecialchars($row['name']); ?></h1>
    <p><strong>Bio:</strong> <?php echo htmlspecialchars($row['bio']); ?></p>
    <p><strong>Skills:</strong> <?php echo htmlspecialchars($row['skills']); ?></p>
    <p><strong>Interests:</strong> <?php echo htmlspecialchars($row['interests']); ?></p>
    
    <div class="portfolio-links">
        <?php if ($row['linkedin']): ?>
            <a href="<?php echo htmlspecialchars($row['linkedin']); ?>" target="_blank">LinkedIn</a>
        <?php endif; ?>
        <?php if ($row['github']): ?>
            <a href="<?php echo htmlspecialchars($row['github']); ?>" target="_blank">GitHub</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
    } else {
        echo "No portfolio found.";
    }
    $stmt->close();
} else {
    echo "Invalid ID.";
}
$conn->close();
?>
