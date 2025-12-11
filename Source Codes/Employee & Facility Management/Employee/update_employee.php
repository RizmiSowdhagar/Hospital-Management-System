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
    $stmt = $conn->prepare("SELECT FNAME, MINIT, LNAME, STREET, CITY, STATE, ZIP_CODE, SALARY, HIRE_DATE, JOB_CLASS, FACID FROM EMP WHERE EMPID=:empid");
    $stmt->bindParam(':empid', $empid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if new values are provided and update each field individually
        if (!empty($_POST["fname"])) {
            $fname = $_POST["fname"];
        } else {
            $fname = $row['FNAME'];
        }
        if (!empty($_POST["minit"])) {
            $minit = $_POST["minit"];
        } else {
            $minit = $row['MINIT'];
        }
        if (!empty($_POST["lname"])) {
            $lname = $_POST["lname"];
        } else {
            $lname = $row['LNAME'];
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
        if (!empty($_POST["salary"])) {
            $salary = $_POST["salary"];
        } else {
            $salary = $row['SALARY'];
        }
        if (!empty($_POST["hire_date"])) {
            $hire_date = date('d-m-Y', strtotime($_POST['hire_date']));
        } else {
            $hire_date = $row['HIRE_DATE'];
        }
        if (!empty($_POST["job_class"])) {
            $job_class = $_POST["job_class"];
        } else {
            $job_class = $row['JOB_CLASS'];
        }
        if (!empty($_POST["facid"])) {
            $facid = $_POST["facid"];
        } else {
            $facid = $row['FACID'];
        }
    }    

        // Prepare and execute update statements for each field
        $stmt = $conn->prepare("UPDATE EMP SET FNAME = :fname, MINIT = :minit, LNAME = :lname, STREET = :street, CITY = :city, STATE = :state, ZIP_CODE = :zip_code, SALARY = :salary, HIRE_DATE = TO_DATE(:hire_date, 'DD-MM-YYYY'), JOB_CLASS = :job_class, FACID = :facid WHERE EMPID = :empid");
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':minit', $minit);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':zip_code', $zip_code);
        $stmt->bindParam(':salary', $salary);
        $stmt->bindParam(':hire_date', $hire_date);
        $stmt->bindParam(':job_class', $job_class);
        $stmt->bindParam(':facid', $facid);
        $stmt->bindParam(':empid', $empid);
        $stmt->execute();

    echo "Form updated successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
