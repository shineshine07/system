<?php
include 'db_connect.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract form data
    $payee = $_POST['payee'];
    $cv = $_POST['cv'];
    $amount_in_words = $_POST['amount_in_words'];
    $type = $_POST['type'];
    $total = $_POST['total'];
    $account_title = $_POST['account_title'];
    $debit = $_POST['debit'];
    $credit = $_POST['credit'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO disbursement (cv, payee, amount_in_words, type, total, account_title, debit, credit) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdssd", $cv, $payee, $amount_in_words, $type, $total, $account_title, $debit, $credit);

    // Check if the query is successful
    if ($stmt->execute()) {
        // Set the success response
        $response = array('status' => 'success', 'message' => 'Disbursement record added successfully.');
    } else {
        // Set the error response
        $response = array('status' => 'error', 'message' => 'Error adding disbursement record.');
    }

    // Close the statement
    $stmt->close();

    // Return the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle invalid request method (not POST)
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid request method.';
}
?>
