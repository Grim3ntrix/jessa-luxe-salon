<?php session_start(); 
    $old = $_SESSION['old'] ?? [];
    $error = $_SESSION['error'] ?? null;
    $success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jessa Luxe Salon - Login</title>
    <link rel="stylesheet" href="/css/auth/auth.css">
    <script src="/js/auth/auth.js" defer></script>
</head>
<body>
    <div class="auth-wrapper">
        <div>
            <img src="/images/JLS-logo-1.png" alt="Jessa Luxe Salon" class="logo">
        </div>

        <div class="login-container">
            <?php if ($error): ?>
                <p class="error"><?= $error; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p class="success"><?= $success; unset($_SESSION['success']); ?></p>
            <?php endif; ?>

            <form action="/login_request" method="POST">
                <input type="email" name="email" placeholder="Email address" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Login</button>
            </form>

             <div class="login-link">
                <p><a href="/signup" class="signup-link">Create new account</a></p>
            </div>
            
        </div>
    </div>
</body>
</html>
