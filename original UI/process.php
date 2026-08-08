<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$user = "uhwlqvsp_Charles";
$pass = "Mat*Z_GgA()2047";
$db   = "uhwlqvsp_alnahda";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and collect form data
    $fullname        = htmlspecialchars($_POST['fullname']);
    $email           = htmlspecialchars($_POST['email']);
    $weight          = (float) $_POST['weight'];
    $phone           = htmlspecialchars($_POST['phone']);
    $phone2          = htmlspecialchars($_POST['phone2'] ?? '');
    $county          = htmlspecialchars($_POST['county']);
    $age             = (int) $_POST['age'];
    $preferredRole   = htmlspecialchars($_POST['preferredRole']);
    $gender          = htmlspecialchars($_POST['gender']);
    $languages       = htmlspecialchars($_POST['languages']);
    $travelledSaudia = htmlspecialchars($_POST['travelledSaudia'] ?? '');
    $returnYear      = htmlspecialchars($_POST['returnYear'] ?? '');
    $durationYears   = htmlspecialchars($_POST['durationYears'] ?? '');
    $finishedContract = htmlspecialchars($_POST['finishedContract'] ?? '');
    $issueWithSponsor = htmlspecialchars($_POST['issueWithSponsor'] ?? '');
    $contractExplain  = htmlspecialchars($_POST['contractExplain'] ?? '');
    $deported         = htmlspecialchars($_POST['deported'] ?? '');
    $exitVisa         = htmlspecialchars($_POST['exitVisa'] ?? '');
    $reentryVisa      = htmlspecialchars($_POST['reentryVisa'] ?? '');
    $lebanon          = htmlspecialchars($_POST['lebanon'] ?? '');
    $jordan           = htmlspecialchars($_POST['jordan'] ?? '');
    $medicalFit       = htmlspecialchars($_POST['medicalFit'] ?? '');
    $willingToReturn  = htmlspecialchars($_POST['willingToReturn'] ?? '');
    $validPassport    = htmlspecialchars($_POST['validPassport'] ?? '');
    $validConduct     = htmlspecialchars($_POST['validConduct'] ?? '');
    $appointmentPreference = !empty($_POST['appointmentPreference']) ? $_POST['appointmentPreference'] : NULL;
    $consent          = isset($_POST['consent']) ? 1 : 0;

    // ✅ Prepare SQL query matching your table structure
    $stmt = $conn->prepare("
        INSERT INTO applications (
            fullname, email, weight, phone, phone2, county, age,
            preferredRole, gender, languages,
            travelledSaudia, returnYear, durationYears,
            finishedContract, issueWithSponsor, contractExplain,
            deported, exitVisa, reentryVisa,
            lebanon, jordan, medicalFit, willingToReturn,
            validPassport, validConduct, appointmentPreference, consent
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // ✅ Correct binding types (county is string!)
    $stmt->bind_param(
        "ssdsssisssssssssssssssssssi",
        $fullname, $email, $weight, $phone, $phone2, $county, $age,
        $preferredRole, $gender, $languages,
        $travelledSaudia, $returnYear, $durationYears,
        $finishedContract, $issueWithSponsor, $contractExplain,
        $deported, $exitVisa, $reentryVisa,
        $lebanon, $jordan, $medicalFit, $willingToReturn,
        $validPassport, $validConduct, $appointmentPreference, $consent
    );

    if ($stmt->execute()) {
        echo "✅ Application submitted successfully!";
    } else {
        echo "❌ Error inserting data: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
