<?php $render('partial/header', $data); ?>

<h1>Edit rating on: <?php $htmlOut($data['pname']); ?></h1>

<?php $beginForm('Rating', 'Edit', method: 'post'); ?>
    <input type="hidden" name="rid" value="<?php $htmlOut($data['rid']) ?>" />
    <div class="mb-3">
        <label for="grade" class="form-label">Grade</label>
        <select name="grade" id="grade" class="form-select">
            <option <?php echo !isset($data['grade']) ? 'selected' : ''; ?> disabled>Select your grade</option>
            <option <?php echo $data['grade'] === 1 ? 'selected' : ''; ?> value="1">1</option>
            <option <?php echo $data['grade'] === 2 ? 'selected' : ''; ?> value="2">2</option>
            <option <?php echo $data['grade'] === 3 ? 'selected' : ''; ?> value="3">3</option>
            <option <?php echo $data['grade'] === 4 ? 'selected' : ''; ?> value="4">4</option>
            <option <?php echo $data['grade'] === 5 ? 'selected' : ''; ?> value="5">5</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="comment" class="form-label">Comment</label>
        <input type="text" class="form-control" id="comment" name="comment" value="<?php $htmlOut($data['comment']); ?>" />
    </div>
    <button class="btn btn-primary">Save</button>
<?php $endForm(); ?>

<?php $render('partial/footer', $data); ?>
