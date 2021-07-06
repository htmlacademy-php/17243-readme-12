<div class="post-link__wrapper">
    <a class="post-link__external" href="<?= isset($url) ? esc($url) : '' ?>" title="Перейти по ссылке">
        <?php if (isset($mode) && $mode === 'preview') : ?>
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                    <img src="<?= isset($url) ? 'https://www.google.com/s2/favicons?domain_url=' . esc($url) : '' ?>" alt="Иконка">
                </div>
                <div class="post-link__info">
                    <h3><?= isset($url_desc) ? esc($url_desc) : '' ?></h3>
                </div>
            </div>
            <span><?= isset($url) ? esc($url) : '' ?></span>
            <svg class="post-link__arrow" width="11" height="16">
                <use xlink:href="#icon-arrow-right-ad"></use>
            </svg>
        <?php else : ?>
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                    <img src="<?= isset($url) ? 'https://www.google.com/s2/favicons?sz=128&domain_url=' . esc($url) : '' ?>" alt="Иконка">
                </div>
                <div class="post-link__info">
                    <h3><?= isset($url_desc) ? esc($url_desc) : '' ?></h3>
                    <span><?= isset($url) ? esc($url) : '' ?></span>
                </div>
            </div>

            <svg class="post-link__arrow" width="11" height="16">
                <use xlink:href="#icon-arrow-right-ad"></use>
            </svg>
        <?php endif; ?>
    </a>
</div>
