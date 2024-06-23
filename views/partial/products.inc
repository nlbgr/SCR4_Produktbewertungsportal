<table class="table align-middle">
    <thead>
        <tr>
            <th>Name</th>
            <th>Creator</th>
            <th>Manufacturer</th>
            <th>Nr. of Ratings</th>
            <th>Mean Rating</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['products'] as $product) : ?>
            <tr>
                <td><?php $htmlOut($product->name); ?></td>
                <td><?php $htmlOut($product->user); ?></td>
                <td><?php $htmlOut($product->manufacturer); ?></td>
                <td><?php $htmlOut($product->numOfRatings); ?></td>
                <td><?php $htmlOut($product->meanRating); ?></td>
                <td>
                    <div class="d-flex gap-1">
                        <?php $beginForm('Products', 'Details', ['pid' => $product->id, 'ctx' => $data['context']], 'get', 'form-inline'); ?>
                            <button class="btn btn-primary">Details</button>
                        <?php $endForm(); ?>

                        <?php if (isset($data['user']) && $product->user === $data['user']->userName): ?>
                            <?php $beginForm('Products', 'Delete', ['pid' => $product->id, 'ctx' => $data['context']], 'post', 'form-inline'); ?>
                                <button class="btn btn-danger">Delete</button>
                            <?php $endForm(); ?>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
