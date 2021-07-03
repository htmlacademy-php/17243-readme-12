<div class="post-details__image-wrapper post-text">
    <div class="post__main">
        <?php if (isset($text)) : ?>
            <?php [$txt, $is_truncated] = truncate(esc($text)) ?>
            <p>
                <?= $is_truncated ? $txt . '&#8230;' : $txt ?>
            </p>
            <?= $is_truncated ? '<a class="post-text__more-link" href="#">Читать далее</a>' : '' ?>
        <?php endif; ?>
    </div>
</div>
