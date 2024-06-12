<?php
include 'db_connect.php';

if (isset($_POST['selectedParticulars'])) {
    // Handle payment history data
    $selectedParticulars = json_decode($_POST['selectedParticulars'], true);
    $student_id = $_POST['student_id'];
    $paid_amount = $_POST['paid_amount'];

    foreach ($selectedParticulars as $particular) {
        $description = $conn->real_escape_string($particular['description']);
        $amount = $particular['amount'];

        // Assuming you have a user ID or some identifier for the payment
        $user_id = 123; // Replace with the actual user ID or identifier

        $conn->query("INSERT INTO payment_history (user_id, student_id, description, amount, paid_amount) VALUES ('$user_id', '$student_id', '$description', '$amount', '$paid_amount')");
    }

    // Check if the insertion was successful
    if ($conn->affected_rows > 0) {
        $response = array('status' => 'success');
    } else {
        $response = array('status' => 'error');
    }

    // Send the response back to the JavaScript code
    echo json_encode($response);
}

// Close the database connection
$conn->close();
?>
