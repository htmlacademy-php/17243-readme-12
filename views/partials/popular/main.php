<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="
                            filters__button
                            filters__button--ellipse filters__button--all
                            <?= is_null($params['content_type_id']) ? 'filters__button--active' : '' ?>
                            " href="/index.php">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach ($content_types as $key => $content_type) : ?>
                        <li class="popular__filters-item filters__item">
                            <a class="
                                filters__button
                                <?= $content_type['classname'] ? 'filters__button--' . esc($content_type['classname']) : '' ?>
                                <?= !is_null($params['content_type_id']) && $content_type['id'] === $params['content_type_id'] ? 'filters__button--active' : '' ?>
                                button
                                " href="/index.php?id=<?= $content_type['id'] ?>">
                                <span class="visually-hidden"><?= esc($content_type['name']) ?? '' ?></span>
                                <?php if ($content_type['classname']) : ?>
                                    <svg class="filters__icon" width="24" height="100%">
                                        <use xlink:href="#icon-filter<?= "-" . esc($content_type['classname']) ?>"></use>
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach ($posts as $key => $post) : ?>
                <article class="popular__post post <?= $post['type'] ?? '' ?>">
                    <header class="post__header">
                        <a href="/post.php?id=<?= $post['id'] ?>">
                            <h2>
                                <?= esc($post['title']) ?? '' ?>
                            </h2>
                        </a>
                    </header>
                    <div class="post__main">
                        <?php if (isset($post['type'])) : ?>
                            <?php if ($post['type'] === 'post-quote') : ?>
                                <?= include_template('partials/popular/types/quote.php', ['text' => $post['body'], 'author' => $post['author_name']]) ?>
                            <?php elseif ($post['type'] === 'post-text') : ?>
                                <?= include_template('partials/popular/types/text.php', ['text' => $post['body']]) ?>
                            <?php elseif ($post['type'] === 'post-photo') : ?>
                                <?= include_template('partials/popular/types/photo.php', ['img_url' => $post['body']]) ?>
                            <?php elseif ($post['type'] === 'post-link') : ?>
                                <?= include_template('partials/popular/types/link.php', ['url' => $post['body'], 'title' => $post['title']]) ?>
                            <?php elseif ($post['type'] === 'post-video') : ?>
                                <?= include_template('partials/popular/types/video.php', ['youtube_url' => $post['body']]) ?>
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
                                    <time class="post__time" title="<?= date_format(date_create($random_date), 'd-m-Y H:i') ?>" datetime="<?= $random_date ?>"><?= get_human_readable_date($random_date) . '&nbsp;' . 'назад' ?></time>
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
    </div>
</section>
