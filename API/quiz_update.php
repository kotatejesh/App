<?php
include('db.php');  // Include the database connection file

// Set the content type to JSON
header('Content-Type: application/json');

// Update record if POST request and 'id' parameter are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $category = $_POST['category'] ?? null;
    $difficulty = $_POST['difficulty'] ?? null;
    $time_limit = $_POST['time_limit'] ?? null;

    // Ensure required fields are provided
    if ($title && $description && $category && $difficulty && $time_limit) {
        // Prepare SQL statement to update the quiz record
        $sql = "UPDATE quizz SET title = '$title', description = '$description', category = '$category', difficulty = '$difficulty', time_limit = '$time_limit' WHERE id = $id";

        // Execute the SQL query
        if ($conn->query($sql)) {
            // Fetch and return the updated record
            $result = $conn->query("SELECT * FROM quizz  WHERE id = $id");
            if ($result->num_rows > 0) {
                // Send a JSON response with the updated record
                echo json_encode([
                    "message" => "Record updated successfully.",
                    "updatedRecord" => $result->fetch_assoc()
                ]);
            } else {
                // If no record is found after the update
                echo json_encode([
                    "message" => "Error: Record not found after update."
                ]);
            }
        } else {
            // If an error occurs with the query
            echo json_encode([
                "message" => "Error: " . $conn->error
            ]);
        }
    } else {
        // If required fields are missing or invalid
        echo json_encode([
            "message" => "Invalid input or missing required fields."
        ]);
    }
}
?>
