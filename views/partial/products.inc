<table class="table align-middle">
    <thead>
        <tr>
            <th>Name</th>
            <th>Creator</th>
            <th>Manufacturer</th>
            <th>Nr. of Ratings</th>
            <th>Mean Rating</th>
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
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
