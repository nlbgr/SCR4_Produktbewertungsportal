<?php $render('partial/header', $data); ?>

    <h1 class="mb-5">Products</h1>

    <div class="mb-2">
        <?php $link('CreateProduct', 'Products', 'Create',
            cssClass: (!isset($data['user']) ? 'btn btn-secondary disabled' : 'btn btn-primary')) ?>
    </div>

    <?php if(sizeof($data['products']) > 0) { ?>
        <?php $render('partial/products', array(
            'user' => $data['user'],
            'products' => $data['products'],
            'context' => $data['context']
        )); ?>
    <?php } else { ?>
        <p>No Products found.</p>
    <?php } ?>

<?php $render('partial/footer', $data); ?>