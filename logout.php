<?php
// logout.php
session_start();

// Clear all session data
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Clear localStorage via JavaScript redirect
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(155deg, #FDFCFF 0%, #F0ECFF 50%, #EAE3FF 100%);
            margin: 0;
        }
        .logout-container {
            text-align: center;
            padding: 2rem;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #EDE9FE;
            border-top: 4px solid #7C3AED;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        h2 { color: #1E1040; margin-bottom: 0.5rem; }
        p { color: #6B7280; }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="spinner"></div>
        <h2>Logging Out...</h2>
        <p>Please wait while we securely log you out.</p>
    </div>
    <script>
        // Clear localStorage
        localStorage.removeItem("visionPath_userData");
        
        // Redirect to login page after a moment
        setTimeout(function() {
            window.location.href = "login.php?logout=success";
        }, 1500);
    </script>
</body>
</html>';
exit;
?>