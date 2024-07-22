<main class="container mt-4">
    <h1>User Profile</h1>

    <div class="row mt-4">
        <div class="col-md-6">
            <h2>Viewing History</h2>
            <ul class="list-group">
                <?php foreach ($data['viewingHistory'] as $movie): ?>
                    <li class="list-group-item"><?php echo $movie['title']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-md-6">
            <h2>Watchlist</h2>
            <ul class="list-group">
                <?php foreach ($data['watchlist'] as $movie): ?>
                    <li class="list-group-item"><?php echo $movie['title']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h2>Your Reviews</h2>
            <?php foreach ($data['reviews'] as $review): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $review['movie_title']; ?></h5>
                        <p class="card-text">Rating: <?php echo $review['rating']; ?>/10</p>
                        <p class="card-text"><?php echo $review['content']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="col-md-6">
            <h2>AI Review Settings</h2>
            <form action="/user/updateAISettings" method="POST">
                <div class="mb-3">
                    <label for="word_count" class="form-label">Word Count</label>
                    <input type="number" class="form-control" id="word_count" name="word_count" value="<?php echo $data['aiSettings']['word_count']; ?>">
                </div>
                <div class="mb-3">
                    <label for="humor_level" class="form-label">Humor Level</label>
                    <input type="range" class="form-range" id="humor_level" name="humor_level" min="1" max="10" value="<?php echo $data['aiSettings']['humor_level']; ?>">
                </div>
                <div class="mb-3">
                    <label for="critic_level" class="form-label">Critic Level</label>
                    <input type="range" class="form-range" id="critic_level" name="critic_level" min="1" max="10" value="<?php echo $data['aiSettings']['critic_level']; ?>">
                </div>
                <div class="mb-3">
                    <label for="style" class="form-label">Style</label>
                    <select class="form-select" id="style" name="style">
                        <option value="formal" <?php echo $data['aiSettings']['style'] == 'formal' ? 'selected' : ''; ?>>Formal</option>
                        <option value="informal" <?php echo $data['aiSettings']['style'] == 'informal' ? 'selected' : ''; ?>>Informal</option>
                        <option value="humorous" <?php echo $data['aiSettings']['style'] == 'humorous' ? 'selected' : ''; ?>>Humorous</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Settings</button>
            </form>
        </div>
    </div>
</main>