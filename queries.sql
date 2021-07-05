-- -----------------------------------------------------
-- Data insertions
-- -----------------------------------------------------
/* content_type */
INSERT INTO
  content_types (name, classname)
VALUES
  ('Цитата', 'quote'),
  ('Текст', 'text'),
  ('Картинка', 'photo'),
  ('Ссылка', 'link'),
  ('Видео', 'video');

/* users */
INSERT INTO
  users (avatar_path, email, login, password)
VALUES
  (
    'userpic-petro.jpg',
    'petro@cxmyal.com',
    'petro',
    '$2y$10$/VzZMLP4aG4ePc3QV2bxS.WKY0UxVfrTuatGyZYxB9jGV7cs2TWXS'
  ),
  (
    'userpic-larisa.jpg',
    'larisa@accebay.site',
    'larisa',
    '$2y$10$CSBgFnvp6OaWmFu/nLhwxeujnwnJhk3US0yQRhTRWkkphRQXrYL5C'
  );

/* posts */
INSERT INTO
  posts (title, body, user_id, content_type_id)
VALUES
  (
    'Цитата',
    'Мы&nbsp;в&nbsp;жизни любим только раз, а&nbsp;после ищем лишь похожих',
    1,
    1
  ),
  (
    'Игра престолов',
    'Не&nbsp;могу дождаться начала финального сезона своего любимого сериала!',
    1,
    2
  ),
  (
    'Наконец, обработал фотки!',
    'rock-default.jpg',
    1,
    3
  ),
  (
    'Лучшие курсы',
    'https://htmlacademy.ru',
    2,
    4
  ),
  (
    'Моя мечта',
    'https://youtu.be/x3sIRL-weh4',
    2,
    5
  ),
  (
    'Лучшее место на&nbsp;земле!',
    'https://youtu.be/hYFXDqI-etU',
    2,
    5
  );

/* comments */
INSERT INTO
  comments (`text`, `post_id`, `user_id`)
VALUES
  ('Неплохо!', 1, 1),
  ('Кто автор?', 1, 1),
  (
    'Я тоже, жду с нетерпением!',
    2,
    1
  ),
  (
    'Какой-то новый сериал? Впервые слышу.',
    2,
    1
  ),
  ('Интересный ракурс!', 3, 1),
  ('Завалена перспектива', 3, 1),
  ('Еще одна online-школа…', 4, 2),
  ('Учился у них?', 4, 2),
  ('Эпичный видос.', 5, 2),
  ('Красивые пейзажи', 5, 2);

/* likes */
INSERT INTO
  likes (post_id, user_id)
VALUES
  (1, 2),
  (1, 2);

/* subscriptions */
INSERT INTO
  subscriptions (subscriber_id, user_id)
VALUES
  (1, 2), (2, 1);

/* hashtags */
INSERT INTO
  hashtags (name)
VALUES
  ('мечта', 'приключениелета');

/* posts_has_hashtags */
INSERT INTO
  posts_has_hashtags (post_id, hashtag_id)
VALUES
  (5, 1), (5, 2), (6, 1), (6, 2);

-- -----------------------------------------------------
-- Queries
-- -----------------------------------------------------
/* получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента */
SELECT
  p.dt_add AS post_dt,
  p.title AS post_title,
  c.name AS content_type,
  u.login AS user_name,
  p.views_count
FROM
  posts AS p
  INNER JOIN users AS u ON p.user_id = u.id
  INNER JOIN content_types AS c ON p.content_type_id = c.id
ORDER BY
  views_count DESC;

/* получить список постов для конкретного пользователя */
SELECT
  u.login AS username,
  p.title
FROM
  posts AS p
  INNER JOIN users AS u ON p.user_id = u.id
WHERE
  u.id = 1;

/* получить список комментариев для одного поста, в комментариях должен быть логин пользователя */
SELECT
  p.title AS post_title,
  c.text AS comment,
  u.login AS username
FROM
  posts AS p
  INNER JOIN comments AS c ON c.post_id = p.id
  INNER JOIN users AS u ON p.user_id = u.id
WHERE
  p.id = 2;
