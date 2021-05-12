<?php
require_once('./helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Андрей';
$posts = [
    [
        'type' => 'post-quote',
        'title' => 'Цитата',
        'body' => 'Мы&nbsp;в&nbsp;жизни любим только раз, а&nbsp;после ищем лишь похожих',
        'username' => 'Лариса',
        'userpic' => 'userpic-larisa-small.jpg',
    ],
    [
        'type' => 'post-text',
        'title' => 'Игра престолов',
        'body' => 'Не&nbsp;могу дождаться начала финального сезона своего любимого сериала!',
        'username' => 'Владик',
        'userpic' => 'userpic.jpg',
    ],
    [
        'type' => 'post-photo',
        'title' => 'Наконец, обработал фотки!',
        'body' => 'rock-medium.jpg',
        'username' => 'Виктор',
        'userpic' => 'userpic-mark.jpg',
    ],
    [
        'type' => 'post-link',
        'title' => 'Лучшие курсы',
        'body' => 'www.htmlacademy.ru',
        'username' => 'Владик',
        'userpic' => 'userpic.jpg',
    ],
    [
        'type' => 'post-video',
        'title' => 'Моя мечта',
        'body' => 'https://youtu.be/x3sIRL-weh4',
        'username' => 'Лариса',
        'userpic' => 'userpic-larisa-small.jpg',
    ],
];

function truncate($text, $threshold = 300)
{
    $words = preg_split("/\s/", html_entity_decode($text));

    $result_str = implode(" ", array_reduce($words, function ($acc, $word) use ($threshold) {
        if (strlen(implode(" ", $acc)) < $threshold) {
            $acc[] = $word;
        }

        return $acc;
    }, []));

    return strlen($result_str) < $threshold ? [$text, false] : [$result_str, true];
}

$page_content = include_template('main.php', ['posts' => $posts]);
$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'readme: популярное',
    'username' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout_content);
