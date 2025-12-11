<?php
// Retrieve form data
$empid = $_POST['empid'];
$ssn = $_POST['ssn'];
$fname = $_POST['fname'];
$minit = $_POST['minit'];
$lname = $_POST['lname'];
$street = $_POST['street'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip_code = $_POST['zip_code'];
$salary = $_POST['salary'];
$hire_date = date('d-m-Y', strtotime($_POST['hire_date']));
$job_class = $_POST['job_class'];
$facid = $_POST['facid'];

// Connect to Oracle Database (replace these values with your actual database credentials)
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement to insert form data into a database table 
    $stmt = $conn->prepare("INSERT INTO EMP (empid, ssn, fname, minit, lname, street, city, state, zip_code, salary, hire_date, job_class, facid)
                            VALUES (:empid, :ssn, :fname, :minit, :lname, :street, :city, :state, :zip_code, :salary, TO_DATE(:hire_date, 'DD-MM-YYYY'), :job_class, :facid)");

    // Bind parameters
    $stmt->bindParam(':empid', $empid);
    $stmt->bindParam(':ssn', $ssn);
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

    $stmt->execute();

    if ($job_class === 'Nurse') {
        //$empid = $_POST['empid'];
        $certification = $_POST['certification'];
        $stmt_nurse = $conn->prepare("INSERT INTO NURSE (empid, certification) VALUES (:empid, :certification)");
        $stmt_nurse->bindParam(':empid', $empid);
        $stmt_nurse->bindParam(':certification', $certification);
        $stmt_nurse->execute();
    }
    elseif ($job_class === 'Doctor') {
        //$empid = $_POST['empid'];
        $speciality = $_POST['speciality'];
        $bc_date = date('d-m-Y', strtotime($_POST['bc_date']));
        $stmt_doctor = $conn->prepare("INSERT INTO DOCTOR (empid, speciality, bc_date) VALUES (:empid, :speciality, TO_DATE(:bc_date, 'DD-MM-YYYY'))");
        $stmt_doctor->bindParam(':empid', $empid);
        $stmt_doctor->bindParam(':speciality', $speciality);
        $stmt_doctor->bindParam(':bc_date', $bc_date);
        $stmt_doctor->execute();
    }
    elseif ($job_class === 'Admin') {
        //$empid = $_POST['empid'];
        $jobtitle = $_POST['jobtitle'];
        $stmt_admin = $conn->prepare("INSERT INTO ADMIN (empid, jobtitle) VALUES (:empid, :jobtitle)");
        $stmt_admin->bindParam(':empid', $empid);
        $stmt_admin->bindParam(':jobtitle', $jobtitle);
        $stmt_admin->execute();
    }
    elseif ($job_class === 'Other HCP') {
        //$empid = $_POST['empid'];
        $job_title = $_POST['job_title'];
        $stmt_otherhcp = $conn->prepare("INSERT INTO OTHER_HCP (empid, job_title) VALUES (:empid, :job_title)");
        $stmt_otherhcp->bindParam(':empid', $empid);
        $stmt_otherhcp->bindParam(':job_title', $job_title);
        $stmt_otherhcp->execute();
    }

    echo "Employee and ", $job_class, " record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
