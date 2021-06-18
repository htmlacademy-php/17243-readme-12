<form class="adding-post__form form" action="add.php?id=2" method="post">
    <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="title">Заголовок <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['title']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="title" type="text" name="title" value="<?= get_post_val('title'); ?>" placeholder="Введите заголовок">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['title'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                <label class="adding-post__label form__label" for="body">Текст поста <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['body']) ? "form__input-section--error" : '' ?>">
                    <textarea class="adding-post__textarea form__textarea form__input" id="body" name="body" value="<?= get_post_val('body'); ?>" placeholder="Введите текст публикации"></textarea>
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['body'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="tags">Теги</label>
                <div class="form__input-section <?= isset($errors['tags']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="tags" type="text" name="tags" value="<?= get_post_val('tags'); ?>" placeholder="Введите теги">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['tags'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="form-name" value="text" />
        </div>
        <?php if (isset($errors) and !empty($errors)) :  ?>
            <div class="form__invalid-block">
                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                <ul class="form__invalid-list">

                    <?php foreach ($errors as $key => $value) : ?>
                        <li class="form__invalid-item"><?= $value ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <div class="adding-post__buttons">
        <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
        <a class="adding-post__close" href="index.php">Закрыть</a>
    </div>
</form>
