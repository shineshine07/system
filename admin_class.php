<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;
	private $conn;

	public function __construct() {
		ob_start();
		include 'db_connect.php';
		$this->conn = $conn; // Update this line
		$this->db = $conn;
	}
	function __destruct() {
		if ($this->conn && $this->conn->ping()) {
			$this->conn->close();
		}
		ob_end_flush();
	}

	function login(){
		extract($_POST);		
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function login2(){
		
		extract($_POST);		
		$qry = $this->db->query("SELECT * FROM complainants where email = '".$email."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user() {
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if (!empty($password)) {
			$data .= ", password = '".md5($password)."' ";
		}
		$data .= ", type = '$type' ";
		
		// Initialize $establishment_id
		$establishment_id = 0;
		
		if ($type == 1) {
			$establishment_id = 0;
		}
	
		$data .= ", establishment_id = '$establishment_id' ";
	
		// Check if the username already exists
		$chk = $this->db->query("SELECT * FROM users WHERE username = '$username' AND id != '$id' ")->num_rows;
		if ($chk > 0) {
			return array('status' => 2, 'message' => 'Username already exists');
		}
	
		// Continue with saving or updating the user
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users SET ".$data);
		} else {
			$save = $this->db->query("UPDATE users SET ".$data." WHERE id = ".$id);
		}
	
		if ($save) {
			return array('status' => 1, 'message' => 'Data successfully saved');
		} else {
			return array('status' => 0, 'message' => 'Failed to save data');
		}
	}
	
	
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", address = '$address' ";
		$data .= ", contact = '$contact' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * from complainants where email ='$email' ".(!empty($id) ? " and id != '$id' " : ''))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}
		if(empty($id))
			$save = $this->db->query("INSERT INTO complainants set $data");
		else
			$save = $this->db->query("UPDATE complainants set $data where id=$id ");
		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
				$qry = $this->db->query("SELECT * FROM complainants where id = $id ");
				if($qry->num_rows > 0){
					foreach ($qry->fetch_array() as $key => $value) {
						if($key != 'password' && !is_numeric($key))
							$_SESSION['login_'.$key] = $value;
					}
						return 1;
				}else{
					return 3;
				}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}

			return 1;
				}
	}
	function save_course() {
		global $conn;
	
		// Assuming you have a database connection object named $conn
		extract($_POST);
	
		// Assuming $id is available in your form, adjust accordingly
		$id = isset($id) ? $id : null;
		$total_amount = 0;
	
		// Start a transaction
		$conn->begin_transaction();
	
		try {
			       
			if ($id) {
				// Assuming you have a field named 'description' in your form
				$update_course_sql = "UPDATE courses SET description = '$fee_type' WHERE id = $id";
			} else {
				// Assuming you have a field named 'description' in your form
				$insert_course_sql = "INSERT INTO courses (description) VALUES ('$fee_type')";
				$conn->query($insert_course_sql);
	
				// Get the last inserted ID
				$id = $conn->insert_id;
			}
	
			// Example for updating or inserting fees
			if (isset($type) && isset($amount)) {
				foreach ($type as $key => $fee_type) {
					$fee_amount = is_numeric($amount[$key]) ? $amount[$key] : 0;
					$total_amount += $fee_amount;
	
					// Perform SQL operations (e.g., INSERT or UPDATE) for each fee record
					// Adjust the SQL query based on your database schema
	
					// Example (replace with your actual SQL query):
					if (isset($fid[$key]) && !empty($fid[$key])) {
						$update_fee_sql = "UPDATE fees SET description = '$fee_type', amount = $fee_amount WHERE id = " . $fid[$key];
						$conn->query($update_fee_sql);
					} else {
						$insert_fee_sql = "INSERT INTO fees (course_id, description, amount) VALUES ($id, '$fee_type', $fee_amount)";
						$conn->query($insert_fee_sql);
					}
				}
	
			
				$update_total_sql = "UPDATE courses SET total_amount = $total_amount WHERE id = $id";
				$conn->query($update_total_sql);
			}
	
			// Commit the transaction
			$conn->commit();
	
			$response = array('status' => 1, 'message' => 'Data successfully saved.');
		} catch (Exception $e) {
			// Rollback the transaction in case of an error
			$conn->rollback();
			$response = array('status' => 0, 'message' => 'Error saving data.');
		}
	
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	function delete_course(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM courses where id = ".$id);
		$delete2 = $this->db->query("DELETE FROM fees where course_id = ".$id);
		if($delete && $delete2){
			return 1;
		}
	}
	
	
	function save_student(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM student where id_no ='$id_no' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO student set $data");
		}else{
			$save = $this->db->query("UPDATE student set $data where id = $id");
		}
		if($save)
			return 1;
	}
	function delete_student(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM student where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_fee() {
		global $conn;
	
		$response = array();
	
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$description = isset($_POST['description']) ? $_POST['description'] : '';
		$amount = isset($_POST['amount']) ? $_POST['amount'] : '';
	
		// Validate input if needed
	
		// Perform the save or update operation
		if ($id) {
			// Update existing fee
			$sql = "UPDATE fees SET description = '$description', amount = '$amount' WHERE id = $id";
		} else {
			// Insert new fee
			$sql = "INSERT INTO fees (description, amount) VALUES ('$description', '$amount')";
		}
	
		if ($conn->query($sql) === TRUE) {
			$response['status'] = 1;
			$response['message'] = 'Fee saved successfully.';
		} else {
			$response['status'] = 0;
			$response['message'] = 'Error saving fee: ' . $conn->error;
		}
	
		echo json_encode($response);
		exit;
	}
	
	function delete_fees(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM fees where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_payment() {
		extract($_POST);

		$or_number = $_POST['or_number'];
	
		// Assuming you have a 'student' table with a 'name' column
		$student_id = $_POST['student_id'];
		$paid_amount = $_POST['paid_amount'];
	
		// Additional data
		$total_amount = $_POST['total_amount']; // Add this line to get the total amount
	
		// Fee details data
		$fee_details = json_decode($_POST['fee_details'], true);
	
		$payment_id = 0; // Initialize payment_id
		
	
		// Check if a payment record already exists for the student
		$existingPaymentQuery = "SELECT id, student_id, paid_amount,or_number, total_amount, balance FROM payments WHERE student_id = '$student_id'";
		$resultExistingPayment = $this->conn->query($existingPaymentQuery);
	
		if ($resultExistingPayment->num_rows > 0) {
			// Update the existing payment record
			$existingPaymentData = $resultExistingPayment->fetch_assoc();
			$existingPaymentId = $existingPaymentData['id'];
			$existingPaidAmount = $existingPaymentData['paid_amount'];
			$existingTotalAmount = $existingPaymentData['total_amount'];
			$existingBalance = $existingPaymentData['balance'];
	
			// Calculate the updated amounts
			$updatedPaidAmount = $existingPaidAmount + $paid_amount;
			$updatedBalance = $existingTotalAmount - $updatedPaidAmount;
	
			$updatePaymentQuery = "UPDATE payments SET paid_amount = '$updatedPaidAmount', balance = '$updatedBalance' WHERE id = '$existingPaymentId'";
			if ($this->conn->query($updatePaymentQuery) !== TRUE) {
				$response = array('status' => 0, 'message' => 'Error updating payment: ' . $this->conn->error);
			} else {
				$payment_id = $existingPaymentId; // Set payment_id
				$response = array('status' => 1, 'message' => 'Payment updated successfully', 'payment_id' => $payment_id, 'updated_balance' => $updatedBalance);
			}
		} else {
			// Insert a new payment record
			$initialBalance = $total_amount - $paid_amount;
	
			$sql_payment = "INSERT INTO payments (student_id, paid_amount, or_number, total_amount, balance) VALUES ('$student_id', '$paid_amount', '$or_number', '$total_amount', '$initialBalance')";
	
			// Perform the insertion and get the payment ID
			if ($this->conn->query($sql_payment) === TRUE) {
				$payment_id = $this->conn->insert_id; // Set payment_id
				$response = array('status' => 1, 'message' => 'Payment saved successfully', 'payment_id' => $payment_id, 'updated_balance' => $initialBalance);
			} else {
				$response = array('status' => 0, 'message' => 'Error saving payment: ' . $this->conn->error);
			}
		}
	
		// If payment was successful, insert fee details
		if ($response['status'] === 1) {
			foreach ($fee_details as $fee) {
				$fee_id = $fee['id'];
				$amount = $fee['amount'];
	
				// Fetch the fee description from the 'fees' table\
				$get_fee_info_sql = "SELECT id, description FROM fees WHERE id = '$fee_id'";
				$result = $this->conn->query($get_fee_info_sql);
	
				if ($result !== false && $result->num_rows > 0) {
					$fee_info = $result->fetch_assoc();
					$fee_description = $fee_info['description'];
				} else {
					// If fee information is not found in 'fees' table, set default values or handle as needed
					$fee_description = " $fee_id";
				}
	
				// Perform the database insertion for fee details (adjust table/column names as needed)
				$sql_fee_details = "INSERT INTO fee_details (student_id, payment_id, fee_id, fee_description, amount) VALUES ('$student_id', '$payment_id', '$fee_id', '$fee_description', '$amount')";
	
				// Perform the insertion
				if ($this->conn->query($sql_fee_details) !== TRUE) {
					// Handle the error if fee details insertion fails
					$response['message'] .= ' Error saving fee details: ' . $this->conn->error;
				}
			}
		}
	
		$response['debug'] = array(
			'student_id' => $student_id,
			'fee_details' => $fee_details
		);
	
		// Return the response as JSON
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	
	
		
	function delete_payment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function archiveDisbursement($id) {
		global $conn;
		$updateQuery = "UPDATE disbursement SET archived = 1 WHERE id = $id";
		$result = $conn->query($updateQuery);
	
		if ($result) {
			return true; // Success
		} else {
			return 'Error: ' . $conn->error; // Error message
		}
	}

    function unarchiveDisbursement($id) {
        global $conn;
        $updateQuery = "UPDATE disbursement SET archived = 0 WHERE id = $id";

        if ($conn->query($updateQuery)) {
            return true; // Success
        } else {
            return 'Error: ' . $conn->error; // Error message
        }
    }

	function getArchivedDisbursements() {
		global $conn;
	
		// Replace this query with your actual query to fetch archived disbursements
		$query = "SELECT * FROM disbursement WHERE archived = 1 ORDER BY cv ASC";
		$result = $conn->query($query);
	
		$disbursements = [];
		while ($row = $result->fetch_assoc()) {
			$disbursements[] = $row;
		}
	
		return $disbursements;
	}

	function save_disbursement() {
		global $conn;
	
		$response = array();
	
		$payee = isset($_POST['payee']) ? $_POST['payee'] : '';
		$cv = isset($_POST['cv']) ? $_POST['cv'] : '';
		$amount_in_words = isset($_POST['amount_in_words']) ? $_POST['amount_in_words'] : '';
		$type = isset($_POST['type']) ? $_POST['type'] : '';
		$total = isset($_POST['total']) ? $_POST['total'] : '';
		$account_title = isset($_POST['account_title']) ? $_POST['account_title'] : '';
		$debit = isset($_POST['debit']) ? $_POST['debit'] : '';
		$credit = isset($_POST['credit']) ? $_POST['credit'] : '';
	
		// Validate input if needed
	
		// Perform the save or update operation
		if (!empty($cv)) {
			// Update existing disbursement
			$stmt = $conn->prepare("UPDATE disbursement SET
				payee = ?,
				amount_in_words = ?,
				type = ?,
				total = ?,
				account_title = ?,
				debit = ?,
				credit = ?
				WHERE cv = ?");
			$stmt->bind_param("ssssddds", $payee, $amount_in_words, $type, $total, $account_title, $debit, $credit, $cv);
		} else {
			// Insert new disbursement
			$stmt = $conn->prepare("INSERT INTO disbursement (payee, amount_in_words, type, total, account_title, debit, credit) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("ssssdds", $payee, $amount_in_words, $type, $total, $account_title, $debit, $credit);
		}
	
		if ($stmt->execute()) {
			$response['status'] = 1;
			$response['message'] = 'Disbursement saved successfully.';
		} else {
			$response['status'] = 0;
			$response['message'] = 'Error saving disbursement: ' . $stmt->error;
		}
	
		// Close the statement
		$stmt->close();
	
		// Return JSON response
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	}

	function archivePayment($id) {
		global $conn;
		$updateQuery = "UPDATE payments SET archived = 1 WHERE id = $id";
		$result = $conn->query($updateQuery);
	
		if ($result) {
			return true; // Success
		} else {
			return 'Error: ' . $conn->error; // Error message
		}
	}
	
	function unarchivePayment($id) {
		global $conn;
		$updateQuery = "UPDATE payments SET archived = 0 WHERE id = $id";
	
		if ($conn->query($updateQuery)) {
			return true; // Success
		} else {
			return 'Error: ' . $conn->error; // Error message
		}
	}
	
	function getArchivedPayment() {
		global $conn;
	
		// Replace this query with your actual query to fetch archived payments
		$query = "SELECT * FROM payments WHERE archived = 1 ORDER BY student_id ASC";
		$result = $conn->query($query);
	
		$payments = [];
		while ($row = $result->fetch_assoc()) {
			$payments[] = $row;
		}
	
		return $payments;
	}
	
	public function save_others_fee()
	{
		if (isset($_POST['action']) && $_POST['action'] == 'save_others_fee') {
			// Retrieve the particular and amount from the POST data
			$particular = $_POST['particular'];
			$amount = $_POST['amount'];
	
			// Insert the data into the fees table
			include 'db_connect.php'; // Assuming this file contains your database connection code
	
			$query = "INSERT INTO fees (description, amount) VALUES (?, ?)";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ss", $particular, $amount);
			$stmt->execute();
	
			if ($stmt->affected_rows > 0) {
				echo json_encode(array('status' => 1, 'message' => 'Fee saved successfully'));
			} else {
				echo json_encode(array('status' => 0, 'message' => 'Failed to save fee'));
			}
	
			$stmt->close();
			$conn->close();
		} else {
			echo json_encode(array('status' => 0, 'message' => 'Invalid action'));
		}
	}

    public function getBalance($student_id) {
		$stmt = $this->conn->prepare("SELECT balance FROM payments WHERE student_id = ?");
		$stmt->bind_param("i", $student_id);
		$stmt->execute();
	
		$result = $stmt->get_result();
	
		if ($row = $result->fetch_assoc()) {
			$balance = $row['balance'];
			$stmt->close(); // Close the statement before returning
			return $balance;
		} else {
			$stmt->close(); // Close the statement before returning
			return null; // Student or balance not found
		}
	}
	
	
}
