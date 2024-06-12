<?php

// Load the database configuration file
include('db_connect.php');

// Include PhpSpreadsheet library autoloader
require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (isset($_POST['importSubmit'])) {

    // Allowed mime types
    $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    // Validate whether the selected file is an Excel file
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)) {

        // If the file is uploaded
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $reader = new Xlsx();
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet_arr = $worksheet->toArray();

            // Remove header row
            array_shift($worksheet_arr);

            foreach ($worksheet_arr as $row) {
                $id_no = $row[0];
                $firstname = $row[1];
                $middlename = $row[2];
                $lastname = $row[3];
                $course = $row[4];
                $level = $row[5];
                $contact = $row[6];
                $address = $row[7];
                $email = $row[8];

                // Check whether student already exists in the database with the same email
                $prevQuery = "SELECT id FROM student WHERE id_no = ?";
                $stmt_prev = $conn->prepare($prevQuery);
                if ($stmt_prev === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                $stmt_prev->bind_param("s", $id_no);
                $stmt_prev->execute();
                $stmt_prev->store_result();

                if ($stmt_prev->num_rows > 0) {
                    // Update student data in the database
                    $stmt_update = $conn->prepare("UPDATE student SET id_no = ?, firstname = ?, middlename = ?, lastname = ?, course = ?, level = ?, contact = ?, address = ? WHERE email = ?");
                    if ($stmt_update === false) {
                        die('Prepare failed: ' . htmlspecialchars($conn->error));
                    }
                    $stmt_update->bind_param("sssssssss", $id_no, $firstname, $middlename, $lastname, $course, $level, $contact, $address, $email);
                    if (!$stmt_update->execute()) {
                        die('Error updating data: ' . htmlspecialchars($stmt_update->error));
                    }
                    $stmt_update->close();
                } else {
                    // Insert student data into the database
                    $stmt_insert = $conn->prepare("INSERT INTO student (id_no, firstname, middlename, lastname, course, level, contact, address, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    if ($stmt_insert === false) {
                        die('Prepare failed: ' . htmlspecialchars($conn->error));
                    }
                    $stmt_insert->bind_param("sssssssss", $id_no, $firstname, $middlename, $lastname, $course, $level, $contact, $address, $email);
                    if (!$stmt_insert->execute()) {
                        die('Error inserting data: ' . htmlspecialchars($stmt_insert->error));
                    }
                    $stmt_insert->close();
                }

                $stmt_prev->close();
            }

            $qstring = '?page=students&status=succ';
        } else {
            $qstring = '?page=students&status=err_upload';
        }
    } else {
        $qstring = '?page=students&status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: index.php".$qstring);
exit();
?>
