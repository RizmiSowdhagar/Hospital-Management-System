<?php
// Database connection settings
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    // Create a new PDO instance
    $pdo = new PDO("oci:dbname=" .$tns, $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the 'date' parameter is set
    if(isset($_POST['date'])) {
        // Retrieve the date from the POST request
        $date = $_POST['date'];

        // Prepare the SQL statement to calculate revenue for each facility on the given date
        $sql = "SELECT f.FAC_TYPE AS facility_name, SUM(i.cost) AS total_revenue
        FROM INVOICE_DETAIL i
        JOIN FACILITY f ON i.FACID = f.FACID
        WHERE TRUNC(DATE_TIME) = TO_DATE(:bind_date, 'YYYY-MM-DD')
        GROUP BY f.FAC_TYPE
        UNION ALL
        SELECT 'Total' AS facility_name, SUM(total_revenue) AS total_revenue
        FROM (
            SELECT f.FAC_TYPE AS facility_name, SUM(i.cost) AS total_revenue
            FROM INVOICE_DETAIL i
            JOIN FACILITY f ON i.FACID = f.FACID
            WHERE TRUNC(DATE_TIME) = TO_DATE(:bind_date, 'YYYY-MM-DD')
            GROUP BY f.FAC_TYPE
        ) subquery";

        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Bind the date parameter
        $stmt->bindParam(':bind_date', $date);

        // Execute the SQL statement
        $stmt->execute();

        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close the cursor
        $stmt->closeCursor();

        // Return the results as JSON
        echo json_encode($results);
    } else {
        // Handle case where 'date' parameter is not set
        echo "Error: 'date' parameter is not set.";
    }
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Error: " . $e->getMessage();
}
?>
