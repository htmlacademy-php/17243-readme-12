<?php
require_once('./const.php');
require_once('./services/validations.php');
require_once('./models/users.php');

function validate_user(array $input_array, array $validators, array $labels): array
{
    $errors = [];
    $form_name = $input_array['form-name'] ?? '';
    $form_fields_validators = $validators[$form_name] ?? '';
    $form_validations = get_validation_rules($form_fields_validators) ?? [];

    foreach ($form_validations as $field => $rules) {
        foreach ($rules as $rule) {
            $label = $labels[$form_name][$field] ?? null;
            [$name, $parameters] = get_validation_name_and_parameters($rule);
            $method_name = get_validation_method_name($name);
            $method_parameters = array_merge([$input_array, $field, $label], $parameters);

            if (!assert(function_exists($method_name), "Метод $method_name не найден")) {
                echo "Функция $method_name не найдена";
                die();
            }

            $validation_result = call_user_func_array($method_name, $method_parameters);

            if ($validation_result !== null) {
                $errors[$field] = $validation_result;
            }
        }
    }

    return [empty($errors) ? array_filter($input_array, function ($value) use ($form_name) {
        return $value !== $form_name;
    }) : [], $errors];
};

function validate_user_credentials(mysqli $con, array $input_array): array
{
    $errors = [];
    $user = get_user_by_field($con, 'login', $input_array['login']);

    if ($user) {
        if (!password_verify($input_array['password'], $user['password'])) {
            $errors['password'] = 'Пароли не совпадают';
        }
    } else {
        $errors['login'] = 'Неверный логин';
    }

    return [$user, $errors];
}
