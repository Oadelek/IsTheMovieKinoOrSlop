<main>
    <h1>Welcome<?php if (isset($_SESSION['username'])) echo ', ' . htmlspecialchars($_SESSION['username']); ?>!</h1>
    <h1>Movies by Genre</h1>

    <?php if (isset($data['genres'])): ?>
        <?php foreach ($data['genres'] as $genre => $movies): ?>
            <h2><?php echo htmlspecialchars($genre); ?></h2>
            <div class="movie-slider">
                <?php foreach ($movies as $movie): ?>
                    <div class="movie-item">
                        <a href="/movie/details/<?php echo $movie['id']; ?>">
                            <img src="<?php echo htmlspecialchars($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            <p><?php echo htmlspecialchars($movie['title']); ?> (<?php echo htmlspecialchars($movie['year']); ?>)</p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No movies available.</p>
    <?php endif; ?>
</main>
