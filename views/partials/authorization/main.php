<main>
    <h1 class="visually-hidden">Главная страница сайта по созданию микроблога readme</h1>
    <div class="page__main-wrapper page__main-wrapper--intro container">
        <section class="intro">
            <h2 class="visually-hidden">Наши преимущества</h2>
            <b class="intro__slogan">Блог, каким<br> он должен быть</b>
            <ul class="intro__advantages-list">
                <li class="intro__advantage intro__advantage--ease">
                    <p class="intro__advantage-text">
                        Есть все необходимое для&nbsp;простоты публикации
                    </p>
                </li>
                <li class="intro__advantage intro__advantage--no-excess">
                    <p class="intro__advantage-text">
                        Нет ничего лишнего, отвлекающего от сути
                    </p>
                </li>
            </ul>
        </section>
        <section class="authorization">
            <h2 class="visually-hidden">Авторизация</h2>
            <form class="authorization__form form" action="index.php" method="post">
                <div class="authorization__input-wrapper form__input-wrapper">
                    <div class="form__input-section form__input-section<?= isset($errors['login']) ? "--error" : '' ?>">
                        <input class="authorization__input authorization__input--login form__input" type="text" name="login" value="<?= get_post_val('login'); ?>" placeholder="Логин">
                        <svg class="form__input-icon" width="19" height="18">
                            <use xlink:href="#icon-input-user"></use>
                        </svg>
                        <label class="visually-hidden">Логин</label>
                    </div>
                    <span class="form__error-label form__error-label--login"><?= isset($errors['login']) ? $errors['login'] : '' ?></span>
                </div>
                <div class="authorization__input-wrapper form__input-wrapper">
                    <div class="form__input-section form__input-section<?= isset($errors['password']) ? "--error" : '' ?>">
                        <input class="authorization__input authorization__input--password form__input" type="password" name="password" value="<?= get_post_val('password'); ?>" placeholder="Пароль">
                        <svg class="form__input-icon" width="16" height="20">
                            <use xlink:href="#icon-input-password"></use>
                        </svg>
                        <label class="visually-hidden">Пароль</label>
                    </div>
                    <span class="form__error-label"><?= isset($errors['password']) ? $errors['password'] : '' ?></span>
                </div>
                <input type="hidden" name="form-name" value="authorization" />
                <a class="authorization__recovery" href="#">Восстановить пароль</a>
                <button class="authorization__submit button button--main" type="submit">Войти</button>
            </form>
        </section>
    </div>
</main>
