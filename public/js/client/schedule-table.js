document.addEventListener('DOMContentLoaded', function() {
    axios.get('/client/get-schedules')
        .then(response => {
            const data = response.data;
            if (data.success) {
                const schedules = data.data;
                const container = document.getElementById('schedule-cards');
                
                schedules.forEach(schedule => {
                    const { color, bg } = getStatusClasses(schedule.status);

                    const card = document.createElement('div');
                    card.classList.add('card');
                    
                    card.innerHTML = `
                        <h3>${schedule.service.name}</h3>
                        <p class="description">${schedule.service.description}</p>
                        <div class="card-details">
                            <p><strong>Duration:</strong> ${schedule.service.duration} min.</p>
                            <p><strong>Date:</strong> ${formatDate(schedule.schedule_date)}</p>
                            <p><strong>Time:</strong> ${formatTime(schedule.schedule_time)}</p>
                            <p>
                                <strong>Status:</strong> 
                                <span class="${getStatusClasses(schedule.status)}">
                                    ${schedule.status.charAt(0).toUpperCase() + schedule.status.slice(1)}
                                </span>
                            </p>
                            <p><strong>Price: </strong> <span class="price">₱${schedule.service.price}</span></p>
                        </div>
                        <div class="card-actions">
                            <button class="book-button" data-schedule-id="${schedule.id}">Book Now</button>
                        </div>
                    `;
                    
                    container.appendChild(card);
                });

                container.addEventListener('click', function(event) {
                    if (event.target.classList.contains('book-button')) {
                        const scheduleId = event.target.getAttribute('data-schedule-id');
                        bookSchedule(scheduleId);
                    }
                });
            } else {
                console.error('Failed to fetch schedules:', data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching schedules:', error);
        });

    function getStatusClasses(status) {
        const statusMap = {
            available: 'badge rounded-pill bg-success',
            booked: 'badge rounded-pill bg-warning',
            completed: 'badge rounded-pill bg-primary',
            blocked: 'badge rounded-pill bg-danger',
            cancelled: 'badge rounded-pill bg-secondary'
        };
        const key = status.toLowerCase();
        return statusMap[key] || 'badge rounded-pill bg-light';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function formatTime(timeString) {
        const [hour, minute] = timeString.split(':');
        const date = new Date();
        date.setHours(hour, minute);
        return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
    }

    function bookSchedule(scheduleId) {
        axios.post('/client/book-schedule', { schedule_id: scheduleId }, { withCredentials: true })
            .then(response => {
                // console.log('Booking response:', response);
                if (response.data.success) {
                    alert('Successfully booked!');
                    setTimeout(() => {
                        location.reload();
                    }, 100);
                } else {
                    alert('Failed to book: ' + response.data.message);
                }
            })
            .catch(error => {
                // console.error('Booking error:', error);
                alert('An error occurred while booking.');
            });
    }
});