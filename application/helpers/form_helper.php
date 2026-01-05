<?php
if (!function_exists('old')) {
    function old($field, $default = '')
    {
        $CI =& get_instance();

        if ($CI->input->post($field) !== null) {
            return $CI->input->post($field);
        }

        $old_input = $CI->session->flashdata('_old_input');
        if (isset($old_input[$field])) {
            return $old_input[$field];
        }

        return $default;
    }
}

if (!function_exists('error')) {
    function error($field)
    {
        $CI =& get_instance();

        if (function_exists('form_error') && form_error($field)) {
            return '<p class="help-block" style="color: red;">' . strip_tags(form_error($field)) . '</p>';
        }

        $errors = $CI->session->flashdata('errors');
        if (isset($errors[$field])) {
            return '<p class="help-block" style="color: red;">' . $errors[$field] . '</p>';
        }

        return '';
    }
}

if (!function_exists('invalid')) {
    function invalid($field)
    {
        $CI =& get_instance();
        $errors = $CI->session->flashdata('errors');

        if ((function_exists('form_error') && form_error($field)) || isset($errors[$field])) {
            return 'style="border: 1px solid red;"';
        }

        return '';
    }
}

if (!function_exists('invalid5')) {
    function invalid5($field)
    {
        $CI =& get_instance();
        $errors = $CI->session->flashdata('errors');

        if ((function_exists('form_error') && form_error($field)) || isset($errors[$field])) {
            return 'is-invalid';
        }

        return '';
    }
}
