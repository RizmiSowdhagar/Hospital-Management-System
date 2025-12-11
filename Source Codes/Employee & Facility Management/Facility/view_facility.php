<?php

$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM FACILITY");
    $stmt->execute();

    // Fetch all rows as an associative array
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output table header
    echo "<table border='1'>";
    echo "<tr><th>FACID</th><th>FAC_SIZE</th><th>FAC_TYPE</th><th>STREET</th><th>CITY</th><th>STATE</th><th>ZIP_CODE</th></tr>";

    // Output table rows
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>".$row['FACID']."</td>";
        echo "<td>".$row['FAC_SIZE']."</td>";
        echo "<td>".$row['FAC_TYPE']."</td>";
        echo "<td>".$row['STREET']."</td>";
        echo "<td>".$row['CITY']."</td>";
        echo "<td>".$row['STATE']."</td>";
        echo "<td>".$row['ZIP_CODE']."</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "View FACILTY record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
