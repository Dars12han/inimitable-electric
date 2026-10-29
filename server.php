<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($message) || empty($recaptchaResponse)) {
        die("All fields are required.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Verify reCAPTCHA
    $recaptchaSecret = "6LfMZ-gqAAAAAN93df8XMYBl_hHMk0rKEfyYEe8U"; // Replace with your actual reCAPTCHA secret key
    $recaptchaVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse";

    $recaptchaResult = file_get_contents($recaptchaVerifyUrl);
    $recaptchaData = json_decode($recaptchaResult);

    if (!$recaptchaData->success) {
        die("reCAPTCHA verification failed.");
    }

    // Store data in database (MySQL)
    $servername = "localhost";
    $username = "u571946566_inimitable"; // Replace with your DB username
    $password = "Inimit_d4465"; // Replace with your DB password
    $dbname = "u571946566_inimitable_db"; // Replace with your database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        echo "Form submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Send email notification
    $to = "inimitablebattery@gmail.com"; // Replace with your admin email
    $subject = "New Contact Form Submission";
    $body = "Name: $name\nEmail: $email\nPhone: $phone\nMessage:\n$message";
    $headers = "From: noreply@yourdomain.com\r\nReply-To: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "Email notification sent.";
    } else {
        echo "Error sending email.";
    }
} else {
    die("Invalid request.");
}
?>

