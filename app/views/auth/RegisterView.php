<main>
    <h1>Register</h1>
    <?php if (isset($data['error']) && !empty($data['error'])): ?>
        <p class="error"><?php echo htmlspecialchars($data['error']); ?></p>
    <?php endif; ?>
    <form action="/auth/register" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</main>
