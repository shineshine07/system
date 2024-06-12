<?php
ob_start();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
include 'admin_class.php';
include 'db_connect.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if ($action == 'save_user') {
    $save = $crud->save_user();
    echo json_encode($save);
    exit();  // Add this line to stop further output
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'update_account'){
	$save = $crud->update_account();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
// ajax.php

if ($action == 'save_course') {
    $save_course = $crud->save_course();
    if ($save_course === 1) {
        echo json_encode(array('status' => 1, 'message' => 'Fee saved successfully'));
    } elseif ($save_course === 2) {
        echo json_encode(array('status' => 2, 'message' => 'Fee already exists'));
    }

    // Exit after sending the response to prevent further output
    exit();
}
 

if ($action == "delete_course") {
    $delete = $crud->delete_course();
    $response = array();

    if ($delete) {
        $response['status'] = 1;
        $response['message'] = 'Data successfully deleted';
    } else {
        $response['status'] = 0;
        $response['message'] = 'Error deleting data';
    }

    echo json_encode($response);
}
if ($action == 'save_student') {
    $save_student = $crud->save_student($_POST);
    if ($save_student === 1) {
    echo json_encode(array('status' => 1, 'message' => 'Student saved successfully'));
} elseif ($save_student === 2) {
    echo json_encode(array('status' => 2, 'message' => 'ID number already exists'));
}

    
    // Exit after sending the response to prevent further output
    exit();
}

if($action == "delete_student"){
	$delete = $crud->delete_student();
	if($delete)
		echo $delete;
}
if($action == "save_fee"){
	$save = $crud->save_fee();
	if($save)
		echo $save;
}
if($action == "delete_fees"){
	$delete = $crud->delete_fees();
	if($delete)
		echo $delete;
}

if ($action == 'save_payment') {
    $save_payment = $crud->save_payment($_POST);  // Pass the form data to the method
    if ($save_payment === 1) {
        echo json_encode(array('status' => 1, 'message' => 'Payment saved successfully'));
    } elseif ($save_payment === 2) {
        echo json_encode(array('status' => 2, 'message' => 'Payment already exists'));
    }

    // Exit after sending the response to prevent further output
    exit();
}

// Handle other actions if needed
if($action == "delete_payment"){
	$delete = $crud->delete_payment();
	if($delete)
		echo $delete;
}
if($action == "delete_disbursement"){
	$delete = $crud->delete_disbursement();
	if($delete)
		echo $delete;
}
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'archive_disbursement':
            archiveDisbursement($_POST['id']);
            break;
        // Add other cases if needed
    }
}

function archiveDisbursement($id)
{
    global $conn;
    $query = $conn->prepare("UPDATE disbursement SET archived = 1 WHERE id = ?");
    $query->bind_param('i', $id);

    $response = array(); // Initialize response array
    
    if ($query->execute()) {
        $response['status'] = 1;
        $response['message'] = 'Disbursement archived successfully.';
        $response['data'] = array(); // You can include data if needed
    } else {
        $response['status'] = 0;
        $response['message'] = 'Failed to archive disbursement.';
        $response['error'] = $query->error; // Include the error message
    }

    // Set Content-Type header
    header('Content-Type: application/json');

    // Output the response as JSON
    echo json_encode($response);
    exit;
}


if (isset($_POST['action']) && $_POST['action'] == 'unarchive_disbursement') {
    $disbursementId = $_POST['disbursement_id'];

    // Perform the update to set 'archived' to 0 (unarchived)
    $updateQuery = "UPDATE disbursement SET archived = 0 WHERE id = $disbursementId";
    if ($conn->query($updateQuery) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    exit;
}


if($action == "save_disbursement"){
	$save = $crud->save_disbursement();
	if($save)
		echo $save;

}
if($action == "save_others_fee"){
	$save = $crud->save_others_fee();
	if($save)
		echo $save;

}
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'archive_payment':
            archivePayment($_POST['id']);
            break;
        // Add other cases if needed
    }
}


function archivePayment($id)
{
    global $conn;
    $query = $conn->prepare("UPDATE payments SET archived = 1 WHERE id = ?");
    $query->bind_param('i', $id);

    $response = array(); // Initialize response array
    
    if ($query->execute()) {
        $response['status'] = 1;
        $response['message'] = 'Payment archived successfully.';
        $response['data'] = array(); // You can include data if needed
    } else {
        $response['status'] = 0;
        $response['message'] = 'Failed to archive payment.';
        $response['error'] = $query->error; // Include the error message
    }

    // Set Content-Type header
    header('Content-Type: application/json');

    // Output the response as JSON
    echo json_encode($response);
    exit;
}
if (isset($_POST['action']) && $_POST['action'] == 'unarchive_payment') {
    $paymentsId = $_POST['payments_id'];

    // Perform the update to set 'archived' to 0 (unarchived)
    $updateQuery = "UPDATE payments SET archived = 0 WHERE id = $paymentsId";
    if ($conn->query($updateQuery) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    exit;
}
if (isset($_POST['action']) && $_POST['action'] == 'get_payment_details') {
    $paymentId = $_POST['id'];

    // Fetch payment details from the payments table
    $payment_query = $conn->query("SELECT * FROM payments WHERE id = $paymentId");
    $payment = $payment_query->fetch_assoc();

    if ($payment) {
        $studentId = $payment['student_id'];

        // Fetch student details from the students table
        $student_query = $conn->query("SELECT s.name as sname, s.id_no, s.course, s.level, p.total_amount FROM student s INNER JOIN payments p ON s.id = p.student_id WHERE s.id = $studentId");
        $student = $student_query->fetch_assoc();

        $paid = $payment['paid_amount'];
        $balance = $student['total_amount'] - $paid;

        // Return payment and student details as JSON
        echo json_encode(['status' => 1, 'paymentDetails' => $payment, 'studentDetails' => $student, 'balance' => $balance]);
    } else {
        // Return an error message
        echo json_encode(['status' => 0, 'message' => 'Failed to fetch payment details.']);
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'search_students') {
    $searchTerm = isset($_POST['term']) ? $_POST['term'] : '';

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, CONCAT_WS(' ', firstname, middlename, lastname) AS name, id_no, level, course FROM student WHERE CONCAT_WS(' ', firstname, middlename, lastname) LIKE ? OR id_no LIKE ? ORDER BY name ASC");
    $searchTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();

    // Fetch the results
    $result = $stmt->get_result();

    // Create an array to store the results
    $students = array();

    // Fetch each row and add it to the array
    while ($row = $result->fetch_assoc()) {
        $students[] = array(
            'id' => $row['id'],
            'text' => ucwords($row['name']) . ' | ' . $row['id_no']
        );
    }
    // Close the database connection
    $stmt->close();
    // Return the results in JSON format
    echo json_encode($students);
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    if ($_POST["action"] == "get_balance" && isset($_POST["student_id"])) {
        $studentId = $_POST["student_id"];
        $isChecked = isset($_POST["is_checked"]) && $_POST["is_checked"] == "true";

        // Add your logic to get the balance based on the student ID
        // Perform necessary database queries

        // For example, assuming you have a 'payments' table
        $query = "SELECT balance FROM payments WHERE student_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $stmt->bind_result($balance);
        $stmt->fetch();
        $stmt->close();

        // Simulate some processing or calculation for the balance (replace this with your actual logic)
        if ($isChecked) {
            $balance;  // Add some value for demonstration purposes
        }

        // Respond with the balance
        echo json_encode(["status" => 1, "balance" => $balance]);
    } elseif ($action == "save_payment_history" && isset($_POST["student_id"]) && isset($_POST["particulars"])) {
        $studentId = $_POST["student_id"];
        $selectedParticulars = $_POST["particulars"];
    
        foreach ($selectedParticulars as $particular) {
            // Assuming 'description' is present in each particular
            $particularDescription = trim($particular["description"]);
    
            // Retrieve additional information from fees table based on description
            $selectFeeQuery = "
                SELECT f.id, f.description, f.amount
                FROM fees f
                WHERE TRIM(f.description) = TRIM(?)
            ";
            $selectStmt = $conn->prepare($selectFeeQuery);
            $selectStmt->bind_param("s", $particularDescription);
            $selectStmt->execute();
            $selectStmt->bind_result($feeId, $feeDescription, $feeAmount);
    
            // Check if a record is found
            if ($selectStmt->fetch()) {
                $selectStmt->close();
    
                // Insert into payment_history table
                $insertQuery = "
                    INSERT INTO payment_history (student_id, fee_id, fee_description, amount)
                    VALUES (?, ?, ?, ?)
                ";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("iisd", $studentId, $feeId, $feeDescription, $particular["amount"]);
                $stmt->execute();
                $stmt->close();
            } else {
                $selectStmt->close();
    
                // Handle the case when the fee information is not found for the given description
                $errorMessage = "Fee information not found for description: " . $particularDescription;
                echo json_encode(["status" => 0, "message" => $errorMessage]);
            }
        }
    
        // Return a response
        echo json_encode(["status" => 1, "message" => "Payment history saved successfully"]);
    

    } elseif ($_POST["action"] == "get_payment_history" && isset($_POST["student_id"])) {
        $studentId = $_POST["student_id"];
    
        // Fetch payment history based on student_id excluding records with matching student_id, fee_description, and amount in fee_details
        $query = "SELECT ph.fee_description, ph.amount
                  FROM payment_history ph
                  LEFT JOIN fee_details fd ON 
                      ph.student_id = fd.student_id AND
                      ph.fee_description = fd.fee_description AND
                      ph.amount = fd.amount
                  WHERE ph.student_id = ? AND fd.student_id IS NULL";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $paymentHistory = array();
    
        while ($row = $result->fetch_assoc()) {
            $paymentHistory[] = array(
                'fee_description' => $row['fee_description'],
                'amount' => $row['amount']
            );
        }
        
        $stmt->close();
        
        echo json_encode(array('status' => 1, 'payment_history' => $paymentHistory));
        exit();
    }
     else {
        echo json_encode(["status" => 0, "message" => "Invalid request"]);
    }

    if ($_POST["action"] == "save_payment_history_to_fee_details" && isset($_POST["student_id"]) && isset($_POST["selected_items"])) {
    $studentId = $_POST["student_id"];
    $selectedItems = json_decode($_POST["selected_items"], true);

    // Loop through the selected items and save them into fee_details
    foreach ($selectedItems as $item) {
        $feeId = $item["fee_id"];

        // Check if the fee_id is already present in fee_details
        $checkFeeExistsQuery = "
            SELECT 1
            FROM fee_details
            WHERE student_id = ? AND fee_id = ?
            LIMIT 1
        ";

        $stmtCheckFeeExists = $conn->prepare($checkFeeExistsQuery);
        $stmtCheckFeeExists->bind_param("ii", $studentId, $feeId);
        $stmtCheckFeeExists->execute();
        $stmtCheckFeeExists->store_result();
        $feeExists = $stmtCheckFeeExists->num_rows > 0;
        $stmtCheckFeeExists->close();

        // If the fee_id is not present in fee_details, proceed with insertion
        if (!$feeExists) {
            // Fetch fee_description from payment_history table based on fee_id
            $selectPaymentQuery = "
                SELECT fee_description, amount
                FROM payment_history
                WHERE student_id = ? AND fee_id = ?
                LIMIT 1
            ";

            $stmtPayment = $conn->prepare($selectPaymentQuery);
            $stmtPayment->bind_param("ii", $studentId, $feeId);
            $stmtPayment->execute();
            $stmtPayment->bind_result($feeDescription, $amount);

            // Check if a record is found
            if ($stmtPayment->fetch()) {
                $stmtPayment->close();

                // Insert into fee_details table
                $insertQuery = "
                    INSERT INTO fee_details (student_id, fee_id, fee_description, amount)
                    VALUES (?, ?, ?, ?)
                ";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("iisd", $studentId, $feeId, $feeDescription, $amount);
                $stmt->execute();

                if ($stmt->errno) {
                    // If an error occurs, display or log the error message and terminate the script (or handle it as appropriate)
                    die("Error: " . $stmt->error);
                }

                $stmt->close();
            } else {
                $stmtPayment->close();

                // Handle the case when the fee information is not found for the given fee_id
                $errorMessage = "Fee information not found for fee_id: " . $feeId;
                echo json_encode(["status" => 0, "message" => $errorMessage]);
            }
        }
    }

    // Respond with a success message
    echo json_encode([
        "status" => 1,
        "message" => "Data saved successfully",
        "selected_items" => $selectedItems,
        "student_id" => $studentId,
    ]);
}
    

    // Add a new condition to handle fetching data for the dropdown
if ($_POST['action'] == "get_combined_particulars") {
    $studentId = $_POST['student_id'];

    // Fetch data from the initial dropdown (fees table)
    $fees = $conn->query("SELECT id, description, amount FROM fees ORDER BY description ASC");
    $feeData = [];
    while ($fee = $fees->fetch_assoc()) {
        $feeData[] = [
            'id' => $fee['id'],
            'description' => $fee['description'],
            'amount' => $fee['amount']
        ];
    }

    // Fetch data from the payment history table
    $paymentHistory = $conn->prepare("SELECT fee_id, fee_description AS description, amount FROM payment_history WHERE student_id = ? ORDER BY fee_description ASC");
    $paymentHistory->bind_param("i", $studentId);
    $paymentHistory->execute();
    $result = $paymentHistory->get_result();
    $paymentHistoryData = [];
    while ($history = $result->fetch_assoc()) {
        $paymentHistoryData[] = [
            'id' => $history['fee_id'],  // Assuming fee_id is the corresponding ID in fees table
            'description' => $history['description'],
            'amount' => $history['amount']
        ];
    }

    // Combine and send the data to the client
    $combinedData = array_merge($feeData, $paymentHistoryData);

    echo json_encode(['status' => 1, 'particulars' => $combinedData]);
    exit;
}
}

    
// Close the database connection
$conn->close();



ob_end_flush();
?>