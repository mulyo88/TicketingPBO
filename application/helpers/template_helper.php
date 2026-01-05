<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$CI_Template_Sections = [];
$CI_Template_Extend = null;

function start_section($name) {
    global $CI_Template_Sections;
    $CI_Template_Sections[$name] = '';
    ob_start();
}

function end_section($name) {
    global $CI_Template_Sections;
    if (ob_get_length() > 0) {
        $CI_Template_Sections[$name] = ob_get_clean();
    }
}

function yield_section($name) {
    global $CI_Template_Sections;
    if (isset($CI_Template_Sections[$name])) {
        echo $CI_Template_Sections[$name];
    }
}

function extend($template) {
    global $CI_Template_Extend;
    $CI_Template_Extend = $template;
}

function render_template() {
    global $CI_Template_Extend;
    if ($CI_Template_Extend) {
        $CI =& get_instance();
        $CI->load->view($CI_Template_Extend);
    }
}

function include_view($view, $data = []) {
    $CI =& get_instance();
    $CI->load->view($view, $data);
}