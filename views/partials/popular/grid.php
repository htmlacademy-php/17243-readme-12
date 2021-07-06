<div class="popular__posts">
    <?php foreach ($posts as $key => $post) : ?>
        <article class="popular__post post <?= $post['type'] ?? '' ?>">
            <header class="post__header">
                <h2>
                    <a href="/post.php?id=<?= $post['id'] ?>"><?= esc($post['title']) ?? '' ?></a>
                </h2>
            </header>
            <div class="post__main">
                <?php if (isset($post['type'])) : ?>
                    <?php if ($post['type'] === 'post-quote') : ?>
                        <?= include_template('partials/post_types/quote.php', [
                            'text' => $post['body'] ?? null,
                            'author' => $post['author_name'] ?? null
                        ]) ?>
                    <?php elseif ($post['type'] === 'post-text') : ?>
                        <?= include_template('partials/post_types/text.php', ['text' => $post['body'] ?? null]) ?>
                    <?php elseif ($post['type'] === 'post-photo') : ?>
                        <?= include_template('partials/post_types/photo.php', ['img_url' => $post['body'] ?? null]) ?>
                    <?php elseif ($post['type'] === 'post-link') : ?>
                        <?= include_template('partials/post_types/link.php', [
                            'url' => $post['body'] ?? null,
                            'title' => $post['title'] ?? null,
                            'url_desc' => $post['url_desc'] ?? null,
                            'mode' => 'preview'
                        ]) ?>
                    <?php elseif ($post['type'] === 'post-video') : ?>
                        <?= include_template('partials/post_types/video.php', ['youtube_url' => $post['body'] ?? null]) ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <footer class="post__footer">
                <div class="post__author">
                    <a class="post__author-link" href="#" title="Автор">
                        <div class="post__avatar-wrapper">
                            <img class="post__author-avatar" src="img/<?= $post['userpic'] ?? '' ?>" alt="Аватар пользователя">
                        </div>
                        <div class="post__info">
                            <b class="post__author-name">
                                <?= $post['username'] ?? '' ?>
                            </b>
                            <?php $random_date = generate_random_date($key) ?>
                            <time class="post__time" title="<?= date_format(date_create($random_date), 'd-m-Y H:i') ?>" datetime="<?= $random_date ?>"><?= get_human_readable_date($random_date) . '&nbsp;' . 'назад' ?>
                            </time>
                        </div>
                    </a>
                </div>
                <div class="post__indicators">
                    <div class="post__buttons">
                        <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                            <svg class="post__indicator-icon" width="20" height="17">
                                <use xlink:href="#icon-heart"></use>
                            </svg>
                            <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                <use xlink:href="#icon-heart-active"></use>
                            </svg>
                            <span>0</span>
                            <span class="visually-hidden">количество лайков</span>
                        </a>
                        <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                            <svg class="post__indicator-icon" width="19" height="17">
                                <use xlink:href="#icon-comment"></use>
                            </svg>
                            <span>0</span>
                            <span class="visually-hidden">количество комментариев</span>
                        </a>
                    </div>
                </div>
            </footer>
        </article>
    <?php endforeach; ?>
</div>
