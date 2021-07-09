<?php
if (!isset($output)) {
    $output = '';
}

$output .= "\t<div class=\"sat-form_item\">" . PHP_EOL;
$output .= "\t\t<label class=\"sat-form_label\" for=\"" . esc_attr($field_name) . "\">" . esc_html($label_value) . "</label>" . PHP_EOL;

if ($info_value) {
    $output .= "\t\t<p class=\"sat-info\">" . esc_html($info_value) . "</p>" . PHP_EOL;
}

$output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($info_name) . "\" value=\"" . esc_attr($info_value) . "\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($label_name) . "\" value=\"" . esc_attr($label_value) . "\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($classes_name) . "\" value=\"" . esc_attr($classes_value) . "\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($cols_name) . "\" value=\"" . esc_attr($cols_value) . "\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"" . esc_attr($rows_name) . "\" value=\"" . esc_attr($rows_value) . "\">" . PHP_EOL;

$quicktags_settings = array(
    'textarea_name' => esc_attr($field_name),
    'media_buttons' => false,
    'textarea_rows' => esc_attr($rows_value),
    'tinymce'       => array(
        // Items for the Visual Tab
        'toolbar1'  => 'formatselect,bold,italic,bullist,numlist,link,unlink,undo,redo,',
    ),
    'quicktags'     => array(
        // Items for the Text Tab
        'buttons'   => 'strong,em,underline,ul,ol,li,link'
    )
);
ob_start();
$editor = wp_editor($field_value, $field_name, $quicktags_settings);
$output .= ob_get_clean();
$output .= "\t</div>" . PHP_EOL;
