<div class="post-link__wrapper">
    <a class="post-link__external" href="<?= isset($url) ? esc($url) : '' ?>" title="Перейти по ссылке">
        <div class="post-link__icon-wrapper">
            <img src="<?= isset($url) ? 'https://www.google.com/s2/favicons?sz=128&domain_url=' . esc($url) : '' ?>" alt="Иконка">
        </div>
        <div class="post-link__info">
            <h3><?= isset($title) ? esc($title) : '' ?></h3>
            <span><?= isset($url) ? esc($url) : '' ?></span>
        </div>
        <svg class="post-link__arrow" width="11" height="16">
            <use xlink:href="#icon-arrow-right-ad"></use>
        </svg>
    </a>
</div>
