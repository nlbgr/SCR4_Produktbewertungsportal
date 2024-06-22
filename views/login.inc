<?php $render('partial/header', $data); ?>

    <h1>Login</h1>

    <?php $beginForm('User', 'LogIn', method: 'post'); ?>
        <div class="mb-3">
            <label for="username" class="form-label">User name</label>
            <input type="text" class="form-control" id="username" name="un" value="<?php $htmlOut($data['userName']); ?>"/>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" />
        </div>
        <button class="btn btn-primary">Login</button>
        <p class="mt-5 mb-0">Don't have an Account?</p>
        <p class="mt-0">SignUp using this link: <?php $link('SignUp', 'User', 'SignUp') ?> </p>
    <?php $endForm(); ?>


<?php $render('partial/footer', $data); ?>
