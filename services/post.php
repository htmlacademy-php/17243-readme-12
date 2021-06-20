<?php
require_once('./const.php');
require_once('./models/posts.php');
require_once('./services/validations.php');

function validate_post(array $input_array, array $validators, array $labels, string $k = 'tags'): array
{
    $errors = [];
    $form_name = $input_array['form-name'] ?? '';
    $form_fields_validators = $validators[$form_name] ?? '';
    $form_validations = [];



    $form_validations = get_validation_rules(array_filter($form_fields_validators, function ($key) use ($k) {
        return $key !== $k;
    }, ARRAY_FILTER_USE_KEY)) ?? [];
    $post = array_filter($input_array, function ($key) use ($k) {
        return $key !== $k;
    }, ARRAY_FILTER_USE_KEY);

    foreach ($form_validations as $field => $rules) {
        foreach ($rules as $rule) {
            $label = $labels[$form_name][$field] ?? '';
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

    return [empty($errors) ? $post : [], isset($errors[$form_name]) ? $errors[$form_name] : $errors];
};

function create_post(object $con, array $post_data, int $id): ?int
{
    $form_name = $post_data['form-name'];
    $filtered_data = array_filter($post_data, function ($key) {
        return $key !== 'form-name';
    }, ARRAY_FILTER_USE_KEY);

    if ($form_name === 'photo') {
        move_uploaded_file($_FILES['file-photo']['tmp_name'], 'uploads/' . $_FILES['file-photo']['name']);
    }

    $post_res = add_post($con, array_merge($filtered_data, ['content_type_id' => $id]));

    if ($post_res) {
        $post_id = mysqli_insert_id($con);

        return $post_id;
    }

    return null;
}
