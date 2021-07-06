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
                            <?= is_null($content_type_id) ? 'filters__button--active' : '' ?>
                            " href="popular.php">
                    <span>Все</span>
                </a>
            </li>
            <?php foreach ($content_types as $key => $content_type) : ?>
                <li class="popular__filters-item filters__item">
                    <a class="
                                filters__button
                                <?= $content_type['classname'] ? 'filters__button--' . esc($content_type['classname']) : '' ?>
                                <?= !is_null($content_type_id) && $content_type['id'] === $content_type_id ? 'filters__button--active' : '' ?>
                                button
                                " href="popular.php?id=<?= $content_type['id'] ?>">
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
