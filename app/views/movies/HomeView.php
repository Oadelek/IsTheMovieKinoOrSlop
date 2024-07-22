<main class="container mt-4">
    <h1 class="mb-4">Welcome<?php if (isset($_SESSION['username'])) echo ', ' . htmlspecialchars($_SESSION['username']); ?>!</h1>

    <?php if (isset($data['genres'])): ?>
        <?php foreach ($data['genres'] as $genre => $movies): ?>
            <h2 class="mt-4 mb-3"><?php echo htmlspecialchars($genre); ?></h2>
            <div class="row row-cols-1 row-cols-md-4 g-4 movie-slider">
                <?php foreach ($movies as $movie): ?>
                    <div class="col">
                        <div class="card h-100 movie-card">
                            <img src="<?php echo htmlspecialchars($movie['poster']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($movie['title']); ?> (<?php echo htmlspecialchars($movie['year']); ?>)</h5>
                                <a href="/movie/details/<?php echo $movie['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No movies available.</p>
    <?php endif; ?>
</main>