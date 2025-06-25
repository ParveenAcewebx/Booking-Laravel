<?php

namespace App\Helpers;

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
            $label = $field['label'] ?? '';
            $name = $field['name'] ?? '';
            $type = $field['type'] ?? 'text';
            $subtype = $field['subtype'] ?? 'text';
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

            if (!empty($value) && in_array($subtype, ['date', 'time', 'datetime-local'])) {
                $timestamp = strtotime($value);
                if ($timestamp) {
                    switch ($subtype) {
                        case 'date':
                            $value = date('Y-m-d', $timestamp);
                            break;
                        case 'time':
                            $value = date('H:i', $timestamp);
                            break;
                        case 'datetime-local':
                            $value = date('Y-m-d\TH:i', $timestamp);
                            break;
                    }
                }
            }

            if ($type === 'hidden') {
                $htmlHidden .= "<input type='hidden' name='dynamic[$name]' value='" . htmlspecialchars($value, ENT_QUOTES) . "'>";
                continue;
            }

            if (in_array($type, ['header', 'paragraph', 'section', 'newsection'])) {
                if (empty($name)) $name = $type . '-' . uniqid();
                switch ($type) {
                    case 'header':
                        $tag = in_array($subtype, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? $subtype : 'h4';
                        $html .= "<div class='form-group'><$tag>" . htmlspecialchars($label) . "</$tag></div>";
                        break;
                    case 'paragraph':
                        $tag = in_array($subtype, ['address', 'p', 'blockquote']) ? $subtype : 'p';
                        $html .= "<div class='form-group'><$tag>" . nl2br(htmlspecialchars($label)) . "</$tag></div>";
                        break;
                    case 'section':
                    case 'newsection':
                        $html .= "<hr><h5>" . htmlspecialchars($label) . "</h5>";
                        break;
                }
                continue;
            }

            if ($type === 'shortcodeblock') {
                $shortcodeValue = $field['value'] ?? $value ?? '';
                $html .= "<div class='form-group'>";
                $html .= "<label>Shortcode:</label>";
                $html .= "<input type='text' name='dynamic[$name]' value='" . htmlspecialchars($shortcodeValue, ENT_QUOTES) . "' placeholder='[your_shortcode]' class='form-control'>";
                $html .= "</div>";
                continue;
            }

            $html .= "<div class='form-group'>";
            $inputName = "dynamic[$name]";
            $inputNameAttr = $multiple ? $inputName . '[]' : $inputName;

            switch ($type) {
                case 'file':
                    $html .= "<label>$label</label>";
                    $multipleAttr = $multiple ? 'multiple' : '';
                    $html .= "<input type='file' name='$inputNameAttr' class='$class' $multipleAttr $required>";
                    if (!empty($value)) {
                        $files = is_array($value) ? $value : [$value];
                        foreach ($files as $file) {
                            $fileUrl = asset('storage/' . $file);
                            $html .= "<small class='form-text text-muted'>Uploaded: <a href='$fileUrl' target='_blank'>View</a></small>";
                        }
                    }
                    break;

                case 'number':
                    $html .= "<label>$label</label>";
                    if ($subtype === 'range') {
                        $html .= "<input type='range' name='$inputName' class='$class' value='" . htmlspecialchars($value) . "' min='$min' max='$max' step='$step' $required>";
                        $html .= "<div class='d-flex justify-content-between text-muted'><small>Min: $min</small><small>Max: $max</small></div>";
                    } else {
                        $html .= "<input type='number' name='$inputName' class='$class' value='" . htmlspecialchars($value) . "' min='$min' max='$max' step='$step' placeholder='" . htmlspecialchars($placeholder) . "' $required>";
                    }
                    break;

                case 'select':
                    $html .= "<label>$label</label>";
                    $multipleAttr = $multiple ? 'multiple' : '';

                    if ($multiple) {
                        if (!is_array($value)) {
                            $decoded = json_decode($value, true);
                            $value = is_array($decoded) ? $decoded : [$value];
                        }
                    } else {
                        $value = (array) $value;
                    }

                    $selectedValues = $value;
                    $optionValues = array_column($options, 'value');

                    $html .= "<select name='$inputNameAttr' class='$class' $multipleAttr $required>";
                    foreach ($options as $opt) {
                        $optValue = $opt['value'] ?? '';
                        $optLabel = $opt['label'] ?? $optValue;
                        $selected = in_array($optValue, $selectedValues) ? 'selected' : '';
                        $html .= "<option value='" . htmlspecialchars($optValue) . "' $selected>" . htmlspecialchars($optLabel) . "</option>";
                    }

                    if ($other) {
                        $otherVals = array_diff($selectedValues, $optionValues);
                        $isOtherSelected = in_array('__other__', $selectedValues) || !empty($otherVals);
                        $otherVal = $otherVals[0] ?? ($values["{$name}_other"] ?? '');
                        $html .= "<option value='__other__' " . ($isOtherSelected ? 'selected' : '') . ">Other</option>";
                        $html .= "<input type='text' name='dynamic[{$name}_other]' class='$class mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal) . "'>";
                    }

                    $html .= "</select>";
                    break;

                case 'checkbox-group':
                    $html .= "<label>$label</label><br>";
                    if (!is_array($value)) {
                        $decoded = json_decode($value, true);
                        $valueArr = is_array($decoded) ? $decoded : [$value];
                    } else {
                        $valueArr = $value;
                    }

                    $optionValues = array_column($options, 'value');
                    $firstCheckbox = true;

                    foreach ($options as $opt) {
                        $optValue = $opt['value'] ?? '';
                        $optLabel = $opt['label'] ?? $optValue;
                        $checked = in_array($optValue, $valueArr) ? 'checked' : '';
                        $requiredAttr = ($firstCheckbox && $required) ? 'required' : '';
                        $html .= "<label><input type='checkbox' name='{$inputName}[]' value='" . htmlspecialchars($optValue) . "' $checked $requiredAttr> " . htmlspecialchars($optLabel) . "</label><br>";
                        $firstCheckbox = false;
                    }

                    if ($other) {
                        $checked = in_array('__other__', $valueArr) ? 'checked' : '';
                        $otherVal = htmlspecialchars($values["{$name}_other"][0] ?? '');
                        $requiredAttr = ($firstCheckbox && $required) ? 'required' : '';
                        $html .= "<label><input type='checkbox' name='{$inputName}[]' value='__other__' $checked $requiredAttr> Other</label>";
                        $html .= "<input type='text' name='dynamic[{$name}_other][]' class='$class mt-1' placeholder='Please specify' value='$otherVal'>";
                    }
                    break;

                case 'radio-group':
                    $html .= "<label>$label</label><br>";
                    $optionValues = array_column($options, 'value');
                    $radioIdBase = uniqid($name . '_');

                    foreach ($options as $index => $opt) {
                        $optValue = $opt['value'] ?? '';
                        $optLabel = $opt['label'] ?? $optValue;
                        $checked = ($optValue == $value) ? 'checked' : '';
                        $id = $radioIdBase . '_' . $index;

                        $html .= "<div class='form-check'>";
                        $html .= "<input type='radio' id='$id' name='$inputName' value='" . htmlspecialchars($optValue) . "' class='form-check-input' $checked $required>";
                        $html .= "<label for='$id' class='form-check-label'>" . htmlspecialchars($optLabel) . "</label>";
                        $html .= "</div>";
                    }

                    if ($other) {
                        $isOtherSelected = $value === '__other__' || (!in_array($value, $optionValues) && !empty($value));
                        $checked = $isOtherSelected ? 'checked' : '';
                        $otherVal = $isOtherSelected ? ($values["{$name}_other"] ?? $value) : '';
                        $otherId = $radioIdBase . '_other';

                        $html .= "<div class='form-check'>";
                        $html .= "<input type='radio' id='$otherId' name='$inputName' value='__other__' class='form-check-input' $checked $required>";
                        $html .= "<label for='$otherId' class='form-check-label'>Other</label>";
                        $html .= "</div>";

                        $html .= "<input type='text' name='dynamic[{$name}_other]' class='$class mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal, ENT_QUOTES) . "'>";
                    }
                    break;

                case 'textarea':
                    $html .= "<label>$label</label>";
                    $html .= "<textarea name='$inputName' class='$class' placeholder='" . htmlspecialchars($placeholder) . "' $required>" . htmlspecialchars($value) . "</textarea>";
                    break;

                default:
                    $html .= "<label>$label</label>";
                    $html .= "<input type='$subtype' name='$inputName' class='$class' value='" . htmlspecialchars($value) . "' placeholder='" . htmlspecialchars($placeholder) . "' $required>";
                    break;
            }

            $html .= "</div>";
        }

        return $htmlHidden . $html;
    }
}
