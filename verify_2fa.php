<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_code = $_POST['2fa_code'];

    // Check if the entered code matches the one saved in the session
    if ($entered_code == $_SESSION['2fa_code']) {
        echo "Login successful!";
        // Redirect to the protected page or grant access
    } else {
        echo "Invalid 2FA code. Please try again.";
    }
}
?>
