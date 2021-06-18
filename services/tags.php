<?php
require_once('./models/hashtags.php');
require_once('./models/posts_has_hashtags.php');

$validate_tags = function (array $input_array, string $k = 'tags') use ($FORM_FIELDS_VALIDATORS, $FORM_FIELDS_LABELS): array {
    $errors = [];
    $form_name = $input_array['form-name'] ?? '';
    $form_fields_validators = $FORM_FIELDS_VALIDATORS[$form_name] ?? '';

    $form_validations = get_validation_rules(array_filter($form_fields_validators, function ($key) use ($k) {
        return $key === $k;
    }, ARRAY_FILTER_USE_KEY)) ?? [];
    $tags = isset($input_array[$k]) ? get_filtered_tags($input_array[$k]) : [];

    foreach ($form_validations as $field => $rules) {
        foreach ($rules as $rule) {
            $label = $FORM_FIELDS_LABELS[$form_name][$field] ?? '';
            [$name, $parameters] = get_validation_name_and_parameters($rule);
            $method_name = get_validation_method_name($name);
            $method_parameters = array_merge([$input_array, $field, $label], $parameters, $errors);

            if (!assert(function_exists($method_name), "Метод $method_name не найден")) {
                echo "Функция $method_name не найдена";
                die();
            }

            $validation_result = call_user_func_array($method_name, $method_parameters);

            if ($validation_result !== null) {
                $errors[$form_name][$field] = $validation_result;
            }
        }
    }

    return [empty($errors) ? $tags : [], isset($errors[$form_name]) ? $errors[$form_name] : $errors];
};

function create_tags(object $con, array $tags_data, int $post_id): void
{
    if (!empty($tags_data)) {
        foreach ($tags_data as $tag) {
            $tag_res = add_hashtag($con, $tag);

            if ($tag_res) {
                $tag_id = mysqli_insert_id($con);
                link_tag_with_post($con, [$post_id, $tag_id]);
            }
        }
    }
}
