<main>
    <h1><?php echo $data['movie']['title']; ?> (<?php echo $data['movie']['year']; ?>)</h1>
    <p><strong>Director:</strong> <?php echo $data['movie']['director']; ?></p>
    <p><?php echo $data['movie']['plot']; ?></p>
    <img src="<?php echo $data['movie']['poster']; ?>" alt="<?php echo $data['movie']['title']; ?>">

    <h2>Reviews</h2>
    <?php if (isset($data['reviews'])): ?>
        <ul>
            <?php foreach ($data['reviews'] as $review): ?>
                <li>
                    <strong><?php echo $review['rating']; ?>/10</strong>
                    <p><?php echo $review['content']; ?></p>
                    <p><em>AI Generated: <?php echo $review['ai_generated'] ? 'Yes' : 'No'; ?></em></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>

    <a href="/review/create/<?php echo $data['movie']['id']; ?>">Add a Review</a>
</main>