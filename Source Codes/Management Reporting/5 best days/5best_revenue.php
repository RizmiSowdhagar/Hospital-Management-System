<?php
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = 'sa2923';
$password = 'Loveyourself@136';

// Assuming you've established a connection to your Oracle database
    $conn = new PDO("oci:dbname=" .$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Check if the month parameter is set
/*if(isset($_POST['month'])) {
    // Retrieve the month from the POST request
    $month = $_POST['month'];

    // Construct the SQL query to retrieve the five best revenue dates for the specified month
    $sql = "SELECT DATE_TIME = TO_TIMESTAMP(DATE_TIME, 'DD-MON-YY') AS revenue_date, SUM(COST) AS total_revenue
            FROM INVOICE_DETAIL
            WHERE EXTRACT(MONTH FROM DATE_TIME) = EXTRACT(MONTH FROM TO_DATE(:month, 'YYYY-MM'))
            GROUP BY DATE_TIME
            ORDER BY total_revenue DESC
            FETCH FIRST 5 ROWS ONLY";

    // Prepare and execute the SQL query
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':month', $month);
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the results as JSON
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    // If the month parameter is not set, return an error
    http_response_code(400); // Bad Request
    echo json_encode(array('error' => 'Month parameter is missing'));
}*/
// Assuming you've established a connection to your Oracle database

// Check if the month parameter is set
if(isset($_POST['month'])) {
    // Retrieve the month from the POST request
    $month = $_POST['month'];

    // Construct the SQL query to retrieve the five best revenue dates for the specified month
    $sql = "SELECT TRUNC(TO_TIMESTAMP(DATE_TIME)) AS revenue_date, SUM(COST) AS total_revenue
    FROM INVOICE_DETAIL
    WHERE TO_CHAR(DATE_TIME, 'MON-YYYY') = TO_CHAR(TO_TIMESTAMP(:month, 'YYYY-MM'), 'MON-YYYY')
    GROUP BY TO_TIMESTAMP(DATE_TIME)
    ORDER BY total_revenue DESC
    FETCH FIRST 5 ROWS ONLY";

    // Prepare and execute the SQL query
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':month', $month);
    $stmt->execute();
    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the results as JSON
    echo json_encode($results);
} else {
    // If the month parameter is not set, return an error
    http_response_code(400); // Bad Request
    echo json_encode(array('error' => 'Month parameter is missing'));
}

// Close the connection
$conn = null;
?>
