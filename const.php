<?php
$FORM_FIELDS_VALIDATORS = [
    'quote' => [
        'title' => 'required|string|min:5|max:200',
        'body' => 'required|string|min:5|max:3000',
        'tags' => 'string|tags',
        'author_name' => 'required|string|min:5|max:50',
    ],
    'text' => [
        'title' => 'required|string|min:5|max:200',
        'body' => 'required|string|min:5|max:3000',
        'tags' => 'string|tags',
    ],
    'link' => [
        'title' => 'required|string|min:5|max:200',
        'body' => 'required|link',
        'tags' => 'string|tags',
    ],
    'video' => [
        'title' => 'required|string|min:5|max:200',
        'body' => 'required|video',
        'tags' => 'string|tags',
    ],
    'photo' => [
        'title' => 'required|string|min:5|max:200',
        'body' => 'required_when_matching:file-photo|link',
        'tags' => 'string|tags',
        'file-photo' => 'required_when_matching:body|upload',
    ],
];

$FORM_FIELDS_LABELS = [
    'quote' => [
        'title' => 'Заголовок',
        'body' => 'Текст цитаты',
        'tags' => 'Теги',
        'author_name' => 'Автор',
    ],
    'text' => [
        'title' => 'Заголовок',
        'body' => 'Текст поста',
        'tags' => 'Теги',
    ],
    'link' => [
        'title' => 'Заголовок',
        'body' => 'Ссылка',
        'tags' => 'Теги',
    ],
    'video' => [
        'title' => 'Заголовок',
        'body' => 'Ссылка youtube',
        'tags' => 'Теги',
    ],
    'photo' => [
        'title' => 'Заголовок',
        'body' => 'Ссылка из интернета',
        'tags' => 'Теги',
        'file-photo' => 'Фото',
    ],
];
