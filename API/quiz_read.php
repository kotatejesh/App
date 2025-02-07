<?php
include('db.php');  // Include the database connection file

// Set the content type to JSON
header('Content-Type: application/json');

// GET - Retrieve all quizzes
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // SQL query to fetch quizzes
    $sql = "SELECT id, title, description, category, difficulty, time_limit FROM quizz";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Initialize an array to hold the results
        $quizz = array();

        // Fetch all rows from the result
        while($row = $result->fetch_assoc()) {
            // Add each quiz to the array
            $quizz[] = $row;
        }

        // Return a success message along with the quizzes as a JSON response
        echo json_encode(array(
            "message" => "Records retrieved successfully.",
            "data" => $quizz
        ));
    } else {
        // If no records are found, return a message with an empty array
        echo json_encode(array(
            "message" => "No records found.",
            "data" => array()
        ));
    }
}
?>
