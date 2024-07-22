<main>
    <h1>Search Movies</h1>
    <form action="/movies/search" method="GET">
        <input type="text" name="query" placeholder="Search for a movie...">
        <button type="submit">Search</button>
    </form>
    <?php if (isset($data['movies'])): ?>
        <ul>
            <?php foreach ($data['movies'] as $movie): ?>
                <li>
                    <a href="/movies/details/<?php echo $movie['id']; ?>">
                        <?php echo $movie['title']; ?> (<?php echo $movie['year']; ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>