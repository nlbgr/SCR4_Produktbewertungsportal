<?php $render('partial/header', $data); ?>

        <h1>Books by category</h1>

    <?php if(sizeof($data['products']) > 0) { ?>
        <?php $render('partial/products', array(
            'products' => $data['products'],
            'context' => $data['context']
        )); ?>
    <?php } else { ?>
        <p>No books in this category.</p>
    <?php } ?>

<?php $render('partial/footer', $data); ?>