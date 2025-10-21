<!-- Edit -->
<div class="modal fade" id="edit{{ $course->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b><span class="employee_id">Edit Course</span></b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('course.update', $course) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Name</label>
                        <input type="text" class="form-control"  name="name" value="{{ $course->name }}"
                            required>
                    </div>
                     <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Batch</label>
                        <input type="text" class="form-control" name="batch" value="{{ $course->batch }}"
                            required>
                    </div>
@php
    $selectedTypes = json_decode($course->type, true) ?? [];
    $selectedCourses = json_decode($course->course, true) ?? [];

    $feesRaw = json_decode($course->fees, true);
    $fees = is_array($feesRaw) ? $feesRaw : ['basic' => $course->fees, 'advance' => ''];

    $durationRaw = json_decode($course->duration, true);
    $duration = is_array($durationRaw) ? $durationRaw : ['basic' => $course->duration, 'advance' => ''];
@endphp

<!-- BASIC -->
<label style="cursor:pointer;">
    <input type="checkbox" name="type[]" value="BASIC" onclick="togglePrivacyDetails()"
        {{ in_array('BASIC', $selectedTypes) ? 'checked' : '' }}>
    <u>BASIC</u>
</label>

<div id="privacy-section" style="{{ in_array('BASIC', $selectedTypes) ? '' : 'display:none;' }}">
    <div class="form-group">
        <label><input type="checkbox" name="course_basic[]" value="Skin & Hair"
            {{ in_array('Skin & Hair', $selectedCourses) ? 'checked' : '' }}> Skin & Hair</label>

        <label><input type="checkbox" name="course_basic[]" value="Nail Art Course"
            {{ in_array('Nail Art Course', $selectedCourses) ? 'checked' : '' }}> Nail Art Course</label>

        <label><input type="checkbox" name="course_basic[]" value="Mehandi Course"
            {{ in_array('Mehandi Course', $selectedCourses) ? 'checked' : '' }}> Mehandi Course</label>
    </div>

    <label for="fees_basic">Fees</label>
    <input type="text" class="form-control" id="fees_basic" name="fees_basic"
        value="{{ $fees['basic'] ?? '' }}" placeholder="Enter fees" />

    <label for="duration_basic">Duration</label>
    <input type="text" class="form-control" id="duration_basic" name="duration_basic"
        value="{{ $duration['basic'] ?? '' }}" placeholder="Enter duration" />
</div>

<br>

<!-- ADVANCE -->
<label style="cursor:pointer;">
    <input type="checkbox" name="type[]" value="ADVANCE" onclick="togglePrivacyDetail()"
        {{ in_array('ADVANCE', $selectedTypes) ? 'checked' : '' }}>
    <u>ADVANCE</u>
</label>

<div id="privacy-sections" style="{{ in_array('ADVANCE', $selectedTypes) ? '' : 'display:none;' }}">
    <div class="form-group">
        <label><input type="checkbox" name="course_advance[]" value="Skin & Hair Advance"
            {{ in_array('Skin & Hair Advance', $selectedCourses) ? 'checked' : '' }}> Skin & Hair Advance</label>

        <label><input type="checkbox" name="course_advance[]" value="Nail Art Course Advance"
            {{ in_array('Nail Art Course Advance', $selectedCourses) ? 'checked' : '' }}> Nail Art Course Advance</label>

        <label><input type="checkbox" name="course_advance[]" value="Mehandi Course Advance"
            {{ in_array('Mehandi Course Advance', $selectedCourses) ? 'checked' : '' }}> Mehandi Course Advance</label>
    </div>

                <label for="fees_advance">Fees</label>
                <input type="text" class="form-control" id="fees_advance" name="fees_advance"
                    value="{{ $fees['advance'] ?? '' }}" placeholder="Enter fees" />

                <label for="duration_advance">Duration</label>
                <input type="text" class="form-control" id="duration_advance" name="duration_advance"
                    value="{{ $duration['advance'] ?? '' }}" placeholder="Enter duration" />
            </div>
                       <div class="form-group">
                        <label for="staff_management_id" class="col-sm-6 control-label">Staff Management</label>
                        <select class="select2 form-control" id="staff_management_id" name="staff_management_id">
                            <option disabled {{ old('staff_management_id', $course->staff_management_id) ? '' : 'selected' }}>Select Staff Management</option>
                            @foreach($Staff_managements as $Staff_management)
                                <option value="{{ $Staff_management->id }}"
                                    {{ old('staff_management_id', $course->staff_management_id) == $Staff_management->id ? 'selected' : '' }}>
                                    {{ $Staff_management->trainer }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Fees</label>
                        <input type="text" class="form-control" name="fees" value="{{ $course->fees }}"
                            required>
                    </div> --}}
                    <div class="form-group">
                            <label for="start_time">Start Time</label>
                            <input type="text" class="form-control timepicker" id="start_time" name="start_time"
                                value="{{ old('start_time', $course->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $course->start_time)->format('h:i A') : '') }}" required />
                        </div>
                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <input type="text" class="form-control timepicker" id="end_time" name="end_time"
                                value="{{ old('end_time', $course->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $course->end_time)->format('h:i A') : '') }}" required />
                        </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Student</label>
                        <input type="text" class="form-control"  name="max_student" value="{{ $course->max_student }}"
                            required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i>
                    Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete{{ $course->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">
              <h4 class="modal-title "><span class="employee_id">Delete Employee</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('course.destroy', $course) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{$course->name}}</h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i A",
        time_24hr: false
    });
</script>

<script>
    function togglePrivacyDetails() {
        const box = document.getElementById('privacy-section');
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
    }

    function togglePrivacyDetail() {
        const box = document.getElementById('privacy-sections');
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
    }
</script>