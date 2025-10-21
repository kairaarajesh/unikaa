@extends('layouts.master')

@section('css')
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- FullCalendar CSS -->
    <link href="{{ URL::asset('public/plugins/fullcalendar/css/fullcalendar.min.css') }}" rel="stylesheet" />
    <style>
        .fc-event {
            cursor: pointer;
            border-radius: 3px;
            padding: 2px 4px;
        }
        .booking-event {
            background-color: #007bff;
            border-color: #0056b3;
        }
        .booking-event:hover {
            background-color: #0056b3;
        }
        .fc-toolbar h2 {
            font-size: 1.5em;
            font-weight: 600;
        }
        .fc-button {
            background-color: #007bff;
            border-color: #007bff;
        }
        .fc-button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .fc-today-button {
            background-color: #28a745;
            border-color: #28a745;
        }
        .fc-today-button:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .fc-day-number {
            font-weight: 500;
        }
        .fc-day-header {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .booking-details-row {
            margin-bottom: 10px;
        }
        .booking-details-label {
            font-weight: 600;
            color: #495057;
        }
        .booking-details-value {
            color: #212529;
        }
        .no-bookings {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        .calendar-stats {
            margin-bottom: 20px;
        }
        .stat-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Booking Calendar</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Booking Calendar</a></li>
        </ol>
    </div>
@endsection

@section('button')
    <a href="{{ route('booking.index') }}" class="btn btn-primary btn-sm btn-flat">
        <i class="mdi mdi-list mr-2"></i>List View
    </a>
@endsection

@section('content')
@include('includes.flash')

<!-- Calendar Statistics -->
<div class="row calendar-stats">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number">{{ $bookings->count() }}</div>
            <div class="stat-label">Total Bookings</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number">{{ $bookings->where('date', date('d-m-Y'))->count() }}</div>
            <div class="stat-label">Today's Bookings</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number">{{ $bookings->where('date', '>=', date('Y-m-d'))->count() }}</div>
            <div class="stat-label">Upcoming Bookings</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number">{{ $bookings->unique('service')->count() }}</div>
            <div class="stat-label">Services Offered</div>
        </div>
    </div>
</div>

<div style="margin-bottom:10px;">
    <span style="display:inline-block;width:15px;height:15px;background:#ff0000;margin-right:5px;"></span>
    <span>Booking Event</span>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($bookings->count() > 0)
                    <div id="calendar"></div>
                @else
                    <div class="no-bookings">
                        <i class="fa fa-calendar-times fa-3x mb-3"></i>
                        <h4>No Bookings Found</h4>
                        <p>There are no bookings to display in the calendar.</p>
                        <a href="{{ route('booking.index') }}" class="btn btn-primary">View All Bookings</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width:1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="bookingDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="#" id="printBooking" class="btn btn-warning">
                    <i class="fa fa-print me-1"></i> Print
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <!-- jQuery (required for FullCalendar) -->
    <script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script>
    <!-- Moment.js (required for FullCalendar v2) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="{{ URL::asset('public/plugins/fullcalendar/js/fullcalendar.min.js') }}"></script>
    <!-- Bootstrap JS for modal support -->
    <script src="{{ URL::asset('public/assets/js/bootstrap.min.js') }}"></script>

    <script>
    $(document).ready(function() {
        @if($bookings->count() > 0)
        var bookingEvents = [];
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'month',
            editable: false,
            selectable: true,
            selectHelper: true,
            events: function(start, end, timezone, callback) {
                $.ajax({
                    url: '{{ url("admin/booking/calendar-events") }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Group bookings by date
                        var bookingsByDate = {};
                        response.data.forEach(function(booking) {
                            var isoDate = toISODate(booking.date);
                            if (!bookingsByDate[isoDate]) bookingsByDate[isoDate] = [];
                            bookingsByDate[isoDate].push(booking);
                        });
                        var todayStr = moment().format('YYYY-MM-DD');
                        var events = Object.keys(bookingsByDate).map(function(date, idx) {
                            var count = bookingsByDate[date].length;
                            var color = '#28a745';
                            if (date < todayStr) color = '#ff0000';
                            else if (date === todayStr) color = '#007bff';
                            return {
                                id: 'date-' + date,
                                title: 'Booked-' + count,
                                start: date,
                                allDay: true,
                                className: 'booking-event',
                                color: color,
                                extendedProps: {
                                    bookings: bookingsByDate[date],
                                    date: date
                                }
                            };
                        });
                        bookingEvents = events;
                        callback(events);
                    }
                });
            },
            eventClick: function(event) {
                showDayBookings(event.extendedProps.bookings || [], moment(event.start).format('MMMM D, YYYY'));
            },
            eventRender: function(event, element) {
                element.attr('title', event.title);
            },
            dayClick: function(date, jsEvent, view) {
                var dayEvent = bookingEvents.find(function(event) {
                    return moment(event.start).format('YYYY-MM-DD') === date.format('YYYY-MM-DD');
                });
                var bookings = dayEvent ? (dayEvent.extendedProps.bookings || []) : [];
                showDayBookings(bookings, date.format('MMMM D, YYYY'));
            },
            eventLimit: true,
            height: 'auto',
            aspectRatio: 1.35,
            firstDay: 1,
            weekMode: 'liquid',
            weekNumbers: false,
            businessHours: {
                dow: [1, 2, 3, 4, 5, 6, 0],
                start: '08:00',
                end: '18:00',
            },
        });

        function to24Hour(timeStr) {
            if (/^\\d{2}:\\d{2}(:\\d{2})?$/.test(timeStr)) return timeStr;
            var match = timeStr.match(/^(\\d{1,2})(:(\\d{2}))?\\s*(am|pm)$/i);
            if (!match) return '00:00:00';
            var hour = parseInt(match[1]);
            var minute = match[3] ? parseInt(match[3]) : 0;
            var ampm = match[4].toLowerCase();
            if (ampm === 'pm' && hour < 12) hour += 12;
            if (ampm === 'am' && hour === 12) hour = 0;
            return (hour < 10 ? '0' : '') + hour + ':' + (minute < 10 ? '0' : '') + minute + ':00';
        }

        function toISODate(dateStr) {
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return dateStr;
            var match = dateStr.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
            if (match) {
                var d = match[1].padStart(2, '0');
                var m = match[2].padStart(2, '0');
                var y = match[3];
                return y + '-' + m + '-' + d;
            }
            return dateStr;
        }
        function showModalBootstrap5() {
            var modalEl = document.getElementById('bookingModal');
            if (window.bootstrap && bootstrap.Modal) {
                if (typeof bootstrap.Modal.getOrCreateInstance === 'function') {
                    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                } else {
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            } else if (window.$ && $(modalEl).modal) {
                $(modalEl).modal('show');
            } else {
                alert('Modal cannot be shown: Bootstrap JS not loaded.');
            }
        }

        function showDayBookings(bookings, date) {
            var details = `<h5>Bookings for ${date}</h5>`;
            if (!bookings || bookings.length === 0) {
                details += `<p>No bookings for this date.</p>`;
            } else {
                details += `<div class=\"table-responsive\"><table class=\"table table-bordered\"><thead><tr><th>Name</th><th>Service</th><th>Gender</th><th>Email</th><th>Phone</th><th>Location</th><th>Time</th><th>Status</th><th class='artist-header' style='display:none'>Artist</th><th>Action</th></tr></thead><tbody>`;
                bookings.forEach(function(booking, idx) {
                    // Determine if the row should be disabled
                    var isDisabled = booking.status && (booking.status !== '' && booking.status !== null);
                    var saveBtnText = isDisabled ? 'Saved!' : 'Save';
                    details += `<tr data-booking-id=\"${booking.id}\">
                        <td>${booking.name}</td>
                        <td>${booking.service}</td>
                        <td>${booking.gender}</td>
                        <td>${booking.email}</td>
                        <td>${booking.phone}</td>
                        <td>${booking.location}</td>
                        <td>${booking.time}</td>
                        <td>
                            <select class='form-control form-control-sm status-dropdown' data-idx='${idx}' ${isDisabled ? 'disabled' : ''}>
                                <option value=\"Service Completed\"${booking.status === 'Service Completed' ? ' selected' : ''}>Service Completed</option>
                                <option value=\"Reschedule\"${booking.status === 'Reschedule' ? ' selected' : ''}>Reschedule</option>
                                <option value=\"Appointment Canceled\"${booking.status === 'Appointment Canceled' ? ' selected' : ''}>Appointment Canceled</option>
                            </select>
                        </td>
                        <td>
                            <select class='form-control form-control-sm artist-dropdown' data-idx='${idx}' style='display:none' ${isDisabled ? 'disabled' : ''}></select>
                        </td>
                        <td>
                            <button class='btn btn-success btn-sm save-status-btn' data-idx='${idx}' style='display:none' ${isDisabled ? 'disabled' : ''}>${saveBtnText}</button>
                        </td>
                    </tr>`;
                });
                details += `</tbody></table></div>`;
            }
            $('#bookingDetails').html(details);
            $('#printBooking').hide();
            showModalBootstrap5();

            $.getJSON('/admin/booking/get-artists', function(artists) {
                $('.status-dropdown').each(function() {
                    var idx = $(this).data('idx');
                    var status = $(this).val();
                    var row = $(this).closest('tr');
                    var artistDropdown = row.find('.artist-dropdown');
                    var saveBtn = row.find('.save-status-btn');
                    var artistHeader = $('.artist-header');
                    var isDisabled = $(this).prop('disabled');
                    artistDropdown.empty();
                    artists.forEach(function(artist) {
                        artistDropdown.append(`<option value=\"${artist.id}\">${artist.employee_name}</option>`);
                    });
                    if (row.data('booking-id') && row.data('booking-id') && row.data('booking-id') !== '' && row.data('booking-id') !== null) {
                        var booking = bookings[idx];
                        if (booking.status === 'Service Completed' && booking.artist) {
                            artistDropdown.val(booking.artist);
                        }
                    }
                    if (status === 'Service Completed') {
                        artistDropdown.show();
                        saveBtn.show();
                        artistHeader.show();
                    } else {
                        artistDropdown.hide();
                        saveBtn.show();
                        artistHeader.hide();
                    }
                    if (isDisabled) {
                        artistDropdown.prop('disabled', true);
                        saveBtn.prop('disabled', true);
                    }
                });
            });

            $('#bookingDetails').on('change', '.status-dropdown', function() {
                var idx = $(this).data('idx');
                var status = $(this).val();
                var row = $(this).closest('tr');
                var artistDropdown = row.find('.artist-dropdown');
                var saveBtn = row.find('.save-status-btn');
                var artistHeader = $('.artist-header');
                if (status === 'Service Completed') {
                    artistDropdown.show();
                    saveBtn.show();
                    artistHeader.show();
                } else {
                    artistDropdown.hide();
                    saveBtn.show();
                    artistHeader.hide();
                }
            });

            $('#bookingDetails').on('click', '.save-status-btn', function() {
                var idx = $(this).data('idx');
                var row = $(this).closest('tr');
                var bookingId = row.data('booking-id');
                var status = row.find('.status-dropdown').val();
                var artist = status === 'Service Completed' ? row.find('.artist-dropdown').val() : null;
                $.ajax({
                    url: '/admin/booking/' + bookingId + '/update-status-artist',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        status: status,
                        artist: artist
                    },
                    success: function(res) {
                        if (res.success) {
                            row.find('.save-status-btn').text('Saved!').removeClass('btn-success').addClass('btn-secondary').prop('disabled', true);
                            row.find('.status-dropdown').prop('disabled', true);
                            row.find('.artist-dropdown').prop('disabled', true);
                        }
                    }
                });
            });
        }
        @endif
    });
    </script>
@endsection