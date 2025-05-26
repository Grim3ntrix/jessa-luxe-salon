document.addEventListener('DOMContentLoaded', function() {
    axios.get('/api/admin/appointment')
        .then(response => {
            if (response.data.success) {
                const services = response.data.data;

                new Tabulator("#appointment-table", {
                    data: services,
                    layout: "fitColumns",
                    pagination: "local",
                    paginationSize: 10,
                    columns: [
                        { title: "ID", field: "id" },
                        { title: "Service", field: "service.name" },
                        { title: "Client", field: "user.username" },
                        { title: "Appointment Date", field: "appointment_date" },
                        { title: "Appointment Time", field: "appointment_time" },
                        { title: "Status", field: "status" }
                    ]
                });
            } else {
                console.error('Failed to load services:', response.data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching services:', error);
        });
});