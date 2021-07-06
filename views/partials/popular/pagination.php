<?php if (isset($pages_count) && $pages_count > 1) : ?>
    <?php if (isset($cur_page)) : ?>
        <div class="popular__page-links">
            <a class="
                popular__page-link
                <?= ($cur_page <= 1) ? 'popular__page-link--disabled' : '' ?>
                popular__page-link--prev
                button <?= ($cur_page <= 1) ? 'button--gray' : 'button--main' ?>
                " href="popular.php?page=<?= $cur_page - 1 ?>">Предыдущая страница</a>
            <a class="
                popular__page-link
                <?= ($cur_page === $pages_count) ? 'popular__page-link--disabled' : '' ?>
                popular__page-link--next
                button <?= ($cur_page === $pages_count) ? 'button--gray' : 'button--main' ?>
                " href="popular.php?page=<?= $cur_page + 1 ?>">Следующая страница</a>
        </div>
    <?php endif; ?>
<?php endif; ?>
