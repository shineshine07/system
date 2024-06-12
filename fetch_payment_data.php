<?php
// fetch_payment_data.php

// Assuming you have a database connection already established

if (isset($_GET['id'])) {
    $paymentId = $_GET['id'];

    // Perform a database query to fetch payment data based on $paymentId
    // Replace the following line with your actual query
    $paymentData = fetchPaymentDataFromDatabase($paymentId);

    // Return the data in JSON format
    header('Content-Type: application/json');
    echo json_encode($paymentData);
} else {
    // Handle error if ID is not provided
    echo json_encode(['error' => 'Payment ID not provided']);
}

// Replace this function with your actual database query logic
function fetchPaymentDataFromDatabase($paymentId) {
    // Implement your database query logic here
    // Example: $result = mysqli_query($conn, "SELECT * FROM payments WHERE id = $paymentId");
    // Example: $paymentData = mysqli_fetch_assoc($result);
    // Remember to handle error cases and return data appropriately
    // Return an associative array representing the payment data
}
?>
