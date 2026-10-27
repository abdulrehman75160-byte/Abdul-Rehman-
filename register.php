<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

redirect_if_logged_in();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Session expired. Refresh the page and try again.';
    } else {
        $name = trim((string)($_POST['name'] ?? ''));
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $password = (string)($_POST['password'] ?? '');
        $confirm = (string)($_POST['confirm_password'] ?? '');

        if (strlen($name) < 2 || strlen($name) > 100) {
            $error = 'Name must be between 2 and 100 characters.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)
            || strlen($email) > 190) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            try {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = get_db()->prepare(
                    'INSERT INTO users (name, email, password)
                     VALUES (:name, :email, :password)'
                );

                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'password' => $hash,
                ]);

                set_flash('Account created. You can now sign in.');
                redirect('index.php');
            } catch (PDOException $ex) {
                if ($ex->getCode() === '23000') {
                    $error = 'This email is already registered.';
                } else {
                    error_log($ex->getMessage());
                    $error = 'Unable to create account. Check database setup.';
                }
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
    <title>Create Account | A.Rehman</title>
    <link rel="stylesheet"
          href="<?= e(BASE_URL) ?>/assets/css/style.css">
</head>
<body>
<main class="auth-wrapper">
    <section class="auth-card">
        <div class="brand">
            <div class="brand-icon">AR</div>
            <h1>A.Rehman</h1>
            <p>Create your personal account</p>
        </div>

        <h2>Create account</h2>
        <p class="subtitle">Fill in the details below.</p>

        <?php if ($error !== ''): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <?= csrf_field() ?>

            <label for="name">Full name</label>
            <input id="name" name="name" type="text"
                   maxlength="100" autocomplete="name"
                   placeholder="Type Your Name"
                   value="<?= e((string)($_POST['name'] ?? '')) ?>"
                   required>

            <label for="email">Email address</label>
            <input id="email" name="email" type="email"
                   maxlength="190" autocomplete="email"
                   placeholder="you@example.com"
                   value="<?= e((string)($_POST['email'] ?? '')) ?>"
                   required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password"
                   minlength="8" autocomplete="new-password"
                   placeholder="At least 8 characters" required>

            <label for="confirm_password">Confirm password</label>
            <input id="confirm_password" name="confirm_password"
                   type="password" minlength="8"
                   autocomplete="new-password"
                   placeholder="Enter password again" required>

            <button type="submit" class="primary-btn">
                Create Account <span aria-hidden="true">&rarr;</span>
            </button>
        </form>

        <p class="footer-text">
            Already registered?
            <a href="<?= e(BASE_URL) ?>/index.php">Sign in</a>
        </p>
        <p class="copyright">&copy; <?= date('Y') ?> A.Rehman</p>
    </section>
</main>
</body>
</html>