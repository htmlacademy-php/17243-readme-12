<div class="post-details__image-wrapper post-quote">
    <div class="post__main">
        <blockquote>
            <p>
            <?= isset($text) ? esc($text) : '' ?>
            </p>
            <cite><?= isset($author) ? esc($author) : 'Неизвестный автор' ?></cite>
        </blockquote>
    </div>
</div>
