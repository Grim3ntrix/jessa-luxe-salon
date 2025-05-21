<?php session_start(); 
    $old = $_SESSION['old'] ?? [];
    $error = $_SESSION['error'] ?? null;
    $success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jessa Luxe Salon - Register</title>
</head>
<body>
    <h2>Sign Up</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="/signup_request" method="POST">

        <label>Username:</label>
        <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
        <br>
        <label>Email:</label>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        <br>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Password">
        <br>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" placeholder="Confirm Password">
        <br>
        <button type="submit">Sign Up</button>
        <p>Already have an account?</p><a href="/login">Login</a>
    </form>
</body>
</html>
