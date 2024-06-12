<?php
include 'db_connect.php';

// Fetch data from payments table for a specific payment_id
$payments = $conn->query("
SELECT 
    p.id, 
    p.total_amount, 
    p.paid_amount,  -- Add this line to fetch paid_amount
    p.date_created, 
    p.remarks, 
    s.id_no, 
    CONCAT_WS(' ', s.firstname, s.middlename, s.lastname) AS name, 
    s.course, 
    s.level, 
    fd.fee_description, 
    fd.amount as fee_amount 
FROM payments p 
LEFT JOIN student s ON p.student_id = s.id
LEFT JOIN fee_details fd ON p.id = fd.payment_id
WHERE p.id = {$_GET['id']}
");

$pay_arr = array();
while ($row = $payments->fetch_assoc()) {
    $row['fee_description'] = htmlspecialchars($row['fee_description']);
    $pay_arr[] = $row;
}

// Assuming you have a student_id in your payments table
$student_id = isset($pay_arr[0]['student_id']) ? $pay_arr[0]['student_id'] : 0;

?>

<style>
.flex {
    display: flex;
    width: 100%;
}

.w-50 {
    width: 50%;
}

.text-center {
    text-align: center;
}

.text-right {
    text-align: right;
}

table.wborder {
    width: 100%;
    border-collapse: collapse;
}

table.wborder>tbody>tr,
table.wborder>tbody>tr>td {
    border: 1px solid;
}

p {
    margin: unset;
}

.imgg {
    max-width: 30%;
    /* Ensure the image does not exceed its container */
    height: auto;
    /* Maintain the image's aspect ratio */
    margin-bottom: 20px;
    /* Add some space below the image */
    margin-left: 255px;

}
</style>

<div class="container-fluid">

  

    <hr>
    <div class="flex">
        <div class="w-50">

            <p>Payment Date:
                <b><?php echo isset($pay_arr[0]['date_created']) ? date("M d,Y", strtotime($pay_arr[0]['date_created'])) : ''; ?></b>
            </p>
            <p>LRN No.: <b><?php echo isset($pay_arr[0]['id_no']) ? $pay_arr[0]['id_no'] : ''; ?></b></p>
            <p>Student: <b><?php echo isset($pay_arr[0]['name']) ? ucwords($pay_arr[0]['name']) : ''; ?></b></p>
            <p>YearLevel/Section
                <b><?php echo isset($pay_arr[0]['course']) && isset($pay_arr[0]['level']) ? $pay_arr[0]['course'] . ' - ' . $pay_arr[0]['level'] : ''; ?></b>
            </p>
        </div>
    </div>
    <hr>
    <p><b>Payment Summary</b></p>
    <table class="wborder">
        <tr>
            <td width="50%">
                <p><b>Fee Details</b></p>
                <hr>
                <table width="100%">
                    <tr>
                        <td width="50%">Fee Type</td>
                        <td width="50%" class='text-right'>Amount</td>
                    </tr>
                    <?php
                    $fee_details_query = $conn->query("SELECT * FROM fee_details WHERE payment_id = {$_GET['id']}");
                    $ftotal = 0;
                    while ($row = $fee_details_query->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><b><?php echo htmlspecialchars($row['fee_description']) ?></b></td>
                        <td class='text-right'><b><?php echo number_format($row['amount'], 2) ?></b></td>
                    </tr>
                    <?php
                        $ftotal += $row['amount'];
                    }
                    ?>
                    <tr>
                        <th>Total</th>
                        <th class='text-right'><b><?php echo number_format($ftotal, 2) ?></b></th>
                    </tr>
                </table>
            </td>

            <td width="50%">
                <p><b>Payment Details</b></p>
                <table width="100%" class="wborder">
                    <tr>
                        <td width="50%">Date</td>
                        <td width="50%" class='text-right'>Amount</td>
                    </tr>
                    <?php
                    $ptotal = 0;
                    foreach ($pay_arr as $row) {
                        $ptotal += $row['total_amount'];
                        ?>
                    <tr>
                        <td><b><?php echo date("Y-m-d", strtotime($row['date_created'])) ?></b></td>
                        <td class='text-right'><b><?php echo number_format($row['fee_amount'], 2) ?></b></td>
                        <!-- Use $row['fee_amount'] instead of $row['total_amount'] -->
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th>Payable Fee</th>
                        <th class='text-right'><b><?php echo number_format($pay_arr[0]['total_amount'], 2) ?></b></th>
                    </tr>
                    <tr>
                        <td>Total Paid</td>
                        <td class='text-right'><b><?php echo number_format($row['paid_amount'], 2) ?></b></td>
                    </tr>
                    <tr>
                        <td>Balance</td>
                        <td class='text-right'>
                            <b><?php echo number_format($pay_arr[0]['total_amount'] - $row['paid_amount'], 2) ?></b>
                        </td>
                    </tr>


                </table>




            </td>
        </tr>
    </table>
    <br>
    <div class='prep'>
        <h5>Prepared by:</h5>

    </div>
    <div class='mar'>
        <h5>MARISOL L. DIGAUM</h5>
    </div>
    <div class='school'>
        <h5>SCHOOL FINANCE OFFICER</h5>
    </div>
    <div class='prepp'>
        <h5>Prepared by:</h5>

    </div>
    <div class='marr'>
        <h5>Rev. Fr. Proceso G. Apolinar</h5>
    </div>
    <div class='schooll'>
        <h5>SCHOOL DIRECTOR</h5>
    </div>
    <style>
    .mar {
        text-align: right;
       
    }

    .school {
        text-align: right;
        margin-top: -12px;
        
    }

    .prep {
       
        margin-left: 400px;
       
    }

    .marr {
        margin-left: 100px;
    }

    .schooll {
        margin-left: 120px;
        margin-top: -12px;

    }

    .prepp {
        margin-right: 250px;

    }
    </style>