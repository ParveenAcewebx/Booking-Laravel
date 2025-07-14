    <div class="tab-pane fade" id="working-days" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="accordion" id="workingHoursAccordion">
                    @foreach($weekDays as $index => $day)
                        @php
                            $daySlug = Str::slug($day);
                            $collapseId = 'collapse' . ucfirst($daySlug);
                            $headingId = 'heading' . ucfirst($daySlug);
                            $isFirst = $index === 0;
                        @endphp
                        @include('admin.staff.partials.fields.add.working-day', compact('day', 'daySlug', 'collapseId', 'headingId', 'isFirst'))
                    @endforeach
                </div>
            </div>
        </div>
    </div>