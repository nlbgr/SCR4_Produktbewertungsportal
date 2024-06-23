<div class="d-flex flex-column gap-1 border border-2 rounded p-1">
    <div class="d-flex justify-content-between">
        <p class="mb-0">
            From:
            <strong>
                <?php $htmlOut($data['rating']->user) ?>
            </strong>
        </p>
        <p class="mb-0">
            <?php $htmlOut($data['rating']->datetime) ?>
        </p>
    </div>
    <p class="mb-0">Rating: <strong><?php $htmlOut($data['rating']->grade) ?></strong></p>
    <?php if ($data['rating']->comment !== ''): ?>
        <div class="d-flex">
            <p class="mb-0">
                Comment:
                <strong>
                    <?php $htmlOut($data['rating']->comment) ?>
                </strong>
            </p>
        </div>
    <?php endif; ?>

    <?php if (isset($data['user']) && $data['rating']->user === $data['user']->userName): ?>
        <div class="d-flex gap-1">
            <?php $beginForm('Products', 'EditRating', ['rid' => $data['rating']->id, 'ctx' => $data['context']], 'post', 'form-inline'); ?>
                <button class="btn btn-warning">Edit</button>
            <?php $endForm(); ?>

            <?php $beginForm('Products', 'DeleteRating', ['rid' => $data['rating']->id, 'ctx' => $data['context']], 'post', 'form-inline'); ?>
                <button class="btn btn-danger">Delete</button>
            <?php $endForm(); ?>
        </div>
    <?php endif; ?>
</div>