<main>
    <h1>Search Movies</h1>
    <form action="/movie/search" method="GET">
        <input type="text" name="query" placeholder="Search for a movie...">
        <button type="submit">Search</button>
    </form>
    <?php if (isset($data['movies']) && !empty($data['movies'])): ?>
        <ul>
            <?php foreach ($data['movies'] as $movie): ?>
                <li>
                    <a href="/movie/details/<?php echo htmlspecialchars($movie['id']); ?>">
                        <?php echo htmlspecialchars($movie['title']); ?>
                    </a>
                    <?php if (isset($movie['omdb_data'])): ?>
                        <p>Director: <?php echo htmlspecialchars($movie['omdb_data']['Director']); ?></p>
                        <p>Plot: <?php echo htmlspecialchars($movie['omdb_data']['Plot']); ?></p>
                        <img src="<?php echo htmlspecialchars($movie['omdb_data']['Poster']); ?>" alt="Poster">
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (isset($data['movies'])): ?>
        <p>No movies found.</p>
    <?php endif; ?>
</main>