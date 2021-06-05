<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication">
            Наконец, обработала фотки!
        </h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper post-photo">
                <div class="post-details__main-block post post--details">
                    <?php if (isset($post['type'])) : ?>
                        <?php if ($post['type'] === 'quote') : ?>
                            <?= include_template('partials/post/types/quote.php', ['text' => $post['body'], 'author' => 'Неизвестный Автор']); ?>
                        <?php elseif ($post['type'] === 'text') : ?>
                            <?= include_template('partials/post/types/text.php',  ['text' => $post['body']]); ?>
                        <?php elseif ($post['type'] === 'photo') : ?>
                            <?= include_template('partials/post/types/photo.php', ['img_url' => $post['body']]); ?>
                        <?php elseif ($post['type'] === 'link') : ?>
                            <?= include_template('partials/post/types/link.php', ['url' => $post['body'], 'title' => $post['title']]); ?>
                        <?php elseif ($post['type'] === 'video') : ?>
                            <?= include_template('partials/post/types/video.php', ['youtube_url' => $post['body']]); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="
                        post__indicator-icon post__indicator-icon--like-active
                      " width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span><?= $post['likes_count'] ?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?= $post['comments_count'] ?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?= intval($post['original_post_id']) ?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <span class="post__view"><?= $post['views_count'] . '&nbsp' . get_noun_plural_form($post['views_count'], 'просмотр', 'просмотра', 'просмотров') ?></span>
                    </div>
                    <ul class="post__tags">
                        <?php if ($hashtags) : ?>
                            <?php foreach ($hashtags as $key => $value) : ?>
                                <li><a href="#"><?= esc($value) ?></a></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                    <div class="comments">
                        <form class="comments__form form" action="#" method="post">
                            <div class="comments__my-avatar">
                                <img class="comments__picture" src="img/userpic-medium.jpg" alt="Аватар пользователя" />
                            </div>
                            <div class="form__input-section form__input-section--error">
                                <textarea class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий"></textarea>
                                <label class="visually-hidden">Ваш комментарий</label>
                                <button class="form__error-button button" type="button">
                                    !
                                </button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка валидации</h3>
                                    <p class="form__error-desc">
                                        Это поле обязательно к заполнению
                                    </p>
                                </div>
                            </div>
                            <button class="comments__submit button button--green" type="submit">
                                Отправить
                            </button>
                        </form>
                        <div class="comments__list-wrapper">
                            <ul class="comments__list">
                                <?php foreach ($comments as $key => $comment) : ?>
                                    <li class="comments__item user">
                                        <?php if (isset($comment['userpic'])) : ?>
                                            <div class="comments__avatar">
                                                <a class="user__avatar-link" href="#">
                                                    <img class="comments__picture" src="img/<?= esc($comment['userpic']) ?>" alt="Аватар пользователя" />
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name" href="#">
                                                    <span><?= esc($comment['username']) ?? '' ?></span>
                                                </a>
                                                <time class="post__time" title="<?= date_format(date_create($comment['dt_add']), 'd-m-Y H:i') ?>" datetime="<?= $comment['dt_add'] ?>"><?= get_human_readable_date($comment['dt_add']) . '&nbsp;' . 'назад' ?></time>
                                            </div>
                                            <p class="comments__text"><?= $comment['text'] ?? '' ?></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>

                            </ul>
                            <a class="comments__more-link" href="#">
                                <span>Показать все комментарии</span>
                                <sup class="comments__amount"><?= $post['comments_count'] ?></sup>
                            </a>
                        </div>
                    </div>
                </div>
                <?php if ($user_details) : ?>
                    <div class="post-details__user user">
                        <div class="post-details__user-info user__info">
                            <?php if (isset($user_details['userpic'])) : ?>
                                <div class="post-details__avatar user__avatar">
                                    <a class="post-details__avatar-link user__avatar-link" href="#">
                                        <img class="post-details__picture user__picture" src="img/<?= esc($user_details['userpic']) ?>" alt="Аватар пользователя" />
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="post-details__name-wrapper user__name-wrapper">
                                <a class="post-details__name user__name" href="#">
                                    <span><?= esc($user_details['username']) ?? '' ?></span>
                                </a>
                                <time class="post-details__time user__time" datetime=<?= esc($user_details['dt_add']) ?>><?= get_human_readable_date(esc($user_details['dt_add'])) . '&nbsp;' . 'на&nbsp;сайте' ?></time>
                            </div>
                        </div>
                        <div class="post-details__rating user__rating">
                            <p class="
                    post-details__rating-item
                    user__rating-item user__rating-item--subscribers
                  ">
                                <span class="post-details__rating-amount user__rating-amount"><?= $user_details['subscribers_count'] ?? 0 ?></span>
                                <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form($user_details['subscribers_count'], 'подписчик', 'подписчика', 'подписчиков') ?></span>
                            </p>
                            <p class="
                    post-details__rating-item
                    user__rating-item user__rating-item--publications
                  ">
                                <span class="post-details__rating-amount user__rating-amount"><?= $user_details['posts_count'] ?? 0 ?></span>
                                <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form($user_details['posts_count'], 'публикация', 'публикации', 'публикаций') ?></span>
                            </p>
                        </div>
                        <div class="post-details__user-buttons user__buttons">
                            <button class="
                    user__button user__button--subscription
                    button button--main
                  " type="button">
                                Подписаться
                            </button>
                            <a class="
                    user__button user__button--writing
                    button button--green
                  " href="#">Сообщение</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>
