<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <?php foreach ($content_types as $key => $content_type) : ?>
                            <li class="adding-post__tabs-item filters__item">
                                <a class="
                                    adding-post__tabs-link
                                    filters__button
                                    <?= $content_type['classname'] ? 'filters__button--' . esc($content_type['classname']) : '' ?>
                                    tabs__item
                                    <?= (isset($active_category_id) and $active_category_id == $content_type['id']) ? 'tabs__item--active filters__button--active' : '' ?>
                                    button
                                    " href="#" onclick="window.location='<?= '/add.php?id=' . ($key + 1) ?>'">
                                    <?php if ($content_type['classname']) : ?>
                                        <svg class="filters__icon" width="24" height="100%">
                                            <use xlink:href="#icon-filter<?= "-" . esc($content_type['classname']) ?>"></use>
                                        </svg>
                                    <?php endif; ?>
                                    <span><?= esc($content_type['name']) ?? '' ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <?php foreach ($content_types as $key => $content_type) : ?>
                        <section class="
                            <?= ($content_type['classname'] ? 'adding-post__' . esc($content_type['classname']) : '') ?>
                            tabs__content
                            <?= (isset($active_category_id) and $active_category_id == $content_type['id']) ? 'tabs__content--active' : '' ?>
                            ">
                            <?php if (isset($content_type['id'])) : ?>
                                <?php if ($content_type['id'] == 1) : ?>
                                    <h2 class="visually-hidden">Форма добавления цитаты</h2>
                                    <?= include_template('partials/adding_post/forms/quote.php', ['errors' => $errors ?? null]) ?>
                                <?php elseif ($content_type['id'] == 2) : ?>
                                    <h2 class="visually-hidden">Форма добавления текста</h2>
                                    <?= include_template('partials/adding_post/forms/text.php', ['errors' => $errors ?? null]) ?>
                                <?php elseif ($content_type['id'] == 3) : ?>
                                    <h2 class="visually-hidden">Форма добавления фото</h2>
                                    <?= include_template('partials/adding_post/forms/photo.php', ['errors' => $errors ?? null]) ?>
                                <?php elseif ($content_type['id'] == 4) : ?>
                                    <h2 class="visually-hidden">Форма добавления ссылки</h2>
                                    <?= include_template('partials/adding_post/forms/link.php', ['errors' => $errors ?? null]) ?>
                                <?php elseif ($content_type['id'] == 5) : ?>
                                    <h2 class="visually-hidden">Форма добавления видео</h2>
                                    <?= include_template('partials/adding_post/forms/video.php', ['errors' => $errors ?? null]) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </section>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>
