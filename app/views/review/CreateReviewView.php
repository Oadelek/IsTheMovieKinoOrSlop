<main>
    <h1>Create Review for <?php echo $data['movie_id']; ?></h1>
    <form action="/review/create/<?php echo $data['movie_id']; ?>" method="POST">
        <input type="number" name="rating" placeholder="Rating" step="0.1" min="0" max="10" required>
        <input type="number" name="word_count" placeholder="Word Count" value="200">
        <input type="number" name="humor_level" placeholder="Humor Level" value="5">
        <input type="number" name="critic_level" placeholder="Critic Level" value="5">
        <select name="style">
            <option value="formal">Formal</option>
            <option value="informal" selected>Informal</option>
            <option value="humorous">Humorous</option>
        </select>
        <button type="submit">Create Review</button>
    </form>
    <?php if (isset($data['error'])): ?>
        <p><?php echo $data['error']; ?></p>
    <?php endif; ?>
</main>