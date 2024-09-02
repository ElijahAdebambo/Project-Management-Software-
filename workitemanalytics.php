<?php
$host = "localhost";
$username = "root";
$password = "";
$db = "fyp";

$con = new mysqli($host, $username, $password, $db);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

session_start();

// Redirect to the login page if the user is not logged in
if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit;
}

// Fetch the total number of tasks per developer
$tasksPerDeveloper = [];
$developerQuery = "SELECT developers.name, COUNT(tasks.id) as totalTasks FROM developers LEFT JOIN tasks ON developers.id = tasks.developer_id GROUP BY developers.id";
$developerResult = $con->query($developerQuery);

while ($row = $developerResult->fetch_assoc()) {
    $tasksPerDeveloper[] = $row;
}

// Fetch all developers for dropdown
$developers = [];
$developersQuery = "SELECT id, name FROM developers ORDER BY name";
$developersResult = $con->query($developersQuery);

while ($dev = $developersResult->fetch_assoc()) {
    $developers[] = $dev;
}


// Encode the data for use in JavaScript
$tasksPerDeveloperJson = json_encode($tasksPerDeveloper);

// Prepare an empty array for population 
$developerTasksJson = json_encode([]);

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            background-color: #c8d5dd;
        }
        .chart-container {
            background-color: #ffffff;
            width: 60%;
            margin: 20px;
        }
        .advice-container {
            width: 30%;
            margin: 20px;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .chart-canvas {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body style="background-color:#c8d5dd;">

<div class="header" style="background-color: #c8d5dd;">
    <div class="navcontainer">
        <div class="header-right">
        <a href="adminhome.php">Admin Dashboard</a>  
           <a href="userhome.php">User Dashboard</a>    
            <a href="calender.php">Calender</a>
		<a href="developerpipeline.php">Pipeline</a>
		
            <a href="registeredaccounts.php">Registered Accounts</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="header-bottom">
            <h2>Work Item View </h2>
        </div>
    </div>
</div>

<center><h3><b><?php echo $_SESSION["username"]; ?> </b>is logged in</h3></center>


<div class="container">
    <div class="chart-container">
    <h3>Total Tasks per Developer</h3>
        <canvas id="tasksPerDeveloperChart" class="chart-canvas"></canvas>
    </div>

    <div class="advice-container">
        <h4>Management Advice</h4>
        <?php
        foreach ($tasksPerDeveloper as $developer) {
            if ($developer['totalTasks'] > 10) {
                echo "<p><b>{$developer['name']}</b>: High workload, avoid assigning more tasks.</p>";
            } elseif ($developer['totalTasks'] > 5) {
                echo "<p><b>{$developer['name']}</b>: Medium workload, assign tasks cautiously.</p>";
            } else {
                echo "<p><b>{$developer['name']}</b>: Low workload, can take on more tasks.</p>";
            }
        }
        ?>
    </div>
</div>






<script>
    var tasksPerDeveloper = JSON.parse('<?php echo $tasksPerDeveloperJson; ?>');

    // Initialize the chart for tasks per developer
    var ctxTasksPerDeveloper = document.getElementById('tasksPerDeveloperChart').getContext('2d');
    var tasksPerDeveloperChart = new Chart(ctxTasksPerDeveloper, {
        type: 'bar',
        data: {
            labels: tasksPerDeveloper.map(function(dev) { return dev.name; }),
            datasets: [{
                label: 'Total Tasks',
                data: tasksPerDeveloper.map(function(dev) { return dev.totalTasks; }),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: false, 
            maintainAspectRatio: false, 
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        max: 15, 
                    },
                    title: {
                        display: true,
                        text: 'Amount of Tasks' 
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Developer Name' 
                    }
                }
            },
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
</script>



<div class="select-container" style="margin-top: 20px;">

    <label for="developerSelect">Select Developer:</label>
    <select id="developerSelect" onchange="updateDeveloperTaskChart()">
        <option value="">Select Developer</option>
        <?php foreach ($developers as $developer): ?>
            <option value="<?php echo $developer['id']; ?>"><?php echo $developer['name']; ?></option>
        <?php endforeach; ?>
    </select>
</div>

  <div class="chart-container">
  <h3>Developer completed vs uncompleted tasks</h3>
        <canvas id="developerTaskChart" class="chart-canvas"></canvas>
    </div>

<script>
      
function updateDeveloperTaskChart() {
    var developerId = document.getElementById('developerSelect').value;
    if (!developerId) {
        return; 
    }

    fetch(`fetch_developer_tasks.php?developerId=${developerId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const ctx = document.getElementById('developerTaskChart').getContext('2d');
        if (window.developerTaskChart instanceof Chart) {
            window.developerTaskChart.destroy(); // Destroy the previous chart instance if exists
        }
        window.developerTaskChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Completed Tasks', 'Uncompleted Tasks'],
                datasets: [{
                    data: [data.completedTasks, data.uncompletedTasks], // Use the fetched data
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false
            }
        });
    })
    .catch(error => {
        console.error('Error during fetch operation:', error);
    });
}
</script>
<?php include('footer.php'); ?>

</body>
</html>
