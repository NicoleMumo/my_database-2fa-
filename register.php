<?php
require_once 'Database.php';
require_once 'User.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"];

    if (empty($username) || empty($email) || empty($password)) {
        die("Please fill in all fields.");
    }

    if (!$email) {
        die("Invalid email format.");
    }

    if (strlen($password) < 6) {
        die("Password must be at least 6 characters long.");
    }

    // Two-factor authentication secret generation
    require 'vendor/autoload.php';
    $gAuth = new \PHPGangsta_GoogleAuthenticator();
    $secret = $gAuth->createSecret();
    $qrCodeUrl = $gAuth->getQRCodeGoogleUrl('YourApp', $secret);

    echo "Scan this QR code with your Google Authenticator app: <br>";
    echo "<img src='$qrCodeUrl'>";

    // Database connection and user creation
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    $user->username = $username;
    $user->email = $email;
    $user->password = $password;
    $user->twofa_secret = $secret;

    if ($user->create()) {
        echo "User created successfully.";
    } else {
        echo "User creation failed.";
    }
}
?>