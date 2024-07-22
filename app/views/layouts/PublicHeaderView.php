<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Is The Movie Kino Or Sleep</title>
    <link rel="stylesheet" href="/public/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/movie/index">Search Movies</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/auth/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/auth/login">Login</a></li>
                    <li><a href="/auth/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>