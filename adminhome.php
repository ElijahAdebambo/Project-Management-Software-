<?php
// Start the session to enable user data persistence across pages
session_start();

$host = "localhost";
$username = "root";
$password = "";
$dbname = "fyp";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch developers for the dropdown
$developersQuery = "SELECT id, name FROM developers ORDER BY name";
$developersResult = $conn->query($developersQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $estimated_hours = $_POST['estimated_hours'];
    $priority = $_POST['priority'];
    $developerId = $_POST['developer_name'];

    // First, check if the developer can take on the task without exceeding 30 hours of workload
    $checkWorkloadQuery = "SELECT SUM(estimated_hours) AS totalHours FROM tasks WHERE developer_id = ?";
    $stmt = $conn->prepare($checkWorkloadQuery);
    $stmt->bind_param("i", $developerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close(); // Close the statement after use
    $currentWorkload = $row ? $row['totalHours'] : 0;

    if ($currentWorkload + $estimated_hours > 30) {
        $_SESSION['message'] = "This developer cannot take on the task without exceeding 30 hours of workload.";
        header('Location: adminhome.php');
        exit();
    }

    // If the developer can take the task, insert it into the database
    $insertQuery = "INSERT INTO tasks (title, description, estimated_hours, priority, developer_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssisi", $title, $description, $estimated_hours, $priority, $developerId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Task successfully added.";
        
        // Now update the developer's current workload
        $updateWorkloadQuery = "
            UPDATE developers
            SET current_workload = current_workload + ?
            WHERE id = ?
        ";
        
        $updateStmt = $conn->prepare($updateWorkloadQuery);
        $updateStmt->bind_param("ii", $estimated_hours, $developerId);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        $_SESSION['message'] = "Failed to add task.";
    }

    $stmt->close(); // Close the statement 

    header('Location: adminhome.php');
    exit();
}

$conn->close(); // Close the connection at the end of the script
?>



<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Admin Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .form-container {
            background-color: white;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        input[type="text"], input[type="number"], input[type="file"], select, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        p.message {
    text-align: center;
    color: green;
    }
    </style>
</head>
<body style="background-color:#c8d5dd;">
<div class="header">
    <!--<a href="userhome.php" ><img src="images/cypershop.png" class="logo"/></a> -->

    <div class="navcontainer">
        <div class="header-right">
           <a href="userhome.php">User Dashboard</a>    
            <a href="workitemanalytics.php">Analytics</a>
            <a href="Calender.php">Calender</a>  
		<a href="developerpipeline.php">Pipeline</a>
		
            <a href="registeredaccounts.php">Registered Accounts</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class= "header-bottom">
<h2>Admin Dashboard</h2>
        </div>
    </div>
    
</div>

<center> <h3> <b><?php  echo   $_SESSION["username"]
 ?> </b>is logged in</h3> </center> 

<div class="form-container">
    <h2>Create a Work Item</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="title">Task Title:</label>
        <input type="text" id="title" name="title" required>
        
        <label for="description">Task Description:</label>
        <textarea id="description" name="description" rows="5" required></textarea>
        
        <label for="estimated_hours">Estimated Hours:</label>
        <select id="estimated_hours" name="estimated_hours" required>
            <?php for ($i = 1; $i <= 30; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        
        <label for="developer_name">Developer:</label>
        <select id="developer_name" name="developer_name" required>
            <option value="">Select Developer</option>
            <?php if ($developersResult->num_rows > 0): ?>
                <?php while ($row = $developersResult->fetch_assoc()): ?>
                    <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            <?php else: ?>
                <option value="">No developers found</option>
            <?php endif; ?>
        </select>
        
        <label for="priority">Priority:</label>
    <select id="priority" name="priority" required>
    <option value="Low">Low</option>
    <option value="Medium">Medium</option>
    <option value="High">High</option>
    </select>
        <label for="attachment">Attachments:</label>
        <input type="file" id="attachment" name="attachment">
        
        <input type="submit" value="Create Work Item">
    </form>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p class='message'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>
</div>

</body>

</html>
<?php

include('footer.php');
?>