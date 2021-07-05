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
    'registration' => [
        'login' => 'required|login|min:5|max:25',
        'email' => 'required|email',
        'password' => 'required|password',
        'password-repeat' => 'required|password_matching:password',
        'userpic-file' => 'upload'
    ],
    'authorization' => [
        'login' => 'required',
        'password' => 'required'
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
    'registration' => [
        'login' => 'Логин',
        'email' => 'Email',
        'password' => 'Пароль',
        'password-repeat' => 'Повтор пароля',
        'userpic-file' => 'Фото'
    ]
];

$SEARCH_TYPES = [
    'random_words' => function (?array $list): string {
        if (empty($list)) {
            return '';
        }

        return '
            WHERE
                MATCH(title, body) AGAINST(?)
            ';
    },
    'hashtag' => function (?array $list): string {
        return '
            WHERE
                p.id IN (
                    SELECT
                        post_id
                    FROM
                        posts_has_hashtags
                    WHERE
                        hashtag_id = (
                            SELECT
                                id
                            FROM
                                hashtags
                            WHERE
                                name = ?
                        )
                )
            ';
    },
    'subscriptions' => function (?array $list): string {
        $subquery = '
            WHERE
                p.user_id IN (
                    SELECT
                        user_id
                    FROM
                        subscriptions
                    WHERE
                        subscriber_id = ?
                )
            ';

        [, $content_type_id] = $list + array_fill(1, 1, null);

        if (isset($content_type_id)) {

            if ($content_type_id != 0) {
                $subquery = $subquery . 'AND' . ' ' . 'c1.id = ?';
            }
        }

        return $subquery;
    }
];
