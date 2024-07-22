<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1>Register</h1>
                <?php if (isset($data['error']) && !empty($data['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($data['error']); ?></div>
                <?php endif; ?>
                <form action="/auth/register" method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="invalid-feedback">
                            Please provide a username.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Please provide a valid email address.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">
                            Please provide a password.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</main>