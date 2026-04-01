<?php if (has_flash('success')): ?>
    <div class="flash flash--success"><?= e(flash('success')); ?></div>
<?php endif; ?>

<?php if (has_flash('error')): ?>
    <div class="flash flash--error"><?= e(flash('error')); ?></div>
<?php endif; ?>
