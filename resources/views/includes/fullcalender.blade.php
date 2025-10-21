<!-- FullCalendar CSS -->
<link href="{{ URL::asset('plugins/fullcalendar/css/fullcalendar.min.css') }}" rel="stylesheet" />

<div class="card">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- jQuery (required for FullCalendar) -->
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<!-- Moment.js (required for FullCalendar v2) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!-- FullCalendar JS -->
<script src="{{ URL::asset('plugins/fullcalendar/js/fullcalendar.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'month',
        editable: false,
        events: [
            // Example events
            {
                title: 'Sample Event',
                start: moment().startOf('month').add(5, 'days').format('YYYY-MM-DD')
            },
            {
                title: 'Another Event',
                start: moment().startOf('month').add(10, 'days').format('YYYY-MM-DD')
            }
        ]
    });
});
</script>
