<?php
include 'connection.php';

// Define  arrays
$tasksBySpecialization = [
    'Backend' => [],
    'Frontend' => [],
    'Full Stack' => [],
    'Data Scientist' => []
];

// Fetch tasks and their assigned developers
$query = "SELECT t.*, d.name, d.specialization FROM tasks t LEFT JOIN developers d ON t.developer_id = d.id";
$result = $con->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Check if the task is assigned to a developer and set a placeholder if not
        $specialization = $row['specialization'] ?? 'Unknown'; // Fallback 
        $row['name'] = $row['name'] ?? 'Unassigned'; // Set 'Unassigned' if no developer is linked
        $tasksBySpecialization[$specialization][] = $row;
    }
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Calendar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
      .container {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
            background-color: #c8d5dd;
        }
        .section {
            background-color: #ffffff;
            width: 80%;
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .section h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .note-input {
            width: 90%;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="header" style="background-color: #c8d5dd;">
    <div class="navcontainer">
        <div class="header-right">
        <a href="adminhome.php">Admin Dashboard</a>  
           <a href="userhome.php">User Dashboard</a>    
            <a href="workitemanalytics.php">Analytics</a>
		<a href="Calender.php">Calender</a>
		
            <a href="registeredaccounts.php">Registered Accounts</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="header-bottom">
            <h2>Developer Pipeline</h2>
        </div>
    </div>
</div>

<center><h3>The user <b><?php echo $_SESSION["username"] ?? 'Guest'; ?></b> is logged in</h3></center>

<div class="container">
    <!-- Backend Work Section -->
    <div class="section" id="backend">
        <h2>Backend Work</h2>
        <table>
            <tr>
                <th>Task</th>
                <th>Assigned To</th>
                <th>Notes</th>
                <th>Attach File</th>
            </tr>
            <?php foreach ($tasksBySpecialization['Backend'] as $task): ?>
            <tr>
                <td><?php echo htmlspecialchars($task['title']); ?></td>
                <td><?php echo htmlspecialchars($task['name']); ?></td>
                <td>
                    <input class="note-input" type="text" placeholder="Add note..." />

                </td>
                <td>
                    <button type="button" onclick="attachFile(<?php echo $task['id']; ?>);">Attach</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
    <div class="section" id="ux">
    <h2>UX Work</h2>
    <table>
        <tr>
            <th>Task</th>
            <th>Assigned To</th>
            <th>Notes</th>
            <th>Attach File</th>
        </tr>
        <?php foreach ($tasksBySpecialization['Frontend'] as $task): ?>
        <tr>
            <td><?php echo htmlspecialchars($task['title']); ?></td>
            <td><?php echo htmlspecialchars($task['name']); ?></td>
            <td>
                <input class="note-input" type="text" placeholder="Add note..." />
            </td>
            <td>
                    <button type="button" onclick="attachFile(<?php echo $task['id']; ?>);">Attach</button>
                </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
    
<div class="section" id="ready-for-testing">
    <h2>Ready for Testing</h2>
    <table>
        <tr>
            <th>Task</th>
            <th>Assigned To</th>
            <th>Notes</th>
            <th>Attach File</th>
        </tr>
        <?php 
            $readyForTestingTasks = array_merge(
                $tasksBySpecialization['Full Stack'], 
                $tasksBySpecialization['Data Scientist']
            );
            foreach ($readyForTestingTasks as $task): 
        ?>
        <tr>
            <td><?php echo htmlspecialchars($task['title']); ?></td>
            <td><?php echo htmlspecialchars($task['name']); ?></td>
            <td>
                <input class="note-input" type="text" placeholder="Add note..." />
            </td>
            <td>
                    <button type="button" onclick="attachFile(<?php echo $task['id']; ?>);">Attach</button>
                </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</div>

</body>
<?php include('footer.php'); ?>
</html>
