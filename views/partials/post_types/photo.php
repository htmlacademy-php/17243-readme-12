<?php if (isset($title)) : ?>
    <h2><a href="#"><?= esc($title) ?></a></h2>
<?php endif; ?>
<div class="post-photo__image-wrapper">
    <img src="<?= isset($img_url) ? 'uploads/' . esc($img_url) : '' ?>" alt="Фото от пользователя" width="760" height="396">
</div>
