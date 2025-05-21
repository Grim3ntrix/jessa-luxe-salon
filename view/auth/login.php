<?php session_start(); 
    $old = $_SESSION['old'] ?? [];
    $error = $_SESSION['error'] ?? null;
    $success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jessa Luxe Salon - Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <p style="color:green"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form action="/login_request" method="POST">
        <label>Email:</label>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"><br>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Password"><br>

        <button type="submit">Login</button>
        <button ><a href="/signup">Create new account</a></button>
    </form>
</body>
</html>
