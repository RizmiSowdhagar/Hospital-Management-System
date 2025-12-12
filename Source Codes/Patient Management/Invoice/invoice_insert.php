<?php
// Retrieve form data
$inv_id = $_POST['inv_id'];
$ins_id = $_POST['ins_id'];
$inv_date = date('d-m-Y', strtotime($_POST['inv_date']));

// Connect to Oracle Database (replace these values with your actual database credentials)
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement to insert form data into a database table 
    $stmt = $conn->prepare("INSERT INTO INVOICE (inv_id, ins_id, inv_date)
    VALUES (:inv_id, :ins_id, TO_DATE(:inv_date, 'DD-MM-YYYY'))");

    // Bind parameters
    $stmt->bindParam(':inv_id', $inv_id);
    $stmt->bindParam(':ins_id', $ins_id);
    $stmt->bindParam(':inv_date', $inv_date);

    $stmt->execute();

    
    echo "Invoice record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
