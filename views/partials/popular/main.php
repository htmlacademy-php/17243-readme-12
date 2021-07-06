<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <?= include_template('partials/popular/filters.php', [
            'content_types' => $content_types,
            'content_type_id' => $params['content_type_id'] ?? null
        ]) ?>
        <?= include_template('partials/popular/grid.php', ['posts' => $posts]) ?>
        <?= include_template('partials/popular/pagination.php', [
            'cur_page' => $params['cur_page'] ?? null,
            'pages_count' => $params['pages_count'] ?? null
        ]) ?>
    </div>
</section>
