<?php $render('partial/header', $data); ?>

    <div class="d-flex justify-content-between mb-3">
        <h1 class="mb-0">Details for: <?php $htmlOut($data['product']->name) ?></h1>
        <div class="d-flex gap-1 mt-auto mb-auto">
            <?php if (isset($data['user']) && $data['product']->user === $data['user']->userName): ?>
                <?php $beginForm('Products', 'Edit', ['pid' => $data['product']->id, 'ctx' => $data['context']], 'get', 'form-inline'); ?>
                    <button class="btn btn-warning">Edit</button>
                <?php $endForm(); ?>

                <?php $beginForm('Products', 'Delete', ['pid' => $data['product']->id, 'ctx' => $data['context']], 'post', 'form-inline'); ?>
                    <button class="btn btn-danger">Delete</button>
                <?php $endForm(); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="d-flex flex-column gap-2 mb-5">
        <p class="mb-0"><strong>Manufacturer: </strong> <?php $htmlOut($data['product']->manufacturer) ?> </p>
        <p class="mb-0"><strong>Created by: </strong> <?php $htmlOut($data['product']->user) ?> </p>
        <p class="mb-0"><strong>Ratings: </strong> <?php $htmlOut($data['product']->numOfRatings) ?>
            ( Mean: <?php $htmlOut(round($data['product']->meanRating, 2)) ?>) </p>
    </div>

    <div class="d-flex justify-content-between">
        <h2>Ratings</h2>
        <div class="mb-2">
            <?php $beginForm('Rating', 'Create', ['pid' => $data['product']->id, 'ctx' => $data['context']], 'get', 'form-inline'); ?>
                <button class="<?php echo (!isset($data['user']) ? 'btn btn-secondary disabled' : 'btn btn-primary') ?>">Add Rating</button>
            <?php $endForm(); ?>
        </div>
    </div>
    <div class="d-flex flex-column gap-2">
        <?php foreach ($data['ratings'] as $rating): ?>
            <?php $render('partial/rating', $data + ['rating' =>$rating]) ?>
        <?php endforeach; ?>
    </div>

<?php $render('partial/footer', $data); ?>