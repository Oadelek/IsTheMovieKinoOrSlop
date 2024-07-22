<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1>Search Movies</h1>
                <form action="/movie/search" method="GET" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="query" class="form-label">Search for a movie</label>
                        <input type="text" class="form-control" id="query" name="query" required>
                        <div class="invalid-feedback">
                            Please provide a search term.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <?php if (isset($data['movies']) && !empty($data['movies'])): ?>
                    <div class="row row-cols-1 row-cols-md-4 g-4 movie-slider">
                        <?php foreach ($data['movies'] as $movie): ?>
                            <div class="col">
                                <div class="card h-100 movie-card">
                                    <img src="<?php echo htmlspecialchars($movie['poster']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($movie['title']); ?> (<?php echo htmlspecialchars($movie['year']); ?>)</h5>
                                        <a href="/movie/details/<?php echo htmlspecialchars($movie['id']); ?>" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (isset($data['movies'])): ?>
                    <p>No movies found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>