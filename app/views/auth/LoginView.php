<main>
    <h1>Login</h1>
    <?php if (isset($data['error']) && !empty($data['error'])): ?>
        <p class="error"><?php echo htmlspecialchars($data['error']); ?></p>
    <?php endif; ?>
    <form action="/auth/login" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</main>