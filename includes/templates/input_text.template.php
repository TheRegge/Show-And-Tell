<?php
if (!isset($output)) {
    $output = '';
}

$output .= "\t<div class=\"sat-form_item\">" . PHP_EOL;
$output .= "\t\t<label class=\"sat-form_label\" for=\"" . esc_attr($field_name) . "\">" . esc_html($label_value) . "</label>" . PHP_EOL;

if ($info_value) {
    $output .= "\t\t<p class=\"sat-info\">{$info_value}</p>" . PHP_EOL;
}
$output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($label_name) . "\" value=\"" . esc_attr($label_value) . "\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($classes_name) . "\" value=\"" . esc_attr($classes_value) . "\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($info_name) . "\" value=\"" . esc_attr($info_value) . "\">" . PHP_EOL;
$output .= "\t\t<input type=\"text\" name=\"" . esc_attr($field_name) . "\" class=\"" . esc_attr($classes_value) . "\" value=\"" . esc_attr($field_value) . "\" {$disabled} >" . PHP_EOL;
$output .= "\t</div>" . PHP_EOL;
