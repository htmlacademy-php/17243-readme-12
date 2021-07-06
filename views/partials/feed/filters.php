<ul class="feed__filters filters">
    <li class="feed__filters-item filters__item">
        <a class="filters__button
        <?= (isset($active_category_id) && $active_category_id == 0) ? 'filters__button--active' : '' ?>" href="#" onclick="window.location='/feed.php?content_type_id=0'">
            <span>Все</span>
        </a>
    </li>
    <?php foreach ($content_types as $key => $content_type) : ?>
        <li class="feed__filters-item filters__item">
            <span class="visually-hidden"><?= esc($content_type['name']) ?? '' ?></span>
            <a class="
                filters__button
                <?= $content_type['classname'] ? 'filters__button--' . esc($content_type['classname']) : '' ?>
                <?= (isset($active_category_id) && $active_category_id == $content_type['id']) ? 'filters__button--active' : '' ?>
                button
                " href="#" onclick="window.location='<?= '/feed.php?content_type_id=' . ($key + 1) ?>'">
                <?php if ($content_type['classname']) : ?>
                    <svg class="filters__icon" width="24" height="100%">
                        <use xlink:href="#icon-filter<?= "-" . esc($content_type['classname']) ?>"></use>
                    </svg>
                <?php endif; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
