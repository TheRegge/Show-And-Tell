<?php
if (!isset($output)) {
    $output = '';
}

$output .= "\t<div class=\"sat-form_item\">" . PHP_EOL;
$output .= "\t\t<div class=\"sat-form_label\">" . esc_attr($label_value) . "</div>" . PHP_EOL;

if ($info_value) {
    $output .= "\t\t<p class=\"sat-info\">" . esc_html($info_value) . "</p>" . PHP_EOL;
}

if ($field_value) {
    $output .= "\t\t<a href=\"". wp_get_attachment_url($field_value) . "\" target=\"_blank\" class=\"sat-attachment_link\">" . wp_get_attachment_image($field_value, 'medium') . "</a>" . PHP_EOL;
}

if ($status !== SAT_SUBMISSION_STATUS_PUBLISHED) {
    $output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($label_name) . "\" value=\"" . esc_attr($label_value) . "\">" . PHP_EOL;
    $output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($info_name) . "\" value=\"" . esc_attr($info_value) . "\">" . PHP_EOL;
    $output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($classes_name) . "\" value=\"" . esc_attr($classes_value) . "\">" . PHP_EOL;
    $output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($field_name) . "\" value=\"" . esc_attr($field_value) . "\">" . PHP_EOL;
    $output .= "\t\t<input type=\"file\" name=\"" . esc_attr($field_name) . "\" id=\"" . esc_attr($field_name) . "\" class=\"" .esc_attr($classes_value) . " inputfile\">" . PHP_EOL;
    $output .= "\t\t<label for=\"" . esc_attr($field_name) . "\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"17\" viewBox=\"0 0 20 17\"><path d=\"M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z\"/></svg> <span>" . __('Change image', 'show-and-tell') . "</span></label>" . PHP_EOL;
}
$output .="\t</div>" . PHP_EOL;
