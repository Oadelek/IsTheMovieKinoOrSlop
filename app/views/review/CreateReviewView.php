<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1>Create Review for <?php echo $data['movie_id']; ?></h1>
                <form action="/review/create/<?php echo $data['movie_id']; ?>" method="POST">
                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <input type="number" class="form-control" name="rating" placeholder="Rating" step="0.1" min="0" max="10" required>
                    </div>

                    <div class="form-group">
                        <label for="word_count">Word Count:</label>
                        <input type="number" class="form-control" name="word_count" placeholder="Word Count" min="50" max="250" value="200" required>
                    </div>

                    <div class="form-group">
                        <label for="humor_level">Humor Level:</label>
                        <input type="number" class="form-control" name="humor_level" placeholder="Humor Level" min="0" max="10" value="5" required>
                    </div>

                    <div class="form-group">
                        <label for="critic_level">Critic Level:</label>
                        <input type="number" class="form-control" name="critic_level" placeholder="Critic Level" min="0" max="10" value="5" required>
                    </div>

                    <div class="form-group">
                        <label for="style">Style:</label>
                        <select class="form-select" name="style">
                            <option value="formal">Formal</option>
                            <option value="informal" selected>Informal</option>
                            <option value="humorous">Humorous</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Review</button>
                </form>
            </div>
        </div>
    </div>
    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger"><?php echo $data['error']; ?></div>
    <?php endif; ?>
</main>