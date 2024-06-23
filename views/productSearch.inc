<?php $render ('partial/header', $data); ?>

    <h1>Search Site</h1>

    <div class="my-3">
        <?php $beginForm('Products', 'Search'); ?>
            <div class="row g-3">
                <div class="col-auto">
                    <input class="form-control" name="f" value="<?php $htmlOut($data['filter'])?>" />
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Search</button>
                </div>
            </div>
        <?php $endForm(); ?>
    </div>

    <?php if ($data['products'] != null): ?>
        <?php if (sizeof($data['products']) > 0) {
            $render('partial/products', array(
                'user' => $data['user'],
                'products' => $data['products'],
                'context' => $data['context']
            ));
        } else { ?>
            <p>No matching books found</p>
        <?php } ?>
    <?php endif; ?>

<?php $render ('partial/footer', $data); ?>







