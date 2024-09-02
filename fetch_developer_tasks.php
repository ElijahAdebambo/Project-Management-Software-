<?php

include 'connection.php';

header('Content-Type: application/json'); 

echo json_encode(['completedTasks' => 5, 'uncompletedTasks' => 3]);
exit;

