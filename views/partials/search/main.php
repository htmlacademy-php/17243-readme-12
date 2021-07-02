<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска</h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= isset($search) ? esc($search) : '' ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <div class="container">
                <?php if (empty($posts)) : ?>
                    <div class="search__no-results container">
                        <p class="search__no-results-info">К сожалению, ничего не найдено.</p>
                        <p class="search__no-results-desc">
                            Попробуйте изменить поисковый запрос или просто зайти в раздел &laquo;Популярное&raquo;, там живет самый крутой контент.
                        </p>
                        <div class="search__links">
                            <a class="search__popular-link button button--main" href="popular.php">Популярное</a>
                            <a class="search__back-link" href="javascript:history.go(-1)">Вернуться назад</a>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="search__content">
                        <?php foreach ($posts as $key => $post) : ?>
                            <?php if (isset($post['type'])) : ?>
                                <article class="search__post post <?= 'post-' . $post['type'] ?>">
                                    <header class="post__header post__author">
                                        <a class="post__author-link" href="#" title="Автор">
                                            <div class="post__avatar-wrapper">
                                                <img class="post__author-avatar" src="<?= isset($post['userpic']) ? 'uploads/' . $post['userpic'] : 'img/userpic-placeholder-medium.jpg' ?>" alt="Аватар пользователя" width="60" height="60">
                                            </div>
                                            <div class="post__info">
                                                <b class="post__author-name"><?= isset($post['username']) ? esc($post['username']) : '' ?></b>
                                                <span class="post__time"><?= isset($post['dt_add']) ? get_human_readable_date(esc($post['dt_add'])) . '&nbsp;' . 'назад' : '' ?></span>
                                            </div>
                                        </a>
                                    </header>
                                    <div class="post__main">

                                        <?php if ($post['type'] === 'quote') : ?>
                                            <?= include_template('partials/search/types/quote.php', ['text' => $post['body'], 'author' => $post['author_name']]) ?>
                                        <?php elseif ($post['type'] === 'text') : ?>
                                            <?= include_template('partials/search/types/text.php',  ['text' => $post['body'], 'title' => $post['title']]) ?>
                                        <?php elseif ($post['type'] === 'photo') : ?>
                                            <?= include_template('partials/search/types/photo.php', ['img_url' => $post['body']]) ?>
                                        <?php elseif ($post['type'] === 'link') : ?>
                                            <?= include_template('partials/search/types/link.php', ['url' => $post['body'], 'title' => $post['title']]) ?>
                                        <?php elseif ($post['type'] === 'video') : ?>
                                            <?= include_template('partials/search/types/video.php', ['youtube_url' => $post['body']]) ?>
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
                                        </div>
                                    </footer>
                                </article>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
