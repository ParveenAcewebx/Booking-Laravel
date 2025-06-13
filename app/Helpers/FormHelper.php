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

            if ($type === 'hidden') {
                $htmlHidden .= "<input type='hidden' name='dynamic[$name]' value='" . htmlspecialchars($value, ENT_QUOTES) . "'>";
                continue;
            }

            if (in_array($type, ['header', 'paragraph', 'section', 'newsection'])) {
                if (empty($name)) {
                    $name = $type . '-' . uniqid(); 
                }

                switch ($type) {
                    case 'header':
                        $html .= "<div class='form-group'>";
                        $html .= "<h4>" . htmlspecialchars($label, ENT_QUOTES) . "</h4>";
                        $html .= "<input type='text' class='form-control' name='dynamic[$name]' value='" . htmlspecialchars($label, ENT_QUOTES) . "'>";
                        $html .= "</div>";
                        break;

                    case 'paragraph':
                        $html .= "<div class='form-group'>";
                        $html .= "<p>" . nl2br(htmlspecialchars($label, ENT_QUOTES)) . "</p>";
                        $html .= "<input type='text' class='form-control' name='dynamic[$name]' value='" . htmlspecialchars($label, ENT_QUOTES) . "'>";
                        $html .= "</div>";
                        break;

                    case 'section':
                    case 'newsection':
                        $html .= "<hr><h5>" . htmlspecialchars($label, ENT_QUOTES) . "</h5>";
                        $html .= "<input type='hidden' name='dynamic[$name]' value='" . htmlspecialchars($label, ENT_QUOTES) . "'>";
                        break;
                }
                continue;
            }


            $html .= "<div class='form-group'>";

            switch ($type) {
                case 'file':
                    $html .= "<label>" . htmlspecialchars($label, ENT_QUOTES) . "</label>";
                    $html .= "<input type='file' name='dynamic[$name]' class='$class' $required>";
                    break;

                case 'number':
                case 'date':
                case 'text':
                case 'email':
                case 'password':
                case 'tel':
                case 'url':
                    $html .= "<label>" . htmlspecialchars($label, ENT_QUOTES) . "</label>";
                    $html .= "<input type='$subtype' name='dynamic[$name]' class='$class' placeholder='" . htmlspecialchars($placeholder, ENT_QUOTES) . "' value='" . htmlspecialchars($value, ENT_QUOTES) . "' $required>";
                    break;

                case 'textarea':
                    $html .= "<label>" . htmlspecialchars($label, ENT_QUOTES) . "</label>";
                    $html .= "<textarea name='dynamic[$name]' class='$class' placeholder='" . htmlspecialchars($placeholder, ENT_QUOTES) . "' $required>" . htmlspecialchars($value, ENT_QUOTES) . "</textarea>";
                    break;

                case 'select':
                    $html .= "<label>" . htmlspecialchars($label, ENT_QUOTES) . "</label>";
                    $html .= "<select name='dynamic[$name]' class='$class' $required>";
                    foreach ($options as $opt) {
                        $optValue = $opt['value'] ?? '';
                        $optLabel = $opt['label'] ?? $optValue;
                        $selected = $optValue == $value ? 'selected' : '';
                        $html .= "<option value='" . htmlspecialchars($optValue, ENT_QUOTES) . "' $selected>" . htmlspecialchars($optLabel, ENT_QUOTES) . "</option>";
                    }
                    $html .= "</select>";
                    break;

                case 'radio-group':
                    $html .= "<label>" . htmlspecialchars($label, ENT_QUOTES) . "</label><br>";
                    foreach ($options as $opt) {
                        $optValue = $opt['value'] ?? '';
                        $optLabel = $opt['label'] ?? $optValue;
                        $checked = $optValue == $value ? 'checked' : '';
                        $html .= "
                            <label>
                                <input type='radio' name='dynamic[$name]' value='" . htmlspecialchars($optValue, ENT_QUOTES) . "' $checked $required>
                                " . htmlspecialchars($optLabel, ENT_QUOTES) . "
                            </label><br>";
                    }
                    break;

                case 'checkbox-group':
                    $html .= "<label>" . htmlspecialchars($label, ENT_QUOTES) . "</label><br>";
                    $valueArr = is_array($value) ? $value : json_decode($value, true) ?? [];
                    foreach ($options as $opt) {
                        $optValue = $opt['value'] ?? '';
                        $optLabel = $opt['label'] ?? $optValue;
                        $checked = in_array($optValue, $valueArr) ? 'checked' : '';
                        $html .= "
                            <label>
                                <input type='checkbox' name='dynamic[$name][]' value='" . htmlspecialchars($optValue, ENT_QUOTES) . "' $checked $required>
                                " . htmlspecialchars($optLabel, ENT_QUOTES) . "
                            </label><br>";
                    }
                    break;

                default:
                    $html .= "<label>" . htmlspecialchars($label, ENT_QUOTES) . "</label>";
                    $html .= "<input type='$type' name='dynamic[$name]' class='$class' value='" . htmlspecialchars($value, ENT_QUOTES) . "' placeholder='" . htmlspecialchars($placeholder, ENT_QUOTES) . "' $required>";
                    break;
            }

            $html .= "</div>";
        }

        return $htmlHidden . $html;
    }
}
