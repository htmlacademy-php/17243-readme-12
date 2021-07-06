<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">
                    <?php foreach ($posts as $key => $post) : ?>
                        <?php if (isset($post['type'])) : ?>
                            <article class="feed__post post <?= 'post-' . $post['type'] ?>">
                                <header class="post__header post__author">
                                    <a class="post__author-link" href="#" title="Автор">
                                        <?php if (isset($post['userpic'])) : ?>
                                            <div class="post__avatar-wrapper">
                                                <img class="post__author-avatar" src="uploads/<?= esc($post['userpic']) ?>" alt="Аватар пользователя" width="60" height="60">
                                            </div>
                                        <?php endif; ?>
                                        <div class="post__info">
                                            <b class="post__author-name"><?= esc($post['username']) ?? '' ?></b>
                                            <time class="post-details__time user__time" datetime=<?= esc($post['dt_add']) ?? '' ?>>
                                                <?= isset($post['dt_add']) ? (get_human_readable_date(esc($post['dt_add'])) . '&nbsp;' . 'назад') : '' ?>
                                            </time>
                                        </div>
                                    </a>
                                </header>
                                <div class="post__main">
                                    <?php if ($post['type'] === 'quote') : ?>
                                        <?= include_template('partials/post_types/quote.php', ['text' => $post['body'] ?? null, 'author' => $post['author_name'] ?? null]) ?>
                                    <?php elseif ($post['type'] === 'text') : ?>
                                        <?= include_template('partials/post_types/text.php',  ['text' => $post['body'] ?? null, 'title' => $post['title'] ?? null]) ?>
                                    <?php elseif ($post['type'] === 'photo') : ?>
                                        <?= include_template('partials/post_types/photo.php', ['img_url' => $post['body'] ?? null]) ?>
                                    <?php elseif ($post['type'] === 'link') : ?>
                                        <?= include_template('partials/post_types/link.php', [
                                            'url' => $post['body'] ?? null,
                                            'title' => $post['title'] ?? null,
                                            'url_desc' => $post['url_desc'] ?? null
                                        ]) ?>
                                    <?php elseif ($post['type'] === 'video') : ?>
                                        <?= include_template('partials/post_types/video.php', ['youtube_url' => $post['body'] ?? null]) ?>
                                    <?php endif; ?>
                                </div>
                                <footer class="post__footer post__indicators">
                                    <div class="post__buttons">
                                        <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                            <svg class="post__indicator-icon" width="20" height="17">
                                                <use xlink:href="#icon-heart"></use>
                                            </svg>
                                            <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                                <use xlink:href="#icon-heart-active"></use>
                                            </svg>
                                            <span><?= $post['likes_count'] ?? '' ?></span>
                                            <span class="visually-hidden">количество лайков</span>
                                        </a>
                                        <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                            <svg class="post__indicator-icon" width="19" height="17">
                                                <use xlink:href="#icon-comment"></use>
                                            </svg>
                                            <span><?= $post['comments_count'] ?? '' ?></span>
                                            <span class="visually-hidden">количество комментариев</span>
                                        </a>
                                        <a class="post__indicator post__indicator--repost button" href="feed.php?post_id=<?= $post['id'] ?>" title="Репост">
                                            <svg class="post__indicator-icon" width="19" height="17">
                                                <use xlink:href="#icon-repost"></use>
                                            </svg>
                                            <span><?= $post['reposts_count'] ?? '' ?></span>
                                            <span class="visually-hidden">количество репостов</span>
                                        </a>
                                    </div>
                                </footer>
                            </article>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?= include_template('partials/feed/filters.php', [
                'active_category_id' => $active_category_id,
                'content_types' => $content_types
            ]) ?>
        </section>
        <?= include_template('partials/feed/promo.php') ?>
    </div>
</main>
