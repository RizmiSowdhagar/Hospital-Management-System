<?php

function fetchDataFromDatabase() {
    //ob_start();
    // Database connection details
    $tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
    $username = "sa2923";
    $password = "Loveyourself@136";
    try {
        // Connect to the database
        $conn = new PDO("oci:dbname=" .$tns, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL query to retrieve distinct dates
        $sqlDates = "SELECT DISTINCT TRUNC(DATE_TIME) AS invoice_date FROM INVOICE_DETAIL";
        $stmtDates = $conn->prepare($sqlDates);
        $stmtDates->execute();

        // Array to store invoice details
        $invoices = [];

        // Loop through each date and retrieve invoice details
        while ($date = $stmtDates->fetch(PDO::FETCH_ASSOC)) {
            $currentDate = $date['INVOICE_DATE']; // Ensure 'invoice_date' key

            // Prepare SQL query to retrieve invoice details for the current date
            $sqlAppointments = "SELECT INV_ID, P_ID, SUM(COST) AS total_cost FROM INVOICE_DETAIL WHERE TRUNC(DATE_TIME) = TO_TIMESTAMP(:current_date, 'DD-MON-YY') GROUP BY INV_ID, P_ID";
            $stmtAppointments = $conn->prepare($sqlAppointments);
            $stmtAppointments->bindParam(":current_date", $currentDate);
            $stmtAppointments->execute();

            // Loop through each invoice detail for the current date
            while ($invoiceData = $stmtAppointments->fetch(PDO::FETCH_ASSOC)) {
                $invoiceId = $invoiceData['INV_ID'];
                $patientId = $invoiceData['P_ID'];
                $totalCost = $invoiceData['TOTAL_COST']; // Ensure 'total_cost' key

                // Retrieve patient information
                $patientInfoSql = "SELECT FNAME, LNAME, STREET, CITY, STATE, ZIP_CODE FROM PATIENT WHERE P_ID = :P_ID";
                $stmtPatientInfo = $conn->prepare($patientInfoSql);
                $stmtPatientInfo->bindParam(":P_ID", $patientId);
                $stmtPatientInfo->execute();
                $patientInfo = $stmtPatientInfo->fetch(PDO::FETCH_ASSOC);

                // Construct invoice object
                $invoice = [
                    'inv_id' => $invoiceId,
                    'date' => $currentDate,
                    'p_id' => $patientId,
                    'patient_name' => $patientInfo['FNAME'] . ' ' . $patientInfo['LNAME'],
                    'address' => $patientInfo['STREET'] . ', ' . $patientInfo['CITY'] . ', ' . $patientInfo['STATE'] . ' ' . $patientInfo['ZIP_CODE'],
                    'total_charges' => $totalCost
                ];

                // Add invoice to the array
                $invoices[] = $invoice;
            }
        }

        // Close connection
        $conn = null;
        //return $invoices;
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($invoices);
        //ob_end_flush();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
fetchDataFromDatabase();
?>
