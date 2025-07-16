@php
    $daySlug = Str::slug($day);
    $collapseId = 'collapse' . ucfirst($daySlug);
    $headingId = 'heading' . ucfirst($daySlug);
    $isFirst = $loop->first;
@endphp

<div class="card mb-1 border">
    <div class="d-flex justify-content-between align-items-center border-bottom px-3 py-2" id="{{ $headingId }}">
        <div class="d-flex flex-grow-1 align-items-center justify-content-between">
            <span class="font-weight-bold">{{ $day }}</span>

            <div class="ml-auto d-flex align-items-center">
                @if($day === 'Monday')
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input apply-to-all-days"
                               id="applyAllDaysCheckbox" name= "apply_all_days" data-day="{{ $daySlug }}">
                        <label class="custom-control-label ml-1 mb-0 medium" for="applyAllDaysCheckbox">
                            Apply to all days
                        </label>
                    </div>
                @endif

                <div class="chevron-toggle ml-3"
                     style="cursor:pointer;"
                     data-toggle="collapse"
                     data-target="#{{ $collapseId }}"
                     data-parent="#workingHoursAccordion"
                     aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                     aria-controls="{{ $collapseId }}">
                    <i class="feather {{ $isFirst ? 'icon-chevron-up' : 'icon-chevron-down' }}"></i>
                </div>
            </div>
        </div>
    </div>

    <div id="{{ $collapseId }}"
         class="collapse {{ $isFirst ? 'show' : '' }}"
         aria-labelledby="{{ $headingId }}"
         data-parent="#workingHoursAccordion">
        <div class="card-body pt-2 pb-2 px-3">
            <div class="d-flex">
                {{-- Start Time --}}
                <select class="form-control form-control-sm w-auto start-time select-user"
                        name="working_days[{{ $daySlug }}][start]">
                    @for($h = 0; $h < 24; $h++)
                        @foreach(['00', '30'] as $m)
                            @php $time = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . $m; @endphp
                            <option value="{{ $time }}"
                                {{ old("working_days.$daySlug.start") == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                        @endforeach
                    @endfor
                </select>

                {{-- End Time --}}
                <select class="form-control form-control-sm w-auto end-time ml-2 select-user"
                        name="working_days[{{ $daySlug }}][end]">
                    @for($h = 0; $h < 24; $h++)
                        @foreach(['00', '30'] as $m)
                            @php $time = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . $m; @endphp
                            <option value="{{ $time }}"
                                {{ old("working_days.$daySlug.end") == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                        @endforeach
                    @endfor
                </select>
            </div>

            {{-- Service Selection --}}
            <div class="d-flex align-items-center mt-3 w-100">
                <select class="form-control select-user service-select"
                        name="working_days[{{ $daySlug }}][service_1][]"
                        multiple>
                    {{-- Will be populated dynamically by JS --}}
                </select>
            </div>
        </div>
    </div>
</div>


