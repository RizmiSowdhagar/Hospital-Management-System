<?php
// Database connection settings
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    // Create a new PDO instance
    $pdo = new PDO("oci:dbname=" . $tns, $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the 'begin_date' and 'end_date' parameters are set
    if (isset($_POST['begin_date']) && isset($_POST['end_date'])) {
        // Retrieve the begin date and end date from the POST request
        $beginDate = $_POST['begin_date'];
        $endDate = $_POST['end_date'];

        // Prepare the SQL statement to compute average daily revenue for each insurance company
        $sql = "SELECT TRUNC(d.DATE_TIME) AS revenue_date, c.NAME AS insurance_company, AVG(d.COST) AS avg_daily_revenue
                FROM INVOICE_DETAIL d
                JOIN INVOICE i ON d.INV_ID = i.INV_ID
                JOIN INSURANCECOMPANY c ON i.INS_ID = c.INS_ID
                WHERE d.DATE_TIME BETWEEN TO_DATE(:begin_date, 'YYYY-MM-DD') AND TO_DATE(:end_date, 'YYYY-MM-DD')
                GROUP BY TRUNC(d.DATE_TIME), c.NAME
                ORDER BY TRUNC(d.DATE_TIME), c.NAME";

        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Bind the date parameters
        $stmt->bindParam(':begin_date', $beginDate);
        $stmt->bindParam(':end_date', $endDate);

        // Execute the SQL statement
        $stmt->execute();

        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close the cursor
        $stmt->closeCursor();

        // Return the results as JSON
        echo json_encode($results);
    } else {
        // Handle case where 'begin_date' or 'end_date' parameters are not set
        echo "Error: 'begin_date' or 'end_date' parameters are not set.";
    }
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Error: " . $e->getMessage();
}
?>
