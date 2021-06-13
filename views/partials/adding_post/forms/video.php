<form class="adding-post__form form" action="add.php?id=5" method="post" enctype="multipart/form-data">
    <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="video-heading">Заголовок <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['link-heading']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="video-heading" type="text" name="video-heading" value="<?= get_post_val('video-heading'); ?>" placeholder="Введите заголовок">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['video-heading'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['video-url']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="video-url" type="text" name="video-url" value="<?= get_post_val('video-url'); ?>" placeholder="Введите ссылку">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['video-url'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="video-tags">Теги</label>
                <div class="form__input-section <?= isset($errors['video-tags']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="video-tags" type="text" name="video-tags" value="<?= get_post_val('video-tags'); ?>" placeholder="Введите ссылку">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['video-tags'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="form-name" value="video" />
        </div>
        <?php if (isset($errors)) :  ?>
            <?php if (isset($errors['video']) and !empty($errors['video'])) :  ?>
                <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">

                        <?php foreach ($errors['video'] as $key => $value) : ?>
                            <li class="form__invalid-item"><?= $value ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="adding-post__buttons">
        <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
        <a class="adding-post__close" href="index.php">Закрыть</a>
    </div>
</form>
