<?php
require_once('./config/date_timezone_and_locale.php');

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'views/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {
    }, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return true;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}


/**
 * @param $args
 * @return void
 */
function dd(...$args)
{
    var_dump(...$args);
    die();
}

/**
 * @param $str
 * @return string
 */
function esc($str)
{
    return strip_tags($str);
}

/**
 * @param $dt
 * @return object
 */
function get_dates_diff($dt_end, $dt_begin = 'now')
{
    return date_diff(date_create($dt_end), date_create($dt_begin));
}

/**
 * @param $array
 * @param $callback
 * @return boolean
 */
function array_every($array, $callback)
{

    return  !in_array(false,  array_map($callback, $array));
}

/**
 * Преобразует экземпляр даты в урезанную версию ассоциативного массива,
 * ключам которого соответствует коллекция слова с требуемым склонением.
 * Склонение определяется числовым значением по ключу,
 * которое передается вспомогательной функции.
 * @param $dt
 * @return string
 */
function get_human_readable_date($dt = 'now')
{
    $dt_dict = [
        'i' => ['one' => 'минута', 'two' => 'минуты', 'many' => 'минут'],
        'h' => ['one' => 'час', 'two' => 'часа', 'many' => 'часов'],
        'd' => [
            'day' => ['one' => 'день', 'two' => 'дня', 'many' => 'дней'],
            'week' => ['one' => 'неделя', 'two' => 'недели', 'many' => 'недель'],
        ],
        'm' => ['one' => 'месяц', 'two' => 'месяца', 'many' => 'месяцев'],
    ];

    $get_interval_name = function ($value, $threshold = 7) {
        if ($value < $threshold) {
            return 'day';
        } else if ($value >= $threshold) {
            return 'week';
        }
    };

    $dt_diff = get_dates_diff($dt);
    $dt_mapped = array_filter(array_slice(get_object_vars($dt_diff), 0, 6));
    $key = current(array_keys($dt_mapped));
    $value = current(array_values($dt_mapped));

    if ($key) {
        if ($key === 'd') {
            $interval_name = $get_interval_name($value);
            ['one' => $one, 'two' => $two, 'many' => $many] = $dt_dict[$key][$interval_name];
        } else {
            ['one' => $one, 'two' => $two, 'many' => $many] = $dt_dict[$key];
        }

        $noun_plural_form = get_noun_plural_form($value, $one, $two, $many);

        return "{$value} {$noun_plural_form}";
    }

    return '';
}

/**
 * @param $string
 * @return String[]
 */
function split_string_into_words($string)
{
    return preg_split('/\s+/', html_entity_decode($string));
}

/**
 * @param $text
 * @param $threshold
 * @return string
 */
function truncate($text, $threshold = 300)
{
    $words = split_string_into_words($text);

    $result_str = implode(" ", array_reduce($words, function ($acc, $word) use ($threshold) {
        if (strlen(implode(" ", $acc)) < $threshold) {
            $acc[] = $word;
        }

        return $acc;
    }, []));

    return strlen($result_str) < $threshold ? [$text, false] : [$result_str, true];
}

/**
 * @param $haystack
 * @param $needle
 * @return boolean
 */
function ends_with($haystack, $needle)
{
    $length = strlen($needle);
    return $length > 0 ? substr($haystack, -$length) === $needle : true;
}

/**
 * @param $name
 * @return string
 */
function get_post_val($name)
{
    return $_POST[$name] ?? "";
}

/**
 * @param $hashtag
 * @param $regex
 * @return boolean
 */
function is_tag_syntax_valid($hashtag)
{
    $regex = '/^#[А-Яа-яA-Za-z0_]+$/';
    $result = preg_match($regex, $hashtag, $matches, PREG_UNMATCHED_AS_NULL);

    return boolval($result);
}

/**
 * @param $string
 * @return string|null
 */
function is_url_valid($value)
{
    return filter_var($value, FILTER_VALIDATE_URL);
}

/**
 * @param $string
 * @return string|null
 */
function validate_length($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }

    return null;
}

/**
 * @param $string
 * @return String[]
 */
function get_filtered_tags($string)
{
    $filtered = array_filter(split_string_into_words($string), 'is_tag_syntax_valid');

    return $filtered;
}

/**
 * @param $string
 * @return string|null
 */
function validate_tags($string)
{
    $filtered = get_filtered_tags($string);

    if (empty($filtered)) {
        return 'Тег должен начинаться с&nbsp;символа решетки (#), может содержать только латинские и&nbsp;кириллические символы, а&nbsp;также символ нижнего подчеркивания (_)';
    }

    return null;
}

/**
 * @param $string
 * @return string|null
 */
function validate_link($value)
{
    if (!is_url_valid($value)) {
        return 'Введите корректный URL';
    } else if (!file_get_contents($value)) {
        return 'Не удалось загрузить файл';
    }

    return null;
}

/**
 * @param $string
 * @return string|null
 */
function validate_video($value)
{
    if (!is_url_valid($value)) {
        return 'Введите корректный URL';
    } else if (check_youtube_url($value)) {
        return null;
    }
}

/**
 * @param $string
 * @return string|null
 */
function validate_upload($value)
{
    if (!empty($value['name'])) {
        $tmp_name = $value['tmp_name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if (!($file_type === "image/gif" or $file_type === "image/png" or $file_type === "image/jpeg")) {
            return 'Загрузите картинку в&nbsp;одном из&nbsp;следующих форматов: GIF, PNG, JPEG';
        }

        return null;
    }

    return 'Вы не&nbsp;загрузили файл';
}

/**
 * @param $data
 * @param $form_field_link_name
 * @param $form_field_upload_name
 * @return string
 */
function get_picture_field_name_to_remove($data, $form_field_link_name, $form_field_upload_name)
{
    $upload = $data[$form_field_upload_name]['name'];
    $link = $data[$form_field_link_name];

    if ((empty($link) and empty($upload)) or (!empty($link) and empty($upload))) {
        return $form_field_upload_name;
    } else if (empty($link) and !empty($upload)) {
        return $form_field_link_name;
    }
}
