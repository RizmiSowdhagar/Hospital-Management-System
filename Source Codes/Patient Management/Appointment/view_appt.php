<?php

$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM MAKES_APPT");
    $stmt->execute();

    // Fetch all rows as an associative array
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output table header
    echo "<table border='1'>";
    echo "<tr><th>EMPID</th><th>FACID</th><th>P_ID</th><th>DATE_TIME</th></tr>";

    // Output table rows
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>".$row['EMPID']."</td>";
        echo "<td>".$row['FACID']."</td>";
        echo "<td>".$row['P_ID']."</td>";
        echo "<td>".$row['DATE_TIME']."</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "View APPOINTMENTS record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
