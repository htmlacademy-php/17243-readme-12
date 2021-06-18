<form class="adding-post__form form" action="add.php?id=1" method="post">
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
            <div class="adding-post__input-wrapper form__textarea-wrapper">
                <label class="adding-post__label form__label" for="body">Текст цитаты <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['body']) ? "form__input-section--error" : '' ?>">
                    <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="body" name="body" value="<?= get_post_val('cite-text'); ?>" placeholder="Текст цитаты"></textarea>
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['body'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__textarea-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="author_name">Автор <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['author_name']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="author_name" type="text" name="author_name" value="<?= get_post_val('author_name'); ?>">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['author_name'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="tags">Теги</label>
                <div class="form__input-section <?= isset($errors['tags']) ? "form__input-section--error" : '' ?>">
                    <input class="adding-post__input form__input" id="tags" type="text" name="tags" value="<?= get_post_val('cite-tags'); ?>" placeholder="Введите теги">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $errors['tags'] ?? '' ?></p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="form-name" value="quote" />
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
