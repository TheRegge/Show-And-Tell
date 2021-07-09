<?php
if (!isset($output)) {
    $output = '';
}

$output .= "\t<div class=\"sat-form_item\">" . PHP_EOL;
$output .= "\t\t<label for=\"{$field_name}\">{$label_value}</label>" . PHP_EOL;

if ($info_value) {
    $output .= "\t\t<p class=\"sat-info\">{$info_value}</p>" . PHP_EOL;
}

$output .= "\t\t<input type=\"hidden\" name=\"{$label_name}\" value=\"{$label_value}\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"{$classes_name}\" value=\"{$classes_value}\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"{$field_name}\" value=\"-1\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"{$info_name}\" value=\"{$info_value}\">" . PHP_EOL;
$output .= "\t\t<input type=\"hidden\" name=\"{$options_name}\" value=\"{$options_value}\">" . PHP_EOL;

$output .= "\t\t<select class=\"{$classes_value}\" name=\"{$field_name}\" id=\"{$field_name}\">" . PHP_EOL;
foreach ($options_arr as $option) {
    $option = trim($option);
    $output .= "\t\t\t<option value=\"{$option}\">{$option}</option>" . PHP_EOL;
}
$output .= "\t\t</select>" . PHP_EOL;
