<?php
include 'connection.php';
//session_start();
$message = ''; // Initialize message variable

// Check if add developer form was submitted
if (isset($_POST['add_developer'])) {
    $devName = $_POST['developer_name'];
    $devEmail = $_POST['developer_email'];
    $devSpecialization = $_POST['developer_specialization'];
    $devLevel = $_POST['developer_level'];
    $stmt = $con->prepare("INSERT INTO developers (name, email, specialization, level) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $devName, $devEmail, $devSpecialization, $devLevel);
    if ($stmt->execute()) {
        $message = 'Developer added successfully!';
    } else {
        $message = 'Error adding developer: ' . $con->error;
    }
    $stmt->close();
}

// Check if a remove developer request was made
if (isset($_GET['remove'])) {
    $devId = $_GET['remove'];
    $stmt = $con->prepare("DELETE FROM developers WHERE id = ?");
    $stmt->bind_param("i", $devId);
    if ($stmt->execute()) {
        $message = 'Developer removed successfully!';
    } else {
        $message = 'Error removing developer: ' . $con->error;
    }
    $stmt->close();
}

// Check if a task completion request was made
if (isset($_POST['mark_completed'])) {
    $taskId = $_POST['task_id'];
    $stmt = $con->prepare("UPDATE tasks SET completed = 1 WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    if ($stmt->execute()) {
        $message = 'Task marked as completed successfully!';
    } else {
        $message = 'Error updating task: ' . $con->error;
    }
    $stmt->close();
}

// Check if a task deletion request was made
if (isset($_POST['delete_task'])) {
    $taskId = $_POST['task_id']; 
    $stmt = $con->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    if ($stmt->execute()) {
        $message = 'Task deleted successfully!';
    } else {
        $message = 'Error deleting task: ' . $con->error;
    }
    $stmt->close();
}

// Query to fetch all developers and their tasks
$query = "SELECT developers.id AS dev_id, developers.name AS dev_name, developers.email AS dev_email, developers.specialization AS specialization, developers.level AS level, tasks.id AS task_id, tasks.title AS title, tasks.description AS description, tasks.estimated_hours AS estimated_hours, tasks.attachment_path AS attachment_path, tasks.completed AS completed
FROM developers
LEFT JOIN tasks ON developers.id = tasks.developer_id
ORDER BY developers.name, tasks.title";

$result = $con->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>User Dashboard</title>
    <style>
        body {
            background-color: #c8d5dd;
            font-family: Arial, sans-serif;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {background-color: #f2f2f2;}
        tr:hover {background-color: #ddd;}
        a {color: #4CAF50;}
        .form-container {
            background-color: #f2f2f2;
            padding: 15px;
            margin: 0 auto;
            margin-top: 50px;
            border-radius: 8px;
            width: auto;
            max-width: 500px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .developer-form .form-group {
            margin-bottom: 10px;
            width: 100%;
        }
        .developer-form label {
            display: block;
            margin-bottom: 5px;
        }
        .developer-form input[type="text"], .developer-form input[type="email"], .developer-form select, .developer-form input[type="radio"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .developer-form button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .developer-form button:hover {
            background-color: #45a049;
        }
    </style>



<div class="header">
    <div class="navcontainer">
        <div class="header-right">
        <a href="adminhome.php">Admin Dashboard</a>  
           <a href="calender.php"> Calendar</a>    
            <a href="workitemanalytics.php">Analytics</a>
		<a href="developerpipeline.php">Pipeline</a>
		
            <a href="registeredaccounts.php">Registered Accounts</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="header-bottom">
            <h2>User Dashboard</h2>
        </div>
    </div>
</div>
</head>
<body style="background-color:#c8d5dd;">
<center><h3><b><?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "Guest"; ?> </b>is logged in</h3></center>

<div class="form-container">
    <h2>Add New Developer</h2>
    <form action="" method="post" class="developer-form">
        <div class="form-group">
            <label for="developer_name">Name:</label>
            <input type="text" id="developer_name" name="developer_name" required placeholder="Developer Name">
        </div>
        <div class="form-group">
            <label for="developer_email">Email:</label>
            <input type="email" id="developer_email" name="developer_email" required placeholder="Developer Email">
        </div>
        <div class="form-group">
            <label for="developer_specialization">Specialization:</label>
            <select id="developer_specialization" name="developer_specialization">
                <option value="Frontend">Frontend</option>
                <option value="Backend">Backend</option>
                <option value="Full Stack">Full Stack</option>
                <option value="DevOps">DevOps</option>
                <option value="Data Scientist">Data Scientist</option>
            </select>
        </div>
        <div class="form-group">
            <label>Level:</label>
            <input type="radio" id="junior" name="developer_level" value="Junior" checked>
            <label for="junior">Junior</label>
            <input type="radio" id="mid_level" name="developer_level" value="Mid-level">
            <label for="mid_level">Mid-level</label>
            <input type="radio" id="senior" name="developer_level" value="Senior">
            <label for="senior">Senior</label>
        </div>
        <button type="submit" name="add_developer">Add Developer</button>
    </form>
    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>
</div>

<div style="margin: 20px;">
    <h2>Current Developers and Tasks</h2>
    <table border="1" style="width:100%; text-align:left;">
        <thead>
            <tr>
                <th>Developer Name</th>
                <th>Task Title</th>
                <th>Description</th>
                <th>Estimated Hours</th>
                <th>Attachment</th>
                <th>Completed</th>
                <th>Action</th> <!-- Column for actions like 'Delete Task' -->
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['dev_name']); ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td><?= htmlspecialchars($row['estimated_hours']); ?></td>
                        <td><?= !empty($row['attachment_path']) ? '<a href="'. htmlspecialchars($row['attachment_path']) .'" target="_blank">View</a>' : 'None'; ?></td>
                        <td>
                            <?php if (isset($row['completed']) && !$row['completed']): ?>
                                <form action="" method="post">
                                    <input type="hidden" name="task_id" value="<?= $row['task_id']; ?>">
                                    <input type="checkbox" name="mark_completed" title="Mark as completed">
                                    <button type="submit">Mark as Complete</button>
                                </form>
                            <?php else: ?>
                                Completed
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Delete button form -->
                            <form action="" method="post">
                                <input type="hidden" name="task_id" value="<?= $row['task_id']; ?>">
                                <button type="submit" name="delete_task" onclick="return confirm('Are you sure you want to delete this task?');">Delete Task</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No developers or tasks found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php
$con->close(); // It's a good practice to close the database connection when done.
include('footer.php');
?>
