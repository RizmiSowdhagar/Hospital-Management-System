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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["new_value"])) {
            $newValue = $_POST["new_value"];
            $stmt = $conn->prepare("UPDATE INVOICE_DETAIL SET COST = :newValue WHERE INV_ID=:inv_id");
            $stmt->bindParam(':newValue', $newValue);
            $stmt->bindParam(':inv_id', $inv_id);
            $stmt->execute();
            echo "Value updated successfully!";
        } else {
        
            $stmt = $conn->prepare("UPDATE SET COST = NULL WHERE INV_ID=:inv_id");
            $stmt->execute();
            echo "Value set to NULL!";
        }
    }
    
    }
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
