<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Course</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form method="POST" action="{{ route('course.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" placeholder="Enter Name" id="name" name="name"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Batch</label>
                            <input type="text" class="form-control" placeholder="Enter Batch" id="batch" name="batch"
                                required />
                        </div>

                        <label style="cursor:pointer;">
    <input type="checkbox" name="type[]" value="BASIC" id="basic-checkbox" onchange="toggleSection('basic')" />
    <u>BASIC</u>
</label>

<div id="basic-section" style="display:none; margin-left: 20px;">
    <div class="form-group">
        <label><input type="checkbox" name="course_basic[]" value="Skin & Hair"> Skin & Hair</label><br>
        <label><input type="checkbox" name="course_basic[]" value="Nail Art Course"> Nail Art Course</label><br>
        <label><input type="checkbox" name="course_basic[]" value="Mehandi Course"> Mehandi Course</label>
    </div>

    <label for="fees_basic">Fees</label>
    <input type="text" class="form-control" placeholder="Enter fees" id="fees_basic" name="fees_basic" /><br>

    <label for="duration_basic">Duration</label>
    <input type="text" class="form-control" placeholder="Enter duration" id="duration_basic" name="duration_basic" />
</div>

<br>

<!-- ADVANCE Section -->
<label style="cursor:pointer;">
    <input type="checkbox" name="type[]" value="ADVANCE" id="advance-checkbox" onchange="toggleSection('advance')" />
    <u>ADVANCE</u>
</label>

<div id="advance-section" style="display:none; margin-left: 20px;">
    <div class="form-group">
        <label><input type="checkbox" name="course_advance[]" value="Skin & Hair Advance"> Skin & Hair Advance</label><br>
        <label><input type="checkbox" name="course_advance[]" value="Nail Art Course Advance"> Nail Art Course Advance</label><br>
        <label><input type="checkbox" name="course_advance[]" value="Mehandi Course Advance"> Mehandi Course Advance</label>
    </div>

    <label for="fees_advance">Fees</label>
    <input type="text" class="form-control" placeholder="Enter fees" id="fees_advance" name="fees_advance" /><br>

    <label for="duration_advance">Duration</label>
    <input type="text" class="form-control" placeholder="Enter duration" id="duration_advance" name="duration_advance" />
</div>
                        <div class="form-group">
                            <label for="category" class="col-sm-6 control-label">Staff Management</label>
                                <select class="select select2s-hidden-accessible form-control" id="staff_management_id" name="staff_management_id">
                                    <option selected disabled>Select Brand</option>
                                    @foreach($Staff_managements as $Staff_management)
                                        <option value="{{ $Staff_management->id }}">
                                            {{ $Staff_management->trainer }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>
                           <div class="form-group">
                                <label for="start_time">Start Time</label>
                                <input type="text" class="form-control timepicker"id="start_time" name="start_time" required />
                            </div>
                            <div class="form-group">
                                <label for="end_time">End Time</label>
                                <input type="text" class="form-control timepicker" id="end_time" name="end_time" required />
                            </div>
                        <div class="form-group">
                            <label for="name">Count Of Student</label>
                            <input type="text" class="form-control" placeholder="Enter Brand" id="max_student" name="max_student"
                                required />
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
        dateFormat: "h:i K",
        time_24hr: false
    });
</script>

<script>
function toggleSection(type) {
    const checkbox = document.getElementById(`${type}-checkbox`);
    const section = document.getElementById(`${type}-section`);
    section.style.display = checkbox.checked ? 'block' : 'none';
}
</script>