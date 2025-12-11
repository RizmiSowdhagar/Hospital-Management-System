<?php
// Retrieve form data
$facid = $_POST['facid'];

// Connect to Oracle Database (replace these values with your actual database credentials)
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve original values from the database
    $stmt = $conn->prepare("SELECT facid, room_count, p_code, descr FROM OUTPATIENT_SURGERY WHERE FACID=:facid");
    $stmt->bindParam(':facid', $facid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if new values are provided and update each field individually
        if (!empty($_POST["room_count"])) {
            $room_count = $_POST["room_count"];
        } else {
            $room_count = $row['ROOM_COUNT'];
        }
        if (!empty($_POST["p_code"])) {
            $p_code = $_POST["p_code"];
        } else {
            $p_code = $row['P_CODE'];
        }
        if (!empty($_POST["descr"])) {
            $descr = $_POST["descr"];
        } else {
            $descr = $row['DESCR'];
        }
    }    

        // Prepare and execute update statements for each field
        $stmt = $conn->prepare("UPDATE OUTPATIENT_SURGERY SET ROOM_COUNT = :room_count, P_CODE = :p_code, DESCR = :descr WHERE FACID = :facid");
        $stmt->bindParam(':room_count', $room_count);
        $stmt->bindParam(':p_code', $p_code);
        $stmt->bindParam(':descr', $descr);
        $stmt->bindParam(':facid', $facid);
        $stmt->execute();

    echo "Form updated successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
