<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

redirect_if_logged_in();

$error = '';
$notice = get_flash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Session expired. Please refresh and try again.';
    } else {
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $password = (string)($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)
            || $password === '') {
            $error = 'Please enter a valid email and password.';
        } else {
            try {
                $stmt = get_db()->prepare(
                    'SELECT id, name, email, password
                     FROM users WHERE email = :email LIMIT 1'
                );

                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();

                if ($user && password_verify(
                    $password,
                    $user['password']
                )) {
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = (int)$user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];

                    unset($_SESSION['csrf_token']);

                    redirect('dashboard.php');
                }

                $error = 'Incorrect email or password.';
            } catch (PDOException $ex) {
                error_log($ex->getMessage());
                $error = 'Database error. Check your database setup.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | A.Rehman</title>
    <link rel="stylesheet"
          href="<?= e(BASE_URL) ?>/assets/css/style.css">
</head>
<body>
    <main class="auth-wrapper">
        <section class="auth-card">
            <div class="brand">
                <div class="brand-icon">AR</div>
                <h1>A.Rehman</h1>
                <p>Welcome back to your account</p>
            </div>

            <h2>Sign in</h2>
            <p class="subtitle">Enter your details to continue.</p>

            <?php if ($notice !== ''): ?>
                <div class="alert success"><?= e($notice) ?></div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alert error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <?= csrf_field() ?>

                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
                    value="<?= e((string)($_POST['email'] ?? '')) ?>"
                    autocomplete="username"
                    required
                >

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >

                <button type="submit" class="primary-btn">
                    Sign In <span aria-hidden="true">&rarr;</span>
                </button>
            </form>

            <p class="footer-text">
                Don't have an account?
                <a href="<?= e(BASE_URL) ?>/register.php">Create account</a>
            </p>

            <p class="copyright">&copy; <?= date('Y') ?> A.Rehman</p>
        </section>
    </main>
</body>
</html>