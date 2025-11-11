@php
$daySlug = Str::slug($day);
$collapseId = 'collapse' . ucfirst($daySlug);
$headingId = 'heading' . ucfirst($daySlug);
$isFirst = $loop->first;

$selectedServices = $workingHours[$daySlug]['services'] ?? [];
$selectedStart = $workingHours[$daySlug]['start'] ?? '00:00';
$selectedEnd = $workingHours[$daySlug]['end'] ?? '00:00';
$applyAllDays = $workingHours['apply_all_days'] ?? 0;
@endphp

<div class="card mb-1 border">
    <!-- FULL HEADER is clickable -->
    <div class="d-flex justify-content-between align-items-center border-bottom px-3 py-2 cursor-pointer"
         id="{{ $headingId }}"
         data-toggle="collapse"
         data-target="#{{ $collapseId }}"
         data-parent="#workingHoursAccordion"
         aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
         aria-controls="{{ $collapseId }}">

        <div class="d-flex flex-grow-1 align-items-center justify-content-between">
            <span class="font-weight-bold">{{ $day }}</span>

            <div class="ml-auto d-flex align-items-center">
                <i class="feather {{ $isFirst ? 'icon-chevron-up' : 'icon-chevron-down' }}"></i>
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
                <div class="col-md-6 p-0">
                    <select class="form-control form-control-sm w-auto start-time select-user"
                        name="working_days[{{ $daySlug }}][start]">
                        @for ($h = 0; $h < 24; $h++)
                            @foreach (['00', '30' ] as $m)
                            @php $time = str_pad($h, 2, '0' , STR_PAD_LEFT) . ':' . $m; @endphp
                            <option value="{{ $time }}" {{ old("working_days.$daySlug.start", $selectedStart) == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                            @endforeach
                        @endfor
                    </select>
                </div>

                {{-- End Time --}}
                <div class="col-md-6 pr-0">
                    <select class="form-control form-control-sm w-auto end-time ml-2 select-user"
                        name="working_days[{{ $daySlug }}][end]">
                        @for ($h = 0; $h < 24; $h++)
                            @foreach (['00', '30' ] as $m)
                            @php $time = str_pad($h, 2, '0' , STR_PAD_LEFT) . ':' . $m; @endphp
                            <option value="{{ $time }}" {{ old("working_days.$daySlug.end", $selectedEnd) == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                            @endforeach
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
