<?php
require_once('./config/init.php');
require_once('./helpers.php');
require_once('./services/validations.php');
require_once('./services/post.php');
require_once('./services/hashtags.php');
require_once('./models/content_types.php');
require_once('./models/posts.php');
require_once('./const.php');

$content_types = get_content_types($con) ?? [];
$active_category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$head = include_template('partials/head.php', ['title' => 'readme: добавление публикации']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php', ['username' => 'Вася Попкин']);
$page_footer = include_template('partials/footer.php');
$modal = include_template('partials/adding_post/modals/add.php');

$form_errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $input_data = remove_empty_form_values($_POST, $_FILES);

    [$validated_post_data, $post_errors] = validate_post(
        $input_data,
        $FORM_FIELDS_VALIDATORS,
        $FORM_FIELDS_LABELS
    );
    [$validated_tags_data, $tags_errors] = validate_hashtags(
        $input_data,
        $FORM_FIELDS_VALIDATORS,
        $FORM_FIELDS_LABELS
    );
    $form_errors = array_merge($post_errors, $tags_errors);

    if (empty($form_errors)) {
        $post_id = create_post($con, $validated_post_data, $active_category_id);
        create_hashtags($con, $validated_tags_data, $post_id);
        header("Location: post.php?id=" . $post_id);
        die();
    }
}

$page_content = include_template('partials/adding_post/main.php', [
    'content_types' => $content_types,
    'errors' => $form_errors,
    'active_category_id' => isset($active_category_id) ? $active_category_id : 1,
]);

$layout_content = include_template('partials/adding_post/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer,
    'modal' => $modal,
]);

print($layout_content);
