<?php

namespace App\Helpers;

class FormHelper
{
    public static function renderDynamicFieldHTML($templateJson, $values = [])
    {
        $fields = is_array($templateJson) ? $templateJson : json_decode($templateJson, true);
        $html = '';
        $htmlHidden = '';

        foreach ($fields as $field) {
            $label = $field['label'] ?? '';
            $name = $field['name'] ?? '';
            $type = $field['type'] ?? 'text';
            $subtype = $field['subtype'] ?? 'text';
            $class = $field['className'] ?? 'form-control';
            $placeholder = $field['placeholder'] ?? '';
            $required = (isset($field['required']) && $field['required'] === 'true') ? 'required' : '';
            $value = $values[$name] ?? '';
            $options = $field['values'] ?? [];
            $other = $field['other'] ?? false;
            $multiple = ($field['multiple'] ?? 'false') === 'true';

            $min = $field['min'] ?? '';
            $max = $field['max'] ?? '';
            $step = $field['step'] ?? '1';

            if (!empty($value) && in_array($subtype, ['date', 'time', 'datetime-local'])) {
                $timestamp = strtotime($value);
                if ($timestamp) {
                    switch ($subtype) {
                        case 'date': $value = date('Y-m-d', $timestamp); break;
                        case 'time': $value = date('H:i', $timestamp); break;
                        case 'datetime-local': $value = date('Y-m-d\TH:i', $timestamp); break;
                    }
                }
            }

            if ($type === 'hidden') {
                $htmlHidden .= "<input type='hidden' name='dynamic[$name]' value='" . htmlspecialchars($value, ENT_QUOTES) . "'>";
                continue;
            }

            // Heading/section/paragraph render
            if (in_array($type, ['header', 'paragraph', 'section', 'newsection'])) {
                if (empty($name)) $name = $type . '-' . uniqid();
                switch ($type) {
                    case 'header':
                        $tag = in_array($subtype, ['h1','h2','h3','h4','h5','h6']) ? $subtype : 'h4';
                        $html .= "<div class='form-group'><$tag>" . htmlspecialchars($label) . "</$tag></div>";
                        break;
                    case 'paragraph':
                        $tag = in_array($subtype, ['address','p','blockquote']) ? $subtype : 'p';
                        $html .= "<div class='form-group'><$tag>" . nl2br(htmlspecialchars($label)) . "</$tag></div>";
                        break;
                    case 'section':
                    case 'newsection':
                        $html .= "<hr><h5>" . htmlspecialchars($label) . "</h5>";
                        break;
                }
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
                    $selectedValues = $multiple ? (array)($value ?? []) : [$value];
                    $html .= "<select name='$inputNameAttr' class='$class' $multipleAttr $required>";

                    $optionValues = array_column($options, 'value');
                    foreach ($options as $opt) {
                        $optValue = $opt['value'] ?? '';
                        $optLabel = $opt['label'] ?? $optValue;
                        $selected = in_array($optValue, $selectedValues) ? 'selected' : '';
                        $html .= "<option value='" . htmlspecialchars($optValue) . "' $selected>" . htmlspecialchars($optLabel) . "</option>";
                    }

                    if ($other) {
                        $otherVals = array_diff($selectedValues, $optionValues);
                        $isOtherSelected = in_array('__other__', $selectedValues) || !empty($otherVals);
                        $otherVal = $otherVals[0] ?? $values["{$name}_other"] ?? '';
                        $html .= "<option value='__other__' " . ($isOtherSelected ? 'selected' : '') . ">Other</option>";
                        $html .= "<input type='text' name='dynamic[{$name}_other]' class='$class mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal) . "'>";
                    }

                    $html .= "</select>";
                    break;

                case 'radio-group':
                    $html .= "<label>$label</label><br>";
                    $matched = false;
                    $optionValues = array_column($options, 'value');
                    foreach ($options as $opt) {
                        $optValue = $opt['value'] ?? '';
                        $optLabel = $opt['label'] ?? $optValue;
                        $checked = ($optValue == $value) ? 'checked' : '';
                        if ($checked) $matched = true;
                        $html .= "<label><input type='radio' name='$inputName' value='" . htmlspecialchars($optValue) . "' $checked $required> " . htmlspecialchars($optLabel) . "</label><br>";
                    }

                    if ($other) {
                        $isOtherSelected = $value && !in_array($value, $optionValues);
                        $checked = $isOtherSelected ? 'checked' : '';
                        $otherVal = $isOtherSelected ? htmlspecialchars($value) : htmlspecialchars($values["{$name}_other"] ?? '');
                        $html .= "<label><input type='radio' name='$inputName' value='__other__' $checked $required> Other</label>";
                        $html .= "<input type='text' name='dynamic[{$name}_other]' class='$class mt-1' placeholder='Please specify' value='$otherVal'>";
                    }
                    break;

                case 'checkbox-group':
                    $html .= "<label>$label</label><br>";
                    $valueArr = is_array($value) ? $value : (json_decode($value, true) ?? []);
                    $optionValues = array_column($options, 'value');
                    foreach ($options as $opt) {
                        $optValue = $opt['value'] ?? '';
                        $optLabel = $opt['label'] ?? $optValue;
                        $checked = in_array($optValue, $valueArr) ? 'checked' : '';
                        $html .= "<label><input type='checkbox' name='{$inputName}[]' value='" . htmlspecialchars($optValue) . "' $checked> " . htmlspecialchars($optLabel) . "</label><br>";
                    }

                    if ($other) {
                        $otherVals = array_diff($valueArr, $optionValues);
                        $checked = !empty($otherVals) ? 'checked' : '';
                        $otherVal = implode(', ', $otherVals);
                        $html .= "<label><input type='checkbox' name='{$inputName}[]' value='__other__' $checked> Other</label>";
                        $html .= "<input type='text' name='dynamic[{$name}_other][]' class='$class mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal) . "'>";
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
