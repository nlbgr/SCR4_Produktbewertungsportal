<?php $render('partial/header', $data); ?>

    <h1>Signup</h1>

<?php $beginForm('User', 'SignUp', method: 'post'); ?>
    <div class="mb-3">
        <label for="username" class="form-label">User name</label>
        <input type="text" class="form-control" id="username" name="un" value="<?php $htmlOut($data['userName']); ?>"/>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" />
    </div>
    <div class="mb-3">
        <label for="repPassword" class="form-label">Repeat Password</label>
        <input type="password" class="form-control" id="repPassword" name="repPassword" />
    </div>
    <button class="btn btn-primary">SignUp</button>
    <p class="mt-5 mb-0">Already have an Account?</p>
    <p class="mt-0">Login using this link: <?php $link('Login', 'User', 'LogIn') ?> </p>
<?php $endForm(); ?>


<?php $render('partial/footer', $data); ?>