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
    function get_form_data_by_form_name($form_name)
    {
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

            $photo_field_name_to_remove = get_photo_field_name_to_remove($form_data, 'photo-url', 'userpic-file-photo');
            $form_data = array_filter($form_data, function ($key) use ($photo_field_name_to_remove) {
                return $key !== $photo_field_name_to_remove;
            }, ARRAY_FILTER_USE_KEY);
        }

        return array_filter($form_data);
    };

    function get_form_field_label($form_name, $form_field_name)
    {
        $labels = [
            'quote' => [
                'quote-heading' => 'Заголовок',
                'quote-text' => 'Текст цитаты',
                'quote-author' => 'Автор',
                'quote-tags' => 'Теги',
            ],
            'text' => [
                'text-heading' => 'Заголовок',
                'post-text' => 'Текст поста',
                'post-tags' => 'Теги',
            ],
            'link' => [
                'link-heading' => 'Заголовок',
                'post-link' => 'Ссылка',
                'link-tags' => 'Теги',
            ],
            'video' => [
                'video-heading' => 'Заголовок',
                'video-url' => 'Ссылка youtube',
                'video-tags' => 'Теги',
            ],
            'photo' => [
                'photo-heading' => 'Заголовок',
                'photo-url' => 'Ссылка из интернета',
                'photo-tags' => 'Теги',
                'userpic-file-photo' => 'Фото',
            ],
        ];

        return $labels[$form_name][$form_field_name];
    };

    $form_name = $_POST['form-name'];

    $errors = [
        'quote' => [],
        'text' => [],
        'link' => [],
        'video' => [],
        'photo' => [],
    ];

    $validations = [
        'quote' => [
            'quote-heading' => 'required|string|min:10|max:200',
            'quote-text' => 'required|string|min:10|max:3000',
            'quote-author' => 'required|string|min:3|max:20',
            'quote-tags' => 'string|tags',
        ],
        'text' => [
            'text-heading' => 'required|string|min:10|max:200',
            'post-text' => 'required|string|min:10|max:3000',
            'post-tags' => 'string|tags',
        ],
        'link' => [
            'link-heading' => 'required|string|min:10|max:200',
            'post-link' => 'required|link',
            'link-tags' => 'string|tags',
        ],
        'video' => [
            'video-heading' => 'required|string|min:10|max:200',
            'video-url' => 'required|video',
            'video-tags' => 'string|tags',
        ],
        'photo' => [
            'photo-heading' => 'required|string|min:10|max:200',
            'photo-url' => 'link',
            'userpic-file-photo' => 'upload',
            'photo-tags' => 'string|tags',
        ],
    ];

    $form_data = get_form_data_by_form_name($form_name);

    $form_validations = get_validation_rules($validations[$form_name]);

    foreach ($form_validations as $field => $rules) {
        foreach ($rules as $rule) {
            [$name, $parameters] = get_validation_name_and_parameters($rule);
            $methodName = get_validation_method_name($name);
            $methodParameters = array_merge([$form_data, $field, get_form_field_label($form_name, $field)], $parameters);

            if (!assert(function_exists($methodName), "Метод $methodName не найден")) {
                echo "Функция $methodName не найдена";
                die();
            }

            $validationResult = call_user_func_array($methodName, $methodParameters);

            if ($validationResult !== null) {
                $errors[$form_name][$field] = $validationResult;
            }
        }
    }

    $form_errors = array_filter($errors[$form_name]);

    if (count($form_errors)) {
        $page_content = include_template('partials/adding_post/main.php', [
            'content_types' => $content_types,
            'errors' => $form_errors,
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
