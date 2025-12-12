<?php

$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM PATIENT");
    $stmt->execute();

    // Fetch all rows as an associative array
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output table header
    echo "<table border='1'>";
    echo "<tr><th>P_ID</th><th>FNAME</th><th>MINIT</th><th>LNAME</th><th>STREET</th><th>CITY</th><th>STATE</th><th>ZIP_CODE</th><th>EMPID</th><th>INS_ID</th></tr>";

    // Output table rows
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>".$row['P_ID']."</td>";
        echo "<td>".$row['FNAME']."</td>";
        echo "<td>".$row['MINIT']."</td>";
        echo "<td>".$row['LNAME']."</td>";
        echo "<td>".$row['STREET']."</td>";
        echo "<td>".$row['CITY']."</td>";
        echo "<td>".$row['STATE']."</td>";
        echo "<td>".$row['ZIP_CODE']."</td>";
        echo "<td>".$row['EMPID']."</td>";
        echo "<td>".$row['INS_ID']."</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "View PATIENT record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
