<form class="auth-card" method="post" action="<?= e(url('admin/login')) ?>">
    <p class="eyebrow">SLGTI administration</p>
    <h1>Sign in</h1>
    <?php if ($message = flash('error')): ?><div class="alert alert-danger"><?= e($message) ?></div><?php endif; ?>
    <?= csrf_field() ?>
    <label>Email <input type="email" name="email" required autocomplete="username"></label>
    <label>Password <input type="password" name="password" required autocomplete="current-password"></label>
    <button type="submit">Sign in</button>
    <a href="<?= e(url('/')) ?>">Return to the website</a>
</form>
