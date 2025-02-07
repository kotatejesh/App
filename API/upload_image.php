<?php
include('db.php');
header("Content-Type: application/json");

$target_dir = "uploads/";
$base_url = "http://localhost/pdd/"; // Adjust to your actual server URL

// Create the folder if it doesn't exist
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Check if the request is for uploading or fetching images
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES["image"])) {
    // Image Upload Logic
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ["jpg", "jpeg", "png", "gif"];

    // Check if file type is allowed
    if (!in_array($imageFileType, $allowed_types)) {
        echo json_encode(["status" => "error", "message" => "Invalid file type. Only JPG, PNG, GIF allowed."]);
        exit;
    }

    // Move the uploaded file to the uploads folder
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Save image path to the database
        $image_path = $base_url . $target_file;
        $stmt = $conn->prepare("INSERT INTO uploadimage (image) VALUES (?)");
        $stmt->bind_param("s", $image_path);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "image_url" => $image_path]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database insert failed."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "File upload failed."]);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch Images Logic
    $sql = "SELECT `s_no`, `image` FROM `uploadimage`";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = ["s_no" => $row["s_no"], "image_url" => $row["image"]];
        }
        echo json_encode(["status" => "success", "images" => $images]);
    } else {
        echo json_encode(["status" => "error", "message" => "No images found."]);
    }
}

$conn->close();
?>
