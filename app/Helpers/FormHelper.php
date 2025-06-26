<?php

namespace App\Helpers;

use App\Helpers\Shortcode;

class FormHelper
{
    public static function renderDynamicFieldHTML($templateJson, $values = [])
    {
        $fields = is_array($templateJson) ? $templateJson : json_decode($templateJson, true);
        $html = '';
        $htmlHidden = '';

        if (!is_array($fields)) {
            return '<div class="alert alert-danger">Invalid form template JSON.</div>';
        }

        foreach ($fields as $field) {
            $type = $field['type'] ?? 'text';
            $subtype = $field['subtype'] ?? 'text';
            $label = $field['label'] ?? '';
            $name = $field['name'] ?? uniqid('field_');
            $class = $field['className'] ?? 'form-control';
            $placeholder = $field['placeholder'] ?? '';
            $required = (!empty($field['required']) && $field['required'] === 'true') ? 'required' : '';
            $value = $values[$name] ?? '';
            $options = $field['values'] ?? [];
            $other = $field['other'] ?? false;
            $multiple = !empty($field['multiple']) && ($field['multiple'] === true || $field['multiple'] === 'true');
            $min = $field['min'] ?? '';
            $max = $field['max'] ?? '';
            $step = $field['step'] ?? '1';

            $inputName = "dynamic[$name]";
            $inputNameAttr = $multiple ? $inputName . '[]' : $inputName;

            switch ($type) {
                case 'hidden':
                    $htmlHidden .= "<input type='hidden' name='$inputName' value='" . htmlspecialchars($value, ENT_QUOTES) . "'>";
                    break;

                case 'header':
                case 'paragraph':
                case 'section':
                case 'newsection':
                    $tag = $type === 'header'
                        ? (in_array($subtype, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? $subtype : 'h4')
                        : ($type === 'paragraph' ? (in_array($subtype, ['address', 'p', 'blockquote']) ? $subtype : 'p') : '');
                    $content = nl2br(htmlspecialchars($label));
                    $html .= ($type === 'section' || $type === 'newsection')
                        ? "<hr><h5>$content</h5>"
                        : "<div class='form-group'><$tag>$content</$tag></div>";
                    break;

                case 'file':
                    $html .= "<div class='form-group'><label>$label</label>
                            <input type='file' name='$inputNameAttr' class='$class' " . ($multiple ? 'multiple' : '') . " $required>";
                    if (!empty($value)) {
                        $files = is_array($value) ? $value : [$value];
                        foreach ($files as $file) {
                            $fileUrl = asset('storage/' . $file);
                            $html .= "<small class='form-text text-muted'>Uploaded: <a href='$fileUrl' target='_blank'>View</a></small>";
                        }
                    }
                    $html .= "</div>";
                    break;

                case 'number':
                    $html .= "<div class='form-group'><label>$label</label>";
                    if ($subtype === 'range') {
                        $html .= "<input type='range' name='$inputName' class='$class' value='" . htmlspecialchars($value) . "' min='$min' max='$max' step='$step' $required>
                                <div class='d-flex justify-content-between text-muted'><small>Min: $min</small><small>Max: $max</small></div>";
                    } else {
                        $html .= "<input type='number' name='$inputName' class='$class' value='" . htmlspecialchars($value) . "' min='$min' max='$max' step='$step' placeholder='" . htmlspecialchars($placeholder) . "' $required>";
                    }
                    $html .= "</div>";
                    break;

                case 'select':
                    $html .= "<div class='form-group'><label>$label</label>";
                    $multipleAttr = $multiple ? 'multiple' : '';
                    $valueArr = $multiple ? (is_array($value) ? $value : (json_decode($value, true) ?: [$value])) : [(string) $value];
                    $optionValues = array_column($options, 'value');
                    $html .= "<select name='$inputNameAttr' class='$class' $multipleAttr $required>";
                    foreach ($options as $opt) {
                        $val = $opt['value'] ?? '';
                        $lbl = $opt['label'] ?? $val;
                        $selected = in_array((string) $val, $valueArr) ? 'selected' : '';
                        $html .= "<option value='" . htmlspecialchars($val) . "' $selected>" . htmlspecialchars($lbl) . "</option>";
                    }
                    if ($other) {
                        $otherVals = array_diff($valueArr, $optionValues);
                        $isOtherSelected = in_array('__other__', $valueArr) || !empty($otherVals);
                        $otherVal = $otherVals[0] ?? ($values["{$name}_other"] ?? '');
                        $selected = $isOtherSelected ? 'selected' : '';
                        $html .= "<option value='__other__' $selected>Other</option>";
                    }
                    $html .= "</select>";
                    if ($other) {
                        $html .= "<input type='text' name='dynamic[{$name}_other]' class='$class mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal) . "'>";
                    }
                    $html .= "</div>";
                    break;

                case 'checkbox-group':
                    $html .= "<div class='form-group'><label>$label</label><br>";
                    $valueArr = is_array($value) ? $value : (json_decode($value, true) ?: [$value]);
                    $optionValues = array_column($options, 'value');
                    foreach ($options as $i => $opt) {
                        $val = $opt['value'] ?? '';
                        $lbl = $opt['label'] ?? $val;
                        $isChecked = in_array((string) $val, $valueArr) ? 'checked' : '';
                        $requiredAttr = ($i === 0 && $required) ? 'required' : '';
                        $html .= "<label><input type='checkbox' name='{$inputName}[]' value='" . htmlspecialchars($val) . "' $isChecked $requiredAttr> " . htmlspecialchars($lbl) . "</label><br>";
                    }
                    if ($other) {
                        $isOtherChecked = in_array('__other__', $valueArr) ? 'checked' : '';
                        $otherVal = $values["{$name}_other"][0] ?? '';
                        $html .= "<label><input type='checkbox' name='{$inputName}[]' value='__other__' $isOtherChecked> Other</label>";
                        $html .= "<input type='text' name='dynamic[{$name}_other][]' class='$class mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal) . "'>";
                    }
                    $html .= "</div>";
                    break;

                case 'radio-group':
                    $html .= "<div class='form-group'><label>$label</label><br>";
                    $optionValues = array_column($options, 'value');
                    $idBase = uniqid($name . '_');
                    foreach ($options as $i => $opt) {
                        $val = $opt['value'] ?? '';
                        $lbl = $opt['label'] ?? $val;
                        $id = $idBase . "_$i";
                        $checked = $val == $value ? 'checked' : '';
                        $html .= "<div class='form-check'><input type='radio' id='$id' name='$inputName' value='" . htmlspecialchars($val) . "' class='form-check-input' $checked $required><label for='$id' class='form-check-label'>" . htmlspecialchars($lbl) . "</label></div>";
                    }
                    if ($other) {
                        $isOther = $value === '__other__' || (!in_array($value, $optionValues) && !empty($value));
                        $otherVal = $isOther ? ($values["{$name}_other"] ?? $value) : '';
                        $otherId = $idBase . '_other';
                        $html .= "<div class='form-check'><input type='radio' id='$otherId' name='$inputName' value='__other__' class='form-check-input' " . ($isOther ? 'checked' : '') . " $required><label for='$otherId' class='form-check-label'>Other</label></div>";
                        $html .= "<input type='text' name='dynamic[{$name}_other]' class='$class mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal) . "'>";
                    }
                    $html .= "</div>";
                    break;

                case 'textarea':
                    $html .= "<div class='form-group'><label>$label</label><textarea name='$inputName' class='$class' placeholder='" . htmlspecialchars($placeholder) . "' $required>" . htmlspecialchars($value) . "</textarea></div>";
                    break;

                case 'shortcodeblock':

                    $shortcodeValue = $field['value'] ?? '';
                    $html .= "<div class='form-group'>" . Shortcode::render(trim($shortcodeValue, '[]'), $values) . "</div>";
                    break;

                default:
                    if (in_array($subtype, ['date', 'time', 'datetime-local']) && !empty($value)) {
                        $timestamp = strtotime($value);
                        if ($timestamp) {
                            $value = match ($subtype) {
                                'date' => date('Y-m-d', $timestamp),
                                'time' => date('H:i', $timestamp),
                                'datetime-local' => date('Y-m-d\TH:i', $timestamp),
                                default => $value
                            };
                        }
                    }
                    $html .= "<div class='form-group'><label>$label</label><input type='$subtype' name='$inputName' class='$class' value='" . htmlspecialchars($value) . "' placeholder='" . htmlspecialchars($placeholder) . "' $required></div>";
                    break;
            }
        }

        return $htmlHidden . $html;
    }
}
