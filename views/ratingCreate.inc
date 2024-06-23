<?php $render('partial/header', $data); ?>

<h1>Rate Product: <?php $htmlOut($data['pname']); ?></h1>

<?php $beginForm('Rating', 'Create', method: 'post'); ?>
    <input type="hidden" name="pid" value="<?php $htmlOut($data['pid']); ?>" />
    <div class="mb-3">
        <label for="grade" class="form-label">Grade</label>
        <select name="grade" id="grade" class="form-select">
            <option selected disabled>Select your grade</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="comment" class="form-label">Comment</label>
        <input type="text" class="form-control" id="comment" name="comment" value="<?php $htmlOut($data['comment']); ?>" />
    </div>
    <button class="btn btn-primary">Create</button>
<?php $endForm(); ?>

<?php $render('partial/footer', $data); ?>
