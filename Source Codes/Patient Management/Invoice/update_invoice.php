<?php
// Retrieve form data
$inv_id = $_POST['inv_id'];

// Connect to Oracle Database (replace these values with your actual database credentials)
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve original values from the database
    $stmt = $conn->prepare("SELECT ins_id, inv_date FROM INVOICE WHERE INV_ID=:inv_id");
    $stmt->bindParam(':inv_id', $inv_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if new values are provided and update each field individually
        if (!empty($_POST["ins_id"])) {
            $ins_id = $_POST["ins_id"];
        } else {
            $ins_id = $row['INS_ID'];
        }
        if (!empty($_POST["inv_date"])) {
            $inv_date = date('d-m-Y', strtotime($_POST['inv_date']));
            // $inv_date = $_POST['inv_date'];
        } else {
            $inv_date = $row['INV_DATE'];
        }
    }    

        // Prepare and execute update statements for each field
        $stmt = $conn->prepare("UPDATE INVOICE SET INS_ID = :ins_id, INV_DATE = TO_DATE(:inv_date, 'DD-MM-YYYY') WHERE INV_ID = :inv_id");
        $stmt->bindParam(':ins_id', $ins_id);
        $stmt->bindParam(':inv_date', $inv_date);
        $stmt->bindParam(':inv_id', $inv_id);
        $stmt->execute();

    echo "Form updated successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
