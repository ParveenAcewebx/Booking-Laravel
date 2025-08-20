<?php

namespace App\Helpers;

use App\Helpers\Shortcode;
use App\Models\Service;

class FormHelper
{
    public static function renderDynamicFieldHTML($templateJson, $values = [], $theme = 'bootstrap')
    {
        $fields = is_array($templateJson) ? $templateJson : json_decode($templateJson, true);


        // Begin the form output
        $html = '';
        $htmlHidden = '';
        $chunks = [];
        $currentChunk = [];
        if (!is_array($fields)) {
            return '<div class="alert alert-danger">Invalid form template JSON.</div>';
        }

        // Theme-based classes
        $classes = [
            'bootstrap' => [
                'group' => 'form-group mb-3',
                'label' => 'form-label',
                'input' => 'form-control',
                'select' => 'form-control',
                'textarea' => 'form-control',
                'checkboxWrapper' => 'form-check',
                'checkbox' => 'form-check-input',
                'checkboxLabel' => 'form-check-label',
                'radioWrapper' => 'form-check',
                'radio' => 'form-check-input',
                'radioLabel' => 'form-check-label',
                'file' => 'form-control',
                'helpText' => 'form-text text-muted',
                'button' => 'btn btn-primary',
                'hidden' => 'd-none',
            ],
            'tailwind' => [
                'group' => 'mb-6',
                'label' => 'block mb-2 text-sm font-medium text-gray-700',
                'input' => 'mt-1 block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm',
                'select' => 'mt-1 block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm',
                'textarea' => 'mt-1 block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm',
                'checkboxWrapper' => 'flex items-center mb-2 space-x-2',
                'checkbox' => 'h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500',
                'checkboxLabel' => 'text-sm font-medium text-gray-700',
                'radioWrapper' => 'flex items-center mb-2 space-x-2',
                'radio' => 'h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500',
                'radioLabel' => 'text-sm font-medium text-gray-700',
                'file' => 'block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:py-2 file:px-4 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200',
                'helpText' => 'text-gray-500 text-xs mt-1',
                'button' => 'px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500',
                'hidden' => 'hidden',
            ]
        ];

        $c = $classes[$theme] ?? $classes['bootstrap'];

        if (!empty($c) && isset($c['button']) && $c['button'] != 'btn btn-primary') {
            $countNewSections = count(array_filter($fields, function ($item) {
                return isset($item['type']) && $item['type'] === 'newsection';
            }));
            foreach ($fields as $item) {
                $currentChunk[] = $item;
                if (isset($item['type']) && $item['type'] === 'newsection') {
                    $chunks[] = $currentChunk;
                    $currentChunk = [];
                }
            }
            if (!empty($currentChunk)) {
                $chunks[] = $currentChunk;
            }
        } else {
            $chunks[] = $fields;
        }
        $stepCount = 1;
        foreach ($chunks as $chunk) {
            $html .= "<div class='step' id='step_$stepCount' style='display: " . ($stepCount === 1 ? 'block' : 'none') . "'>";
            foreach ($chunk as $field) {
                $type = $field['type'] ?? 'text';
                $subtype = $field['subtype'] ?? 'text';
                $label = $field['label'] ?? '';
                $name = $field['name'] ?? uniqid('field_');
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
                        $headingClasses = [
                            'h1' => 'text-4xl font-bold mb-4',
                            'h2' => 'text-3xl font-semibold mb-3',
                            'h3' => 'text-2xl font-medium mb-2',
                            'h4' => 'text-xl font-normal mb-2',
                            'h5' => 'text-lg font-light mb-2',
                            'h6' => 'text-base font-light mb-2',
                        ];
                        $headingClass = $headingClasses[$tag] ?? 'text-xl font-medium mb-2';
                        $content = nl2br(htmlspecialchars($label));
                        $html .= ($type === 'section' || $type === 'newsection')
                            ? ""
                            : "<div class='{$c['group']}'><$tag class='$headingClass'>$content</$tag></div>";

                        break;

                    case 'file':
                        $html .= "<div class='{$c['group']}'>
                                <label class='{$c['label']}'>$label " . ($required ? ' <span class="text-red-500">*</span>' : '') . "</label>
                                <input type='file' name='$inputNameAttr' class='{$c['file']}' " . ($multiple ? 'multiple' : '') . " $required>";
                        if (!empty($value)) {
                            $files = is_array($value) ? $value : [$value];
                            foreach ($files as $file) {
                                $fileUrl = asset('storage/' . $file);
                                $html .= "<small class='{$c['helpText']}'>Uploaded: <a href='$fileUrl' target='_blank'>View</a></small>";
                            }
                        }
                        $html .= "</div>";
                        break;

                    case 'number':
                        $html .= "<div class='{$c['group']}'><label class='{$c['label']}'>$label " . ($required ? ' <span class="text-red-500">*</span>' : '') . "</label>";
                        if ($subtype === 'range') {
                            $html .= "<input type='range' name='$inputName' class='{$c['input']}' value='" . htmlspecialchars($value) . "' min='$min' max='$max' step='$step' $required>
                                  <div class='flex justify-between text-gray-500 text-sm'><small>Min: $min</small><small>Max: $max</small></div>";
                        } else {
                            $html .= "<input type='number' name='$inputName' class='{$c['input']}' value='" . htmlspecialchars($value) . "' min='$min' max='$max' step='$step' placeholder='" . htmlspecialchars($placeholder) . "' $required>";
                        }
                        $html .= "</div>";
                        break;

                    case 'select':
                        $valueArr = $multiple ? (is_array($value) ? $value : (json_decode($value, true) ?: [$value])) : [(string) $value];
                        $optionValues = array_column($options, 'value');
                        $multipleAttr = $multiple ? 'multiple' : '';
                        $html .= "<div class='{$c['group']}'><label class='{$c['label']}'>$label " . ($required ? ' <span class="text-red-500">*</span>' : '') . "</label>
                              <select name='$inputNameAttr' class='{$c['select']}'$required >";
                        foreach ($options as $opt) {
                            $val = $opt['value'] ?? '';
                            $lbl = $opt['label'] ?? $val;
                            $selected = in_array((string)$val, $valueArr) ? 'selected' : '';
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
                            $html .= "<input type='text' name='dynamic[{$name}_other]' class='{$c['input']} mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal) . "'>";
                        }
                        $html .= "</div>";
                        break;

                    case 'checkbox-group':
                        $valueArr = is_array($value) ? $value : (json_decode($value, true) ?: [$value]);
                        $optionValues = array_column($options, 'value');
                        $html .= "<div class='{$c['group']}'><label class='{$c['label']}'>$label " . ($required ? ' <span class="text-red-500">*</span>' : '') . "</label>";
                        foreach ($options as $i => $opt) {
                            $val = $opt['value'] ?? '';
                            $lbl = $opt['label'] ?? $val;
                            $isChecked = in_array((string)$val, $valueArr) ? 'checked' : '';
                            $requiredAttr = ($i === 0 && $required) ? 'required' : '';

                            $html .= "<div class='{$c['checkboxWrapper']}'>
                                    <input type='checkbox' name='{$inputName}[]' value='" . htmlspecialchars($val) . "' class='{$c['checkbox']}' $isChecked $requiredAttr>
                                    <label class='{$c['checkboxLabel']}'>" . htmlspecialchars($lbl) . ($required ? ' <span class="text-red-500">*</span>' : '') . "</label>
                                  
                                    </div>
                                        <p class='checkbox-error-message text-red-500 text-xs mt-1'></p>";
                        }
                        if ($other) {
                            $isOtherChecked = in_array('__other__', $valueArr) ? 'checked' : '';
                            $otherVal = $values["{$name}_other"][0] ?? '';
                            $html .= "<div class='{$c['checkboxWrapper']}'>
                                    <input type='checkbox' name='{$inputName}[]' value='__other__' class='{$c['checkbox']}' $isOtherChecked>
                                    <label class='{$c['checkboxLabel']}'>Other</label>
                                  </div>
                                  <input type='text' name='dynamic[{$name}_other][]' class='{$c['input']} mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal) . "'>";
                        }
                        $html .= "</div>";
                        break;

                    case 'radio-group':
                        $optionValues = array_column($options, 'value');
                        $idBase = uniqid($name . '_');
                        $html .= "<div class='{$c['group']}'><label class='{$c['label']}'>$label " . ($required ? ' <span class="text-red-500">*</span>' : '') . "</label>";
                        foreach ($options as $i => $opt) {
                            $val = $opt['value'] ?? '';
                            $lbl = $opt['label'] ?? $val;
                            $id = $idBase . "_$i";
                            $checked = $val == $value ? 'checked' : '';
                            $html .= "<div class='{$c['radioWrapper']}'>
                                    <input type='radio' id='$id' name='$inputName' value='" . htmlspecialchars($val) . "' class='{$c['radio']}' $checked $required>
                                    <label for='$id' class='{$c['radioLabel']}'>" . htmlspecialchars($lbl) . "</label>
                                    
                                  </div>
                                  
                                  ";
                        }
                        if ($other) {
                            $isOther = $value === '__other__' || (!in_array($value, $optionValues) && !empty($value));
                            $otherVal = $isOther ? ($values["{$name}_other"] ?? $value) : '';
                            $otherId = $idBase . '_other';
                            $html .= "<div class='{$c['radioWrapper']}'>
                                    <input type='radio' id='$otherId' name='$inputName' value='__other__' class='{$c['radio']}' " . ($isOther ? 'checked' : '') . " $required>
                                    <label for='$otherId' class='{$c['radioLabel']}'>Other</label>
                                  </div>
                                  <input type='text' name='dynamic[{$name}_other]' class='{$c['input']} mt-1' placeholder='Please specify' value='" . htmlspecialchars($otherVal) . "'>";
                        }
                        $html .= "<p class='radio-error-message text-red-500 text-xs mt-1'></p>
                        </div>";
                        break;

                    case 'textarea':
                        $html .= "<div class='{$c['group']}'><label class='{$c['label']}'>$label " . ($required ? ' <span class="text-red-500">*</span>' : '') . "</label>
                              <textarea name='$inputName' class='{$c['textarea']}' placeholder='" . htmlspecialchars($placeholder) . "' $required>" . htmlspecialchars($value) . "</textarea></div>";
                        break;

                    case 'shortcodeblock':
                        $shortcodeValue = $field['value'] ?? '';
                        $class = $c;
                        $html .= "<div class='{$c['group']}'>" . Shortcode::render(trim($shortcodeValue, '[]'), $values, $class) . "</div>";
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
                        $html .= "<div class='{$c['group']}'><label class='{$c['label']}'>$label " . ($required ? ' <span class="text-red-500">*</span>' : '') . "</label>
                              <input type='$subtype' name='$inputName' class='{$c['input']}' value='" . htmlspecialchars($value) . "' placeholder='" . htmlspecialchars($placeholder) . "' $required>
                              </div>";
                        break;
                }
            }
            $html .= "</div>";
            $stepCount++;
        }
        if (!empty($c) && isset($c['button']) && $c['button'] != 'btn btn-primary') {
            $html .= "<div class='form-navigation flex justify-between " . ($countNewSections > 0 ? '' : 'hidden') . "'>
                <div class='perv_step'>
                    <button type='button' class='step-previous previous {$c['button']}' style='display: none;'>Previous</button>
                </div>
                <div class='nex_step'>
                    <button type='button' class='step-next next {$c['button']}'>Next</button>
                    <button type='submit' class='submit {$c['button']} hidden'>Submit</button>
                </div>
          </div>";
        }

        if (!empty($c) && isset($c['button']) && $c['button'] != 'btn btn-primary') {
            $html .= ($countNewSections == '0' ? "<div class='form-navigation'><button type='submit' class='submit {$c['button']}'>Submit</button>" : '');
        }
        return $htmlHidden . $html;
    }
}
