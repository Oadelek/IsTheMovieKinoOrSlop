<main>
    <h1>Featured Movies by Genre</h1>
    <?php foreach ($data as $genre => $movies): ?>
        <section>
            <h2><?php echo htmlspecialchars($genre); ?></h2>
            <div class="slider">
                <?php foreach ($movies as $movie): ?>
                    <div class="slider-item">
                        <a href="/movie/details/<?php echo htmlspecialchars($movie['id']); ?>">
                            <img src="<?php echo htmlspecialchars($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            <p><?php echo htmlspecialchars($movie['title']); ?> (<?php echo htmlspecialchars($movie['year']); ?>)</p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</main>