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
                        <!--здесь содержимое карточки-->
                        <?php if (isset($post['type'])) : ?>
                            <?php if ($post['type'] === 'post-quote') : ?>
                                <blockquote>
                                    <?php if (isset($post['body'])) : ?>
                                        <?php [$text, $is_truncated] = truncate(esc($post['body'])) ?>
                                        <p>
                                            <?= $is_truncated ? $text . '&#8230;' : $text ?>
                                        </p>
                                        <?= $is_truncated ? '<a class="post-text__more-link" href="#">Читать далее</a>' : '' ?>
                                    <?php endif; ?>
                                    <cite>Неизвестный Автор</cite>
                                </blockquote>

                            <?php elseif ($post['type'] === 'post-text') : ?>
                                <?php if (isset($post['body'])) : ?>
                                    <?php [$text, $is_truncated] = truncate(esc($post['body'])) ?>
                                    <p>
                                        <?= $is_truncated ? $text . '&#8230;' : $text ?>
                                    </p>
                                    <?= $is_truncated ? '<a class="post-text__more-link" href="#">Читать далее</a>' : '' ?>
                                <?php endif; ?>
                            <?php elseif ($post['type'] === 'post-photo') : ?>
                                <div class="post-photo__image-wrapper">
                                    <img src="img/<?= esc($post['body']) ?? '' ?>" alt="Фото от пользователя" width="360" height="240">
                                </div>

                            <?php elseif ($post['type'] === 'post-link') : ?>
                                <div class="post-link__wrapper">
                                    <a class="post-link__external" href="http://<?= esc($post['body']) ?? '' ?>" title="Перейти по ссылке">
                                        <div class="post-link__info-wrapper">
                                            <div class="post-link__icon-wrapper">
                                                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                            </div>
                                            <div class="post-link__info">
                                                <h3>
                                                    <?= esc($post['title']) ?? '' ?>
                                                </h3>
                                            </div>
                                        </div>
                                        <span>
                                            <?= esc($post['body']) ?? '' ?>
                                        </span>
                                    </a>
                                </div>

                            <?php elseif ($post['type'] === 'post-video') : ?>
                                <div class="post-video__block">
                                    <div class="post-video__preview">
                                        <?= embed_youtube_cover(esc($post['body']) ?? '') ?>

                                    </div>
                                    <a href="post-details.html" class="post-video__play-big button">
                                        <svg class="post-video__play-big-icon" width="14" height="14">
                                            <use xlink:href="#icon-video-play-big"></use>
                                        </svg>
                                        <span class="visually-hidden">Запустить проигрыватель</span>
                                    </a>
                                </div>

                            <?php endif; ?>
                        <?php endif; ?>

                    </div>
                    <footer class="post__footer">
                        <div class="post__author">
                            <a class="post__author-link" href="#" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <!--укажите путь к файлу аватара-->
                                    <img class="post__author-avatar" src="img/<?= $post['userpic'] ?? '' ?>" alt="Аватар пользователя">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name">
                                        <!--здесь имя пользоателя-->
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
