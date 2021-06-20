<?php
require_once('./helpers.php');

function get_validation_rules(array $rules): array
{
    $result = [];
    foreach ($rules as $fieldName => $rule) {
        $result[$fieldName] = explode('|', $rule);
    }

    return $result;
}

function get_validation_method_name(string $name): string
{
    $studlyWords = str_replace('-', '_', $name);
    return "validate_{$studlyWords}";
}

function get_validation_name_and_parameters(string $rule): array
{
    $nameParams = explode(':', $rule);
    $parameters = [];
    $name = $nameParams[0];
    if (isset($nameParams[1])) {
        $parameters = explode(',', $nameParams[1]);
    }

    return [$name, $parameters];
}

function validate_required(array $input_array, string $parameter_name, string $form_field_label): ?string
{
    return !array_key_exists($parameter_name, $input_array) ? "$form_field_label: Поле является обязательным для заполнения" : null;
}

function validate_string(array $input_array, string $parameter_name, string $form_field_label): ?string
{
    if (!array_key_exists($parameter_name, $input_array)) {
        return null;
    }

    return !empty(intval($input_array[$parameter_name])) ? "$form_field_label: Поле должно быть строкой" : null;
}

function validate_min(array $input_array, string $parameter_name, string $form_field_label, int $count): ?string
{
    if (!array_key_exists($parameter_name, $input_array)) {
        return null;
    }

    return strlen($input_array[$parameter_name]) < $count ? "$form_field_label: Поле должно быть длиной не менее $count символов" : null;
}

function validate_max(array $input_array, string $parameter_name, string $form_field_label, int $count): ?string
{
    if (!array_key_exists($parameter_name, $input_array)) {
        return null;
    }

    return strlen($input_array[$parameter_name]) > $count ? "$form_field_label: Поле должно быть длиной менее $count символов" : null;
}

function is_tag_syntax_valid(string $hashtag): bool
{
    $regex = '/^#[А-Яа-яA-Za-z_]+$/u';
    $result = preg_match($regex, $hashtag, $matches, PREG_UNMATCHED_AS_NULL);

    return boolval($result);
}

function get_filtered_tags(string $string): array
{
    $filtered = array_filter(split_string_into_words($string), 'is_tag_syntax_valid');

    return $filtered;
}

function validate_tags(array $input_array, string $parameter_name, string $form_field_label): ?string
{
    if (!array_key_exists($parameter_name, $input_array)) {
        return null;
    }

    $filtered = get_filtered_tags($input_array[$parameter_name]);

    if (empty($filtered)) {
        return "$form_field_label: Тег должен начинаться с&nbsp;символа решетки (#), может содержать только латинские и&nbsp;кириллические символы, а&nbsp;также символ нижнего подчеркивания (_)";
    }

    return null;
}

function is_url_valid(string $value): ?string
{
    return filter_var($value, FILTER_VALIDATE_URL);
}

function check_youtube_url(string $url, string $label): ?string
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {
    }, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "$label: Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "$label: Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return null;
}

function validate_link(array $input_array, string $parameter_name, string $form_field_label): ?string
{
    if (!array_key_exists($parameter_name, $input_array)) {
        return null;
    }

    if (!is_url_valid($input_array[$parameter_name])) {
        return "$form_field_label: Введите корректный URL";
    } else if (!file_get_contents($input_array[$parameter_name])) {
        return 'Не удалось загрузить файл';
    }

    return null;
}

function validate_video(array $input_array, string $parameter_name, string $form_field_label): ?string
{
    if (!array_key_exists($parameter_name, $input_array)) {
        return null;
    }

    if (!is_url_valid($input_array[$parameter_name])) {
        return "$form_field_label: Введите корректный URL";
    }

    return check_youtube_url($input_array[$parameter_name], $form_field_label);
}

function validate_upload(array $input_array, string $parameter_name, string $form_field_label): ?string
{
    if (!array_key_exists($parameter_name, $input_array)) {
        return null;
    }

    $value = $input_array[$parameter_name];

    if (!empty($value['name'])) {
        $tmp_name = $value['tmp_name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if (!($file_type === "image/gif" || $file_type === "image/png" || $file_type === "image/jpeg")) {
            return "$form_field_label: Загрузите картинку в&nbsp;одном из&nbsp;следующих форматов: GIF, PNG, JPEG";
        }

        return null;
    }

    return "$form_field_label: Вы не&nbsp;загрузили файл";
}

function validate_required_when_matching(array $input_array, string $parameter_name, string $form_field_label, string $form_field_name, array $errors = [])
{
    $form_field_link_name = $parameter_name === 'body' ? $parameter_name : $form_field_name;
    $form_field_upload_name = $parameter_name === 'file-photo' ? $parameter_name : $form_field_name;

    $upload = isset($input_array[$form_field_upload_name]) ?? null;
    $link = isset($input_array[$form_field_link_name]) ?? null;

    if (!empty($upload) && !isset($errors[$form_field_upload_name])) {
        $error_message = validate_required($input_array, $form_field_upload_name, $form_field_label);
    } else if (
        (empty($link) && empty($upload) && !isset($errors[$form_field_link_name]))
        or
        (!empty($link) && empty($upload) && !isset($errors[$form_field_link_name]))
    ) {
        $error_message = validate_required($input_array, $form_field_link_name, $form_field_label);
    }

    return $error_message ?? null;
}
