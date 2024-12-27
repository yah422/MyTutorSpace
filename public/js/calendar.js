document.addEventListener('DOMContentLoaded', function () {
    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        events: '/tutor-availabilities', // Your route to fetch availabilities
        eventColor: '#90EE90', // Available slot color
        eventBorderColor: '#90EE90',
        eventClick: function (info) {
            // Handle event click (for example, to book)
        },
    });

    calendar.render();
});
