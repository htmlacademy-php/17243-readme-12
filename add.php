<?php
require_once('./helpers.php');
require_once('./config/init.php');
require_once('./models/content_types.php');

$content_types = get_content_types($con) ?? [];
$active_category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$head = include_template('partials/head.php', ['title' => 'readme: добавление публикации']);
$symbols = include_template('partials/symbols.php');
$page_header = include_template('partials/header.php', ['is_auth' => $is_auth, 'username' => 'Вася Попкин']);
$page_footer = include_template('partials/footer.php');
$modal = include_template('partials/adding_post/modals/add.php');
$page_content = include_template('partials/adding_post/main.php', [
    'content_types' => $content_types,
    'active_category_id' => isset($active_category_id) ? $active_category_id : 1,
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_name = $_POST['form-name'];

    $errors = [
        'quote' => [],
        'text' => [],
        'link' => [],
        'video' => [],
        'photo' => [],
    ];

    $form_fields = [
        'quote' => [
            ['name' => 'quote-heading', 'label' => 'Заголовок', 'is_required' => true],
            ['name' => 'quote-text', 'label' => 'Текст цитаты', 'is_required' => true],
            ['name' => 'quote-author', 'label' => 'Автор', 'is_required' => true],
            ['name' => 'quote-tags', 'label' => 'Теги', 'is_required' => false],
        ],
        'text' => [
            ['name' => 'text-heading', 'label' => 'Заголовок', 'is_required' => true],
            ['name' => 'post-text', 'label' => 'Текст поста', 'is_required' => true],
            ['name' => 'post-tags', 'label' => 'Теги', 'is_required' => false],
        ],
        'link' => [
            ['name' => 'link-heading', 'label' => 'Заголовок', 'is_required' => true],
            ['name' => 'post-link', 'label' => 'Ссылка', 'is_required' => true],
            ['name' => 'link-tags', 'label' => 'Теги', 'is_required' => false],
        ],
        'video' => [
            ['name' => 'video-heading', 'label' => 'Заголовок', 'is_required' => true],
            ['name' => 'video-url', 'label' => 'Ссылка youtube', 'is_required' => true],
            ['name' => 'video-tags', 'label' => 'Теги', 'is_required' => false],
        ],
        'photo' => [
            ['name' => 'photo-heading', 'label' => 'Заголовок', 'is_required' => true],
            ['name' => 'photo-url', 'label' => 'Ссылка из интернета', 'is_required' => true],
            ['name' => 'photo-tags', 'label' => 'Теги', 'is_required' => false],
            ['name' => 'userpic-file-photo', 'label' => 'Фото', 'is_required' => true],
        ],
    ];

    $rules = [
        'quote' => [
            'quote-heading' => function ($value) {
                return validate_length($value, 10, 200);
            },
            'quote-text' => function ($value) {
                return validate_length($value, 10, 3000);
            },
            'quote-author' => function ($value) {
                return validate_length($value, 3, 20);
            },
            'quote-tags' => function ($value) {
                return validate_tags($value);
            }
        ],
        'text' => [
            'text-heading' => function ($value) {
                return validate_length($value, 10, 200);
            },
            'post-text' => function ($value) {
                return validate_length($value, 10, 3000);
            },
            'post-tags' => function ($value) {
                return validate_tags($value);
            },
        ],
        'link' => [
            'link-heading' => function ($value) {
                return validate_length($value, 10, 200);
            },
            'post-link' => function ($value) {
                return validate_link($value);
            },
            'link-tags' => function ($value) {
                return validate_tags($value);
            },
        ],
        'video' => [
            'video-heading' => function ($value) {
                return validate_length($value, 10, 200);
            },
            'video-url' => function ($value) {
                return validate_video($value);
            },
            'video-tags' => function ($value) {
                return validate_tags($value);
            },
        ],
        'photo' => [
            'photo-heading' => function ($value) {
                return validate_length($value, 10, 200);
            },
            'photo-url' => function ($value) {
                return  validate_link($value);
            },
            'photo-tags' => function ($value) {
                return validate_tags($value);
            },
            'userpic-file-photo' => function ($value) {
                return validate_upload($value);
            },
        ],
    ];

    $form_data = [];

    if ($form_name === 'quote') {
        $form_data = filter_input_array(INPUT_POST, [
            'quote-heading' => FILTER_DEFAULT,
            'quote-text' => FILTER_DEFAULT,
            'quote-author' => FILTER_DEFAULT,
            'quote-tags' => FILTER_DEFAULT,
        ], true);
    } else if ($form_name === 'text') {
        $form_data = filter_input_array(INPUT_POST, [
            'text-heading' => FILTER_DEFAULT,
            'post-text' => FILTER_DEFAULT,
            'post-tags' => FILTER_DEFAULT,
        ], true);
    } else if ($form_name === 'link') {
        $form_data = filter_input_array(INPUT_POST, [
            'link-heading' => FILTER_DEFAULT,
            'post-link' => FILTER_DEFAULT,
            'link-tags' => FILTER_DEFAULT,
        ], true);
    } else if ($form_name === 'video') {
        $form_data = filter_input_array(INPUT_POST, [
            'video-heading' => FILTER_DEFAULT,
            'video-url' => FILTER_DEFAULT,
            'video-tags' => FILTER_DEFAULT,
        ], true);
    } else if ($form_name === 'photo') {
        $form_data = array_merge(
            ['userpic-file-photo' => $_FILES['userpic-file-photo']],
            filter_input_array(INPUT_POST, [
                'photo-heading' => FILTER_DEFAULT,
                'photo-url' => FILTER_DEFAULT,
                'photo-tags' => FILTER_DEFAULT,
            ], true)
        );

        $picture_field_name_to_remove = get_picture_field_name_to_remove($form_data, 'photo-url', 'userpic-file-photo');
        $form_data = array_filter($form_data, function ($key) use ($picture_field_name_to_remove) {
            return $key !== $picture_field_name_to_remove;
        }, ARRAY_FILTER_USE_KEY);
    }

    foreach ($form_data as $key => $value) {
        [$form_field] = array_values(array_filter($form_fields[$form_name], function ($row) use ($key) {
            return $row['name'] === $key;
        }));


        if (isset($form_field['is_required'])) {
            if ($form_field['is_required'] and empty($value)) {
                $errors[$form_name][$key] = $form_field['label'] . '. ' . 'Поле надо заполнить';
            } else if (($form_field['is_required'] and !empty($value)) or (!$form_field['is_required'] and !empty($value))) {
                if (isset($rules[$form_name][$key])) {
                    $rule = $rules[$form_name][$key];

                    if ($rule($value)) {
                        $errors[$form_name][$key] = $form_field['label'] . '. ' . $rule($value);
                    }
                }
            }
        }
    }

    $errors = array_filter($errors);

    if (count($errors[$form_name])) {
        $page_content = include_template('partials/adding_post/main.php', [
            'content_types' => $content_types,
            'errors' => $errors,
            'active_category_id' => $active_category_id,
        ]);
    }
}

$layout_content = include_template('partials/adding_post/layout.php', [
    'head' => $head,
    'symbols' => $symbols,
    'page_header' => $page_header,
    'page_content' => $page_content,
    'page_footer' => $page_footer,
    'modal' => $modal,
]);

print($layout_content);
