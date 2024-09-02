<?php
session_start(); // Start the session

$host = "localhost";
$username = "root";
$password = "";
$dbname = "fyp";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $estimated_hours = filter_input(INPUT_POST, 'estimated_hours', FILTER_SANITIZE_NUMBER_INT);
        $developerName = filter_input(INPUT_POST, 'developer_name', FILTER_SANITIZE_STRING);

        $stmt = $conn->prepare("SELECT id, current_workload FROM developers WHERE name = :name");
        $stmt->execute([':name' => $developerName]);
        $developer = $stmt->fetch();

        if ($developer && ($developer['current_workload'] + $estimated_hours) <= 30) {
            $conn->beginTransaction();
            try {
                $insertTask = $conn->prepare("INSERT INTO tasks (title, estimated_hours, developer_id) VALUES (:title, :estimated_hours, :developer_id)");
                $insertTask->execute([':title' => $title, ':estimated_hours' => $estimated_hours, ':developer_id' => $developer['id']]);

                $updateWorkload = $conn->prepare("UPDATE developers SET current_workload = current_workload + :estimated_hours WHERE id = :developer_id");
                $updateWorkload->execute([':estimated_hours' => $estimated_hours, ':developer_id' => $developer['id']]);

                $conn->commit();

                $_SESSION['message'] = "Task '{$title}' assigned to developer: '{$developerName}' with an updated workload of " . ($developer['current_workload'] + $estimated_hours) . " hours.";
            } catch (Exception $e) {
                $conn->rollBack();
                $_SESSION['message'] = "Error: " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "Developer '{$developerName}' cannot take on this task without exceeding the 30-hour workload limit or not found.";
        }
        header('Location: userhome.php'); // Redirect to userhome.php
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message'] = "Connection failed: " . $e->getMessage();
    header('Location: userhome.php');
    exit();
}
?>
