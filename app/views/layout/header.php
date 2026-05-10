<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_id']);
$user_name    = $_SESSION['user_name'] ?? 'Traveller';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tripverse</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;600&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Tripverse/app/assets/css/index.css">
</head>

<body>

    <nav id="nav">
        <a href="/Tripverse/index.php" class="logo">
            <span class="logo-ball">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="2" y1="12" x2="22" y2="12" />
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                </svg>
            </span>
            TripVerse
        </a>

        <ul class="nav-links">
            <li><a href="/Tripverse/index.php" class="active">Home</a></li>
            <?php if ($is_logged_in): ?>
                <li><a href="/Tripverse/index.php#myTrip">My Trips</a></li>
            <?php endif; ?>
        </ul>

        <?php if ($is_logged_in): ?>
            <div class="user-menu">
                <div class="avatar-btn" onclick="toggleDD()">
                    <div class="avatar-circle"><?= strtoupper(substr($user_name, 0, 1)) ?></div>
                    <span class="avatar-name"><?= htmlspecialchars($user_name) ?></span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="dropdown" id="dropdown">
                    <a href="/Tripverse/app/controllers/AuthController.php?action=logout" class="logout">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Sign out
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="auth-btns">
                <a href="/Tripverse/app/views/auth/login.php" class="btn-login">Sign In</a>
                <a href="/Tripverse/app/views/auth/register.php" class="btn-register">Get Started</a>
            </div>
        <?php endif; ?>
    </nav>