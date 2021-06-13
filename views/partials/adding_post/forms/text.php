<form class="adding-post__form form" action="add.php?id=2" method="post">
    <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="text-heading">Заголовок <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['text-heading']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="text-heading" type="text" name="text-heading" value="<?= get_post_val('text-heading'); ?>" placeholder="Введите заголовок">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['text-heading'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['post-text']) ? "form__input-section--error" : '' ?>">
                    <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="post-text" value="<?= get_post_val('post-text'); ?>" placeholder="Введите текст публикации"></textarea>
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['post-text'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="post-tags">Теги</label>
                <div class="form__input-section <?= isset($errors['post-tags']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="post-tags" type="text" name="post-tags" value="<?= get_post_val('post-tags'); ?>" placeholder="Введите теги">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['post-tags'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="form-name" value="text" />
        </div>
        <?php if (isset($errors)) :  ?>
            <?php if (isset($errors['text']) and !empty($errors['text'])) :  ?>
                <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">

                        <?php foreach ($errors['text'] as $key => $value) : ?>
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
