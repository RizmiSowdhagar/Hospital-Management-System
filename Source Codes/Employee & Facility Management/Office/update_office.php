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
    $stmt = $conn->prepare("SELECT facid, office_count FROM OFFICE WHERE FACID=:facid");
    $stmt->bindParam(':facid', $facid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if new values are provided and update each field individually
        if (!empty($_POST["office_count"])) {
            $office_count = $_POST["office_count"];
        } else {
            $office_count = $row['OFFICE_COUNT'];
        }
    }    

        // Prepare and execute update statements for each field
        $stmt = $conn->prepare("UPDATE OFFICE SET OFFICE_COUNT = :office_count WHERE FACID = :facid");
        $stmt->bindParam(':office_count', $office_count);
        $stmt->bindParam(':facid', $facid);
        $stmt->execute();

    echo "Form updated successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
