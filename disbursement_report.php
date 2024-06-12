<?php
include 'db_connect.php';
$selectedMonth = isset($_POST['selectedMonth']) ? $_POST['selectedMonth'] : '';
$selectedWeek = isset($_POST['selectedWeek']) ? $_POST['selectedWeek'] : '';
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
                            $stmt = $conn->prepare("SELECT type, SUM(total) as total FROM disbursement WHERE YEAR(date) = ? AND MONTH(date) = ? GROUP BY type");
                            $stmt->bind_param("ss", $selectedYear, $selectedMonth);
                        } elseif (!$isMonthSelected && $isWeekSelected) {
                            // Updated logic for weekly records
                            list($selectedYear, $selectedWeek) = explode('-W', $selectedWeek);

                            // Assuming $selectedYear and $selectedWeek are set appropriately

$total = 0;
$stmt = $conn->prepare("SELECT type, SUM(total) as total FROM disbursement WHERE YEAR(date) = ? AND WEEK(date, 1) = ? GROUP BY type");
$stmt->bind_param("ss", $selectedYear, $selectedWeek);

                            
                        } else {
                            echo "Please select either a month or a week.";
                            exit; // Stop further execution
                        }

                        // Execute the query and check for errors
                        if (!$stmt->execute()) {
                            die('Error executing query: ' . $stmt->error);
                        }

                        $disbursements = $stmt->get_result();
                        $stmt->close();

                        if ($disbursements->num_rows === 0) {
                            echo "No records found for the selected period.";
                        } else {
                            $totalDisbursement = 0;

                             
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
                            echo "<h5 class='text-center'> DISBURSEMENT REPORT</h5>";
                            echo "<h6 class='text-center'> FOR THE PERIOD " . ($isMonthSelected ? $selectedMonthName : "Week $selectedWeek of $selectedYear") . "</h6>";
                            echo "<table class='table table-bordered' id='report-list'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th class='text-center'>#</th>";
                            echo "<th class=''> EXPENDITURES / DISBURSEMENT / EXPENSES </th>";
                            echo "<th class='text-right'>TOTAL</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";

                            $i = 1; // Initialize $i here

                            while ($row = $disbursements->fetch_assoc()) {
                                $total += $row['total'];
                                echo "<tr>";
                                echo "<td class='text-center'>$i</td>";
                                echo "<td>{$row['type']}</td>";
                                echo "<td class='text-right'>" . number_format($row['total'], 2) . "</td>";
                                echo "</tr>";
                                $i++;
                            }

                            echo "</tbody>";
                            echo "<tfoot>";
                            echo "<tr>";
                            echo "<th colspan='2' class='text-right'>TOTAL DISBURSEMENT </th>";
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
                            echo "<button onclick='printReport()' class='btn btn-dark float-right'>Print Report</button>";
                            echo "<br><br><br><br>";
                    }
                }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Include the necessary scripts and stylesheets for jQuery UI Datepicker and Bootstrap -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-eBdeMYz5Fv1AdkZtG6x2PHfi6jw9EkVtBLvRpO5o1Y6tLo31fYEXMtpEjeL8/hqI2nqt0P5aw5O7jxlPj7yU5g==" crossorigin="anonymous" />
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1aP5l1qeh2Pnhfe5MOWAoi1kG8LfeU9CsdE=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-9h6BECaTq5rEd1Wc3sEun5/BXNlGu8dRo1Uf3H02N3Gx/J5cVWD04qMr7pC5tF7Gic8kNO1JqdbEUlJfZoH2Uw==" crossorigin="anonymous"></script>
<style>
@media print {
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
        #prep{
            visibility: visible;
        }

        .imgg {
            visibility: visible;
            margin-top: -290px;
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
}
</style>

<script>
function printReport() {
    // Display details or send for printing
    window.print();
}



    // Function to toggle between month and week inputs
    function toggleDateInputs() {
        var monthInput = document.getElementById("month");
        var weekInput = document.getElementById("week");
        monthInput.value = "";
        weekInput.value = "";
        monthInput.disabled = !monthInput.disabled;
        weekInput.disabled = !weekInput.disabled;
    }

    // Initialize Bootstrap Datepicker for the month input
    $("#month-picker").datepicker({
        format: 'yyyy-mm',
        autoclose: true,
        useCurrent: false,
        minViewMode: 1
    });

    // Initialize Bootstrap Datepicker for the week input
    $("#week-picker").datepicker({
        format: 'yyyy-Www',
        autoclose: true,
        useCurrent: false
    });
    // Update the start date of the week input when a month is selected
    $("#month-picker").on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        $("#week-picker").datepicker('setStartDate', startDate);
        $("#week-picker").datepicker('setDate', startDate);
        $("#week-picker").datepicker('update');
    });

    // Update the end date of the month input when a week is selected
    $("#week-picker").on('changeDate', function(selected) {
        var endDate = new Date(selected.date.valueOf());
        $("#month-picker").datepicker('setEndDate', endDate);
        $("#month-picker").datepicker('update');
    });
</script>
