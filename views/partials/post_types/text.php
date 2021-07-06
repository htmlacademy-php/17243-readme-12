<?php if (isset($title)) : ?>
    <h2><a href="#"><?= esc($title) ?></a></h2>
<?php endif; ?>
<?php if (isset($text)) : ?>
    <?php [$txt, $is_truncated] = truncate(esc($text)) ?>
    <p>
        <?= $is_truncated ? $txt . '&#8230;' : $txt ?>
    </p>
    <?= $is_truncated ? '<a class="post-text__more-link" href="#">Читать далее</a>' : '' ?>
<?php endif; ?>
