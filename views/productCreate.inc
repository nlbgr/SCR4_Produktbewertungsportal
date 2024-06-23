<?php $render('partial/header', $data); ?>

<h1>Create Product</h1>

<?php $beginForm('Products', 'Create', method: 'post'); ?>
    <div class="mb-3">
        <label for="pname" class="form-label">Product Name</label>
        <input type="text" class="form-control" id="pname" name="pname" value="<?php $htmlOut($data['pname']); ?>" />
    </div>
    <div class="mb-3">
        <label for="mname" class="form-label">Manufacturer</label>
        <input type="text" class="form-control" id="mname" name="mname" value="<?php $htmlOut($data['mname']); ?>" />
    </div>
    <button class="btn btn-primary">Create</button>
<?php $endForm(); ?>


<?php $render('partial/footer', $data); ?>
