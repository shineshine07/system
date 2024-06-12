<?php
include 'db_connect.php';
$selectedMonth = isset($_POST['selectedMonth']) ? $_POST['selectedMonth'] : '';
?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card_body">
                <div class="row justify-content-center pt-4">
                    <label for="" class="mt-2">Select Period</label>
                    <div class="col-sm-3">
                        <form method="post" action="">
                            <input type="month" name="selectedMonth" id="month" value="<?php echo $selectedMonth ?>" class="form-control">
                            <input type="submit" value="Generate Report" class="btn btn-danger mt-2">
                        </form>
                    </div>
                </div>
                <hr>
                <div class="col-md-12">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $isMonthSelected = !empty($selectedMonth);
                        $isWeekSelected = !empty($selectedWeek);

                        if ($isMonthSelected && !$isWeekSelected) {
                            // Your existing month query remains the same
                            $selectedYear = substr($selectedMonth, 0, 4);
                            $selectedMonth = substr($selectedMonth, 5, 2);
                            $selectedMonthName = date("F", mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));

                            $total = 0;
                            $stmt = $conn->prepare("SELECT fee_description, SUM(amount) as total FROM fee_details WHERE YEAR(date) = ? AND MONTH(date) = ? GROUP BY fee_description");
                            $stmt->bind_param("ss", $selectedYear, $selectedMonth);
                        } elseif (!$isMonthSelected && $isWeekSelected) {
                            // Updated logic for weekly records
                            list($selectedYear, $selectedWeek) = explode('-W', $selectedWeek);

                            $total = 0;
                            $stmt = $conn->prepare("SELECT fee_description, SUM(amount) as total FROM fee_details WHERE YEAR(date) = ? AND WEEK(date, 1) = ? GROUP BY fee_description");
                            $stmt->bind_param("ss", $selectedYear, $selectedWeek);
                        } else {
                            echo "Please select either a month or a week.";
                            exit; // Stop further execution
                        }

                        // Execute the query and check for errors
                        if (!$stmt->execute()) {
                            die('Error executing query: ' . $stmt->error);
                        }

                        $feeDetails = $stmt->get_result();
                        $stmt->close();

                        if ($feeDetails->num_rows === 0) {
                            echo "No records found for the selected period.";
                        } else {
                            $totalCollection = 0;

                            echo "<style>";
        echo ".imgg{
            max-width: 30%; /* Ensure the image does not exceed its container */
            height: auto; /* Maintain the image's aspect ratio */
            margin-bottom: 20px; /* Add some space below the image */
            margin-left: 335px;
        }";
        echo ".mar{
            text-align:right;
            margin-right:20px;}";
            
        echo ".school{
            text-align:right;}";
        echo ".prep{
            margin-left:550px;";
        echo "</style>";
                            
                            // Add the image tag here
                            echo "<img src='assets/img/baang1.png' alt='Your Image Alt Text' class='imgg'>";

                            echo "<div id='print-content'>";
                            echo "<h5 class='text-center'> PAYMENTS REPORT</h5>";
                            echo "<h6 class='text-center'> FOR THE PERIOD " . ($isMonthSelected ? "$selectedMonthName $selectedYear" : "Week $selectedWeek of $selectedYear") . "</h6>";
                            echo "<table class='table table-bordered' id='report-list'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th class='text-center'>#</th>";
                            echo "<th class=''> COLLECTION </th>";
                            echo "<th class='text-right'>TOTAL</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";

                            $i = 1; // Initialize $i here

                            while ($row = $feeDetails->fetch_assoc()) {
                                $total += $row['total'];
                                echo "<tr>";
                                echo "<td class='text-center'>$i</td>";
                                echo "<td>{$row['fee_description']}</td>";
                                echo "<td class='text-right'>" . number_format($row['total'], 2) . "</td>";
                                echo "</tr>";
                                $i++;
                            }

                            echo "</tbody>";
                            echo "<tfoot>";
                            echo "<tr>";
                            echo "<th colspan='2' class='text-right'>TOTAL COLLECTION </th>";
                            echo "<th class='text-right'>" . number_format($total, 2) . "</th>";
                            echo "</tr>";
                            echo "</tfoot>";
                            echo "</table>";
                            echo "<br><br>";
                                                echo "<div class='prep'><h5>Prepared by:</h5>";
                                                echo "<br><br>";
                                                echo "<div class='mar'><h5>MARISOL L. DIGAUM</h5></div>";
                                                echo "<div class='school'><h5>SCOOL FINANCE OFFICER</h5></div>";
                                                echo "</div>";
                            echo "</div>";
                    

                            // Print button
                            echo "<button onclick='printReport()' class='btn btn-success float-right'>Print Report</button>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
                    @media print {
                        @page {
                            size: 8.5in 11in;


                        }

                        body * {
                            visibility: hidden;
                        }

                        #print-content,
                        #print-content * {
                            visibility: visible;
                        }

                        #print-content {
                            margin: 1cm;
                            /* Adjust the margin as needed */
                            margin-top: -50px;
                            margin-left: -5cm;
                            /* Adjust the left margin as needed */

                        }

                        .imgg {
                            visibility: visible;
                            margin-top: -250px;
                            margin-left: 130px;
                            width: 1000px;
                        }

                        h3 {
                            font-size: 18px;
                            margin-bottom: 12px;
                            text-align: center;
                            /* Center the heading */
                        }

                        table {
                            width: 100%;
                            /* Use 100% to ensure it fits within the printable area */
                            border-collapse: collapse;
                            font-size: 14px;
                            /* Adjust the font size for the table */
                        }

                        .table th,
                        .table td {
                            border: 1px solid #ddd;
                            padding: 12px;
                            /* Adjust the padding as needed */
                            text-align: left;
                        }

                        .float-right {
                            display: none;
                        }
                    }
                    </style>

                    <script>
                    function printReport() {
                        // Display details or send for printing
                        window.print();
                    }
                    </script>