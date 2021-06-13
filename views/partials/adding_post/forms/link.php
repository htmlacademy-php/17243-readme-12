<form class="adding-post__form form" action="add.php?id=4" method="post">
    <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="link-heading">Заголовок <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['link-heading']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="link-heading" type="text" name="link-heading" value="<?= get_post_val('link-heading'); ?>" placeholder="Введите заголовок">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['link-heading'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__textarea-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['post-link']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="post-link" type="text" name="post-link" value="<?= get_post_val('post-link'); ?>">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['post-link'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="link-tags">Теги</label>
                <div class="form__input-section <?= isset($errors['link-tags']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="link-tags" type="text" name="link-tags" value="<?= get_post_val('link-tags'); ?>" placeholder="Введите ссылку">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['link-tags'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="form-name" value="link" />
        </div>
        <?php if (isset($errors)) :  ?>
            <?php if (isset($errors['link']) and !empty($errors['link'])) :  ?>
                <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">

                        <?php foreach ($errors['link'] as $key => $value) : ?>
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
