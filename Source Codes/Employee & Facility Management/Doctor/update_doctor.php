<?php
// Retrieve form data
$empid = $_POST['empid'];

// Connect to Oracle Database (replace these values with your actual database credentials)
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve original values from the database
    $stmt = $conn->prepare("SELECT empid, speciality, bc_date FROM DOCTOR WHERE EMPID=:empid");
    $stmt->bindParam(':empid', $empid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if new values are provided and update each field individually
        if (!empty($_POST["speciality"])) {
            $speciality = $_POST["speciality"];
        } else {
            $speciality = $row['SPECIALITY'];
        }
        if (!empty($_POST["bc_date"])) {
            $bc_date = date('d-m-Y', strtotime($_POST['bc_date']));
        } else {
            $bc_date = $row['BC_DATE'];
        }
    }    

        // Prepare and execute update statements for each field
        $stmt = $conn->prepare("UPDATE DOCTOR SET SPECIALITY = :speciality, BC_DATE = TO_DATE(:bc_date, 'DD-MM-YYYY') WHERE EMPID = :empid");
        $stmt->bindParam(':speciality', $speciality);
        $stmt->bindParam(':bc_date', $bc_date);
        $stmt->bindParam(':empid', $empid);
        $stmt->execute();

    echo "Form updated successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
