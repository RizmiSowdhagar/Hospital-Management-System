<?php
// Retrieve form data
if(isset($_POST['empid'], $_POST['facid'], $_POST['p_id'], $_POST['date_time'])) {
    $empid = $_POST['empid'];
    $facid = $_POST['facid'];
    $p_id = $_POST['p_id'];
    $date_time = $_POST['date_time'];

    // Validate date and time format
    $date_time = date('Y-m-d H:i:s', strtotime($date_time));

    // Connect to Oracle Database (replace these values with your actual database credentials)
    $tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
    $username = "sa2923";
    $password = "Loveyourself@136";

    try {
        $conn = new PDO("oci:dbname=".$tns, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement to insert form data into a database table
        $stmt = $conn->prepare("INSERT INTO MAKES_APPT (empid, facid, p_id, date_time) VALUES (:empid, :facid, :p_id, TO_DATE(:date_time, 'YYYY-MM-DD HH24:MI:SS'))");

        // Bind parameters
        $stmt->bindParam(':empid', $empid);
        $stmt->bindParam(':facid', $facid);
        $stmt->bindParam(':p_id', $p_id);
        $stmt->bindParam(':date_time', $date_time);

        // Execute the statement
        $stmt->execute();

        echo "Form submitted successfully";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close database connection
    $conn = null;
} else {
    echo "One or more form fields are missing.";
}
?>
