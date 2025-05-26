<?php session_start(); 
    $old = $_SESSION['old'] ?? [];
    $error = $_SESSION['error'] ?? null;
    $success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jessa Luxe Salon - Sign Up</title>
    <link rel="stylesheet" href="/css/auth/auth.css">
    <script src="/js/auth/auth.js" defer></script>
</head>
<body>

    <div class="auth-wrapper">
        <div>
            <img src="/images/JLS-logo-1.png" alt="Jessa Luxe Salon" class="logo">
        </div>
        <div class="signup-container">
            <h2>Create a new account</h2>
            <p>It’s simple and free.</p>
            <hr>
            <?php if ($error): ?>
                <div class="message error"><?= htmlspecialchars($error); unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="message success"><?= htmlspecialchars($success); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <form action="/signup_request" method="POST">
                <input type="text" id="username" name="username" placeholder="Username" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                
                <input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">

                <input type="password" id="password" name="password" placeholder="New password">

                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password">

                <button type="submit">Sign Up</button>
            </form>

            <div class="login-link">
                <p>Already have an account? <a href="/login">Log In</a></p>
            </div>
        </div>
    </div>
</body>
</html>
