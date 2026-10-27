<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function is_logged_in(): bool
{
    return isset($_SESSION['user_id'])
        && is_int($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('Please login to continue.');
        redirect('index.php');
    }
}

function redirect_if_logged_in(): void
{
    if (is_logged_in()) {
        redirect('dashboard.php');
    }
}