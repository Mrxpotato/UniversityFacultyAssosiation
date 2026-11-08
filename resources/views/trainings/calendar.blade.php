<x-public-layout title="Training Calendar">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-700 via-primary-800 to-primary-900 py-12 sm:py-20 relative overflow-hidden">
        <!-- Decorative Blue accents -->
        <div class="absolute top-0 left-0 w-40 sm:w-64 h-40 sm:h-64 bg-primary-500/10 rounded-full filter blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-64 sm:w-96 h-64 sm:h-96 bg-primary-400/5 rounded-full filter blur-3xl translate-x-1/4 translate-y-1/4"></div>
        <div class="absolute top-1/2 right-10 w-24 sm:w-32 h-24 sm:h-32 bg-primary-500/10 rounded-full filter blur-2xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white relative z-10">
            <div class="inline-flex items-center justify-center w-14 h-14 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 shadow-lg shadow-primary-500/30 mb-4 sm:mb-6">
                <span class="material-icons text-2xl sm:text-4xl text-primary-900">event</span>
            </div>
            <h1 class="mt-3 sm:mt-4 text-2xl sm:text-4xl md:text-5xl font-display font-bold">Training Calendar</h1>
            <p class="mt-2 sm:mt-3 text-base sm:text-xl text-primary-200/80">View all upcoming trainings and seminars</p>
            <div class="mt-3 sm:mt-4 w-16 sm:w-24 h-1 bg-gradient-to-r from-primary-400 to-primary-600 mx-auto rounded-full"></div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-12">
        <!-- Back Link -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <a href="{{ route('trainings.index') }}" class="inline-flex items-center text-primary-700 hover:text-primary-600 transition-colors font-medium">
                <span class="material-icons mr-1">arrow_back</span>
                Back to List View
            </a>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-primary-100 text-primary-800 border border-primary-200">
                    <span class="w-3 h-3 rounded-full bg-primary-500 mr-2"></span>
                    Seminar
                </span>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-primary-50 text-primary-800 border border-primary-200">
                    <span class="w-3 h-3 rounded-full bg-primary-500 mr-2"></span>
                    Workshop
                </span>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-primary-200 text-primary-900 border border-primary-300">
                    <span class="w-3 h-3 rounded-full bg-primary-600 mr-2"></span>
                    Training
                </span>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-primary-100 text-primary-900 border border-primary-300">
                    <span class="w-3 h-3 rounded-full bg-primary-600 mr-2"></span>
                    Conference
                </span>
            </div>
        </div>

        <!-- Calendar -->
        <div class="bg-white rounded-2xl shadow-xl shadow-primary-100/50 p-3 sm:p-6 border border-primary-100">
            <div id="calendar"></div>
        </div>

        <!-- Event Details Modal -->
        <div id="eventModal" class="hidden" fixed inset-0 bg-primary-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-primary-200">
                <div id="modalContent" class="p-6">
                    <!-- Modal content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar (self-hosted; styles are injected by the bundle) -->
    <script src="{{ asset('vendor/fullcalendar/index.global.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                events: function(info, successCallback, failureCallback) {
                    fetch(`{{ route('trainings.calendar') }}?start=${info.startStr}&end=${info.endStr}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => {
                        console.error('Error loading events:', error);
                        failureCallback(error);
                    });
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
                eventMouseEnter: function(info) {
                    info.el.style.cursor = 'pointer';
                    
                    // Show tooltip
                    const tooltip = document.createElement('div');
                    tooltip.className = 'absolute z-50 bg-gray-900 text-white px-3 py-2 rounded-lg text-sm';
                    tooltip.style.top = info.jsEvent.pageY + 10 + 'px';
                    tooltip.style.left = info.jsEvent.pageX + 10 + 'px';
                    tooltip.innerHTML = `
                        <strong>${info.event.title}</strong><br>
                        <span class="text-xs">${info.event.extendedProps.type}</span><br>
                        <span class="text-xs">${info.event.extendedProps.venue || 'TBA'}</span>
                    `;
                    tooltip.id = 'event-tooltip';
                    document.body.appendChild(tooltip);
                },
                eventMouseLeave: function(info) {
                    const tooltip = document.getElementById('event-tooltip');
                    if (tooltip) {
                        tooltip.remove();
                    }
                },
                height: 'auto',
                aspectRatio: 1.8,
                eventDisplay: 'block',
                displayEventTime: true,
                displayEventEnd: true,
                slotMinTime: '07:00:00',
                slotMaxTime: '20:00:00',
                nowIndicator: true,
                navLinks: true,
                weekNumbers: false,
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5],
                    startTime: '08:00',
                    endTime: '17:00',
                },
            });
            
            calendar.render();
        });
    </script>

    <style>
        /* Calendar styling - Theme-aware */
        #calendar {
            font-family: 'Inter', 'Roboto', sans-serif;
        }
        
        .fc-event {
            border-radius: 6px;
            border: none !important;
            padding: 3px 6px;
            font-weight: 500;
        }
        
        .fc-event-title {
            font-weight: 500;
        }
        
        .fc-daygrid-event {
            white-space: normal;
        }
        
        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: var(--color-primary-800, #1e3a8a) !important;
        }
        
        .fc-button {
            background: linear-gradient(135deg, var(--color-primary-700, #1e3a8a) 0%, var(--color-primary-800, #162b6a) 100%) !important;
            border: 1px solid color-mix(in srgb, var(--color-primary-400, #6480e6) 30%, transparent) !important;
            text-transform: capitalize !important;
            font-weight: 500 !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            transition: all 0.3s ease !important;
        }
        
        .fc-button:hover {
            background: linear-gradient(135deg, var(--color-primary-600, #2b4ac7) 0%, var(--color-primary-700, #1e3a8a) 100%) !important;
            box-shadow: 0 4px 15px color-mix(in srgb, var(--color-primary-700, #1e3a8a) 30%, transparent) !important;
            transform: translateY(-1px);
        }
        
        .fc-button-active {
            background: linear-gradient(135deg, var(--color-primary-500, #4263cc) 0%, var(--color-primary-600, #2b4ac7) 100%) !important;
            color: #ffffff !important;
            border-color: var(--color-primary-400, #6480e6) !important;
        }
        
        .fc-button-active:hover {
            background: linear-gradient(135deg, var(--color-primary-400, #6480e6) 0%, var(--color-primary-500, #4263cc) 100%) !important;
        }
        
        .fc-day-today {
            background: color-mix(in srgb, var(--color-primary-200, #bac8ff) 15%, transparent) !important;
        }
        
        .fc-col-header-cell {
            background: linear-gradient(180deg, var(--color-primary-50, #f0f4ff) 0%, var(--color-primary-100, #dbe4ff) 100%) !important;
            border-bottom: 2px solid color-mix(in srgb, var(--color-primary-400, #6480e6) 30%, transparent) !important;
        }
        
        .fc-col-header-cell-cushion {
            color: var(--color-primary-800, #1e3a8a) !important;
            font-weight: 600 !important;
            padding: 12px 4px !important;
        }
        
        .fc-daygrid-day-number {
            color: var(--color-primary-800, #1e3a8a) !important;
            font-weight: 500 !important;
            min-width: 28px;
            min-height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .fc-event {
            background: linear-gradient(135deg, var(--color-primary-700, #1e3a8a) 0%, var(--color-primary-800, #162b6a) 100%) !important;
            box-shadow: 0 2px 8px color-mix(in srgb, var(--color-primary-700, #1e3a8a) 20%, transparent) !important;
        }
        
        .fc-event:hover {
            background: linear-gradient(135deg, var(--color-primary-600, #2b4ac7) 0%, var(--color-primary-700, #1e3a8a) 100%) !important;
            box-shadow: 0 4px 12px color-mix(in srgb, var(--color-primary-700, #1e3a8a) 30%, transparent) !important;
        }
        
        .fc-event.workshop {
            background: linear-gradient(135deg, var(--color-accent-500, #0ea5e9) 0%, var(--color-accent-600, #0284c7) 100%) !important;
            color: #ffffff !important;
        }
        
        .fc-list-event:hover td {
            background-color: color-mix(in srgb, var(--color-primary-200, #bac8ff) 15%, transparent) !important;
        }
        
        .fc-list-day-cushion {
            background: linear-gradient(90deg, var(--color-primary-700, #1e3a8a) 0%, var(--color-primary-800, #162b6a) 100%) !important;
            color: #ffffff !important;
        }
        
        .fc-scrollgrid {
            border-color: color-mix(in srgb, var(--color-primary-200, #bac8ff) 30%, transparent) !important;
        }
        
        .fc-scrollgrid td, .fc-scrollgrid th {
            border-color: color-mix(in srgb, var(--color-primary-700, #1e3a8a) 10%, transparent) !important;
        }
        
        .fc-timegrid-now-indicator-line {
            border-color: var(--color-accent-500, #0ea5e9) !important;
        }
        
        .fc-timegrid-now-indicator-arrow {
            border-color: var(--color-accent-500, #0ea5e9) !important;
            border-top-color: transparent !important;
            border-bottom-color: transparent !important;
        }
    </style>
</x-public-layout>
