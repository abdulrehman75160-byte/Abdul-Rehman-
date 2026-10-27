<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

require_login();

$name = (string)($_SESSION['user_name'] ?? 'User');
$email = (string)($_SESSION['user_email'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | A.Rehman</title>
    <link rel="stylesheet"
          href="<?= e(BASE_URL) ?>/assets/css/style.css">
</head>
<body>
    <main class="dashboard-wrapper">
        <header class="dashboard-nav">
            <a class="nav-brand" href="<?= e(BASE_URL) ?>/dashboard.php">
                <span class="brand-icon small">AR</span>
                A.Rehman
            </a>

            <form method="POST" action="<?= e(BASE_URL) ?>/logout.php">
                <?= csrf_field() ?>
                <button class="logout-btn" type="submit">Logout</button>
            </form>
        </header>

        <section class="welcome-card">
            <span class="welcome-tag">ACCOUNT DASHBOARD</span>
            <h1>Welcome, <?= e($name) ?>!</h1>
            <p>You have successfully signed in to your account.</p>

            <div class="profile-box">
                <div class="profile-avatar">AR</div>
                <div>
                    <h3><?= e($name) ?></h3>
                    <p><?= e($email) ?></p>
                    <span class="account-status">Account active</span>
                </div>
            </div>
        </section>

        <p class="copyright">
            &copy; <?= date('Y') ?> A.Rehman Login System
        </p>
    </main>
</body>
</html>