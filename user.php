<?php
require_once 'Database.php';

function send2FACode($email, $code) {
    $subject = "Your 2FA Code";
    $message = "Your two-factor authentication code is: $code";
    $headers = "From: no-reply@yourdomain.com";
    mail($email, $subject, $message, $headers); // PHP's mail function to send email
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Generate a random 6-digit 2FA code
        $code = rand(100000, 999999);

        // Save the 2FA code temporarily in the session
        session_start();
        $_SESSION['2fa_code'] = $code;
        $_SESSION['username'] = $username;

        // Send the 2FA code to the user's email
        send2FACode($user['email'], $code);

        // Prompt for 2FA code
        echo '<form method="POST" action="verify_2fa.php">
                  <label for="2fa_code">Enter the 2FA code sent to your email:</label>
                  <input type="text" name="2fa_code" required>
                  <button type="submit">Verify</button>
              </form>';
    } else {
        echo 'Invalid credentials';
    }
}
?>
