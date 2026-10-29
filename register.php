<?php
require_once 'config/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $age = $_POST['age'] ?? null;
    $city = $_POST['city'] ?? '';
    
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        $result = registerUser($full_name, $email, $password, $age, $city);
        if ($result['success']) {
            $success = 'Registration successful! Please login.';
            // Clear form
            $_POST = [];
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
    <title>Register – VisionPath</title>
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

        .register-container {
            background: white;
            border-radius: var(--radius);
            padding: 2.5rem;
            max-width: 460px;
            width: 100%;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
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
            margin-bottom: 1.5rem;
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
        }
        .logo-icon svg { position: relative; z-index: 1; }
        .logo-text { line-height: 1.18; }
        .logo-text strong { display: block; font-weight: 800; font-size: 1.08rem; color: var(--navy); letter-spacing: -.3px; }
        .logo-text span { font-size: 0.73rem; color: var(--purple); font-weight: 600; letter-spacing: .5px; text-transform: uppercase; }

        h1 { font-size: 1.5rem; font-weight: 800; color: var(--navy); text-align: center; margin-bottom: 0.3rem; }
        .subtitle { text-align: center; color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; margin-bottom: .3rem; color: var(--navy); font-size: 0.85rem; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: var(--font-body);
            color: var(--dark);
            background: var(--purple-bg);
            transition: all .2s;
        }
        .form-group input:focus, .form-group select:focus {
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

        .auth-footer { text-align: center; margin-top: 1.2rem; font-size: 0.9rem; color: var(--gray); }
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
            margin-top: 0.8rem;
            transition: color .2s;
        }
        .back-home:hover { color: var(--purple); }

        .req { color: #EF4444; }

        @media (max-width: 480px) {
            .register-container { padding: 1.5rem; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <a href="index.html" class="logo-wrap">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" fill="none" width="22" height="22">
                    <path d="M12 2L8 8H3l4 4-2 6 7-4 7 4-2-6 4-4h-5L12 2z" fill="white" opacity=".9"/>
                    <circle cx="12" cy="12" r="2.5" fill="white"/>
                </svg>
            </div>
            <div class="logo-text"><strong>VisionPath</strong><span>Discover · Learn · Lead</span></div>
        </a>

        <h1>Create Your Account</h1>
        <p class="subtitle">Join 50,000+ students building their future</p>

        <?php if ($error): ?>
            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-msg"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name <span class="req">*</span></label>
                <input type="text" name="full_name" placeholder="e.g., Ali Hassan" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Email Address <span class="req">*</span></label>
                <input type="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" placeholder="e.g., 20" min="13" max="60" value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <select name="city">
                        <option value="">Select city</option>
                        <?php $cities = ['Karachi','Lahore','Islamabad','Rawalpindi','Faisalabad','Multan','Peshawar','Quetta','Hyderabad','Sialkot','Gujranwala','Abbottabad','Other'];
                        foreach ($cities as $c): ?>
                            <option value="<?php echo $c; ?>" <?php echo (($_POST['city'] ?? '') === $c) ? 'selected' : ''; ?>><?php echo $c; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Password <span class="req">*</span> <small style="font-weight:400;color:var(--gray);">(min 6 characters)</small></label>
                <input type="password" name="password" placeholder="Create a strong password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password <span class="req">*</span></label>
                <input type="password" name="confirm_password" placeholder="Confirm your password" required>
            </div>

            <button type="submit" class="btn-primary"><i class="fas fa-user-plus"></i> Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Sign in</a>
        </div>

        <a href="index.html" class="back-home"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
</body>
</html>