<?php
require_once 'config/auth.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $success = 'You have been logged out successfully.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $result = loginUser($email, $password);
        if ($result['success']) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – VisionPath</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --purple: #7C3AED;
            --purple-light: #A78BFA;
            --purple-pale: #EDE9FE;
            --purple-bg: #F5F3FF;
            --white: #FFFFFF;
            --navy: #1E1040;
            --dark: #2D2547;
            --gray: #6B7280;
            --border: #E5E0F8;
            --radius: 16px;
            --shadow: 0 4px 24px rgba(124, 58, 237, 0.10);
            --shadow-md: 0 8px 40px rgba(124, 58, 237, 0.18);
            --font-body: 'Poppins', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background: linear-gradient(155deg, #FDFCFF 0%, #F0ECFF 50%, #EAE3FF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            background: white;
            border-radius: var(--radius);
            padding: 3rem;
            max-width: 420px;
            width: 100%;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--purple), var(--purple-light));
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--purple) 0%, var(--purple-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
            position: relative;
            overflow: hidden;
        }

        .logo-icon svg { position: relative; z-index: 1; }
        .logo-text { line-height: 1.18; }
        .logo-text strong { display: block; font-weight: 800; font-size: 1.08rem; color: var(--navy); letter-spacing: -.3px; }
        .logo-text span { font-size: 0.73rem; color: var(--purple); font-weight: 600; letter-spacing: .5px; text-transform: uppercase; }

        h1 { font-size: 1.6rem; font-weight: 800; color: var(--navy); text-align: center; margin-bottom: 0.5rem; }
        .subtitle { text-align: center; color: var(--gray); font-size: 0.9rem; margin-bottom: 2rem; }

        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; font-weight: 600; margin-bottom: .4rem; color: var(--navy); font-size: 0.9rem; }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 0.9rem;
            font-family: var(--font-body);
            color: var(--dark);
            background: var(--purple-bg);
            transition: all .2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--purple);
            background: white;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.14);
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: var(--purple);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
            font-family: var(--font-body);
        }
        .btn-primary:hover { background: #6D28D9; transform: translateY(-2px); box-shadow: 0 8px 22px rgba(124, 58, 237, 0.38); }

        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--gray); }
        .auth-footer a { color: var(--purple); font-weight: 700; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }

        .error-msg, .success-msg {
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .error-msg { background: #FFF5F5; color: #DC2626; border: 1px solid #FECACA; }
        .success-msg { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--gray);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.85rem;
            margin-top: 1rem;
            transition: color .2s;
        }
        .back-home:hover { color: var(--purple); }
    </style>
</head>
<body>
    <div class="login-container">
        <a href="index.html" class="logo-wrap">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" fill="none" width="22" height="22">
                    <path d="M12 2L8 8H3l4 4-2 6 7-4 7 4-2-6 4-4h-5L12 2z" fill="white" opacity=".9"/>
                    <circle cx="12" cy="12" r="2.5" fill="white"/>
                </svg>
            </div>
            <div class="logo-text"><strong>VisionPath</strong><span>Discover · Learn · Lead</span></div>
        </a>

        <h1>Welcome Back</h1>
        <p class="subtitle">Sign in to continue your learning journey</p>

        <?php if ($error): ?>
            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-msg"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="register.php">Create one now</a>
        </div>

        <a href="index.html" class="back-home"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
</body>
</html>