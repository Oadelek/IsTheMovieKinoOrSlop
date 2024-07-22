<main class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <img src="<?php echo $data['movie']['poster']; ?>" alt="<?php echo $data['movie']['title']; ?>" class="img-fluid rounded">
        </div>
        <div class="col-md-8">
            <h1><?php echo $data['movie']['title']; ?> (<?php echo $data['movie']['year']; ?>)</h1>
            <p><strong>Director:</strong> <?php echo $data['movie']['director']; ?></p>
            <p><?php echo $data['movie']['plot']; ?></p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="btn btn-outline-primary watchlist-toggle" data-movie-id="<?php echo $data['movie']['id']; ?>">
                    Add to Watchlist
                </button>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_SESSION['already_reviewed']) && $_SESSION['already_reviewed'] == true): ?>
        <div class="alert alert-info" role="alert">
            You have already reviewed this movie.
        </div>
        <?php unset($_SESSION['already_reviewed']); // Clear the message after showing it ?>
    <?php endif; ?>

    <h2 class="mt-4">Reviews</h2>
    <div id="reviews">
        <?php if (isset($data['reviews'])): ?>
            <?php foreach ($data['reviews'] as $review): ?>
                <div class="review-card">
                    <h5>Rating: <?php echo $review['rating']; ?>/10</h5>
                    <p><?php echo $review['content']; ?></p>
                    <?php if ($review['ai_generated']): ?>
                        <span class="ai-badge">AI Generated</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews yet.</p>
        <?php endif; ?>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <p>Please <a href="/auth/login">log in</a> to add a review.</p>
    <?php else: ?>
        <a href="/review/create/<?php echo $data['movie']['id']; ?>" class="btn btn-primary mt-3">Add a Review</a>
    <?php endif; ?>
</main>

<script>
document.querySelectorAll('.watchlist-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const movieId = this.dataset.movieId;
        fetch(`/movie/toggleWatchlist/${movieId}`, {method: 'POST'})
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.textContent = data.inWatchlist ? 'Remove from Watchlist' : 'Add to Watchlist';
                }
            });
    });
});
</script>