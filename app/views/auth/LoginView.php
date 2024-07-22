<main>
    <h1>Login</h1>
    <form action="/auth/login" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <?php if (isset($data['error'])): ?>
        <p><?php echo $data['error']; ?></p>
    <?php endif; ?>
</main>