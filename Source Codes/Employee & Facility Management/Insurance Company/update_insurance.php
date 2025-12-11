<?php
// Retrieve form data
$ins_id = $_POST['ins_id'];

// Connect to Oracle Database (replace these values with your actual database credentials)
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve original values from the database
    $stmt = $conn->prepare("SELECT ins_id, name, street, city, state, zip_code FROM INSURANCECOMPANY WHERE INS_ID=:ins_id");
    $stmt->bindParam(':ins_id', $ins_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if new values are provided and update each field individually
        if (!empty($_POST["name"])) {
            $name = $_POST["name"];
        } else {
            $name = $row['NAME'];
        }
        if (!empty($_POST["street"])) {
            $street = $_POST["street"];
        } else {
            $street = $row['STREET'];
        }
        if (!empty($_POST["city"])) {
            $city = $_POST["city"];
        } else {
            $city = $row['CITY'];
        }
        if (!empty($_POST["state"])) {
            $state = $_POST["state"];
        } else {
            $state = $row['STATE'];
        }
        if (!empty($_POST["zip_code"])) {
            $zip_code = $_POST["zip_code"];
        } else {
            $zip_code = $row['ZIP_CODE'];
        }
    }    

        // Prepare and execute update statements for each field
        $stmt = $conn->prepare("UPDATE INSURANCECOMPANY SET NAME = :name, STREET = :street, CITY = :city, STATE = :state, ZIP_CODE = :zip_code WHERE INS_ID = :ins_id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':zip_code', $zip_code);
        $stmt->bindParam(':ins_id', $ins_id);
        $stmt->execute();

    echo "Form updated successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
