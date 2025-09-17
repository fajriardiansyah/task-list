<x-filament-panels::page>
     <div id="calendar"></div>

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: {!! $this->getTasks() !!},
                    eventClick: function(info) {
                        if (info.event.url) {
                            window.open(info.event.url, '_self');
                            info.jsEvent.preventDefault(); // don't follow a link in the event.url
                        }
                    }
                });
                calendar.render();
            });
        </script>
    @endpush
</x-filament-panels::page>
