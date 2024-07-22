<main class="container mt-4">
    <h1>Preview Your Review</h1>
    <form action="/review/submitReview" method="POST">
        <input type="hidden" name="movie_id" value="<?php echo $data['movie_id']; ?>">
        <input type="hidden" name="rating" value="<?php echo $data['rating']; ?>">

        <div class="mb-3">
            <label for="content" class="form-label">Review Content</label>
            <textarea class="form-control" id="content" name="content" rows="10"><?php echo $data['content']; ?></textarea>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="ai_generated" name="ai_generated" value="1" <?php echo $data['ai_generated'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="ai_generated">Mark as AI-generated</label>
        </div>

        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
</main>