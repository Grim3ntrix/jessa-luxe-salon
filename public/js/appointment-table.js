document.addEventListener('DOMContentLoaded', function() {
    axios.get('/api/admin/appointment')
        .then(response => {
            if (response.data.success) {
                const services = response.data.data;

                const table = new Tabulator("#appointment-table", {
                    data: services,
                    layout: "fitColumns",
                    pagination: "local",
                    paginationSize: 10,
                    columns: [
                        { title: "ID", field: "id", width: 50 },
                        { title: "Client", field: "user.username", minWidth: 120 },
                        { title: "Service", field: "service.name", minWidth: 150 },
                        { 
                            title: "Description", minWidth: 100,
                            field: "service.description", 
                            formatter: "textarea", 
                            tooltip: true      
                        },
                        { 
                            title: "Duration", 
                            field: "service.duration", 
                            minWidth: 100,
                            formatter: function(cell, formatterParams) {
                                return cell.getValue() + " min.";
                            }
                        },
                        { title: "Price", field: "service.price", minWidth: 100 },
                        { title: "Appointment Date", field: "appointment_date", minWidth: 180 },
                        { title: "Appointment Time", field: "appointment_time", minWidth: 180 },
                        { title: "Status", field: "status", minWidth: 100 },
                        {
                            title: "Actions",
                            formatter: function(cell, formatterParams, onRendered) {
                                const rowData = cell.getRow().getData();
                                if (rowData.status === 'pending') {
                                    return `
                                        <button class="confirm-btn" data-id="${rowData.id}">Confirm</button>
                                        <button class="cancel-btn" data-id="${rowData.id}">Cancel</button>
                                    `;
                                } else {
                                    return `<span style="color: gray;">No actions</span>`;
                                }
                            },
                            width: 200
                        }
                    ]
                });

                document.querySelector('#appointment-table').addEventListener('click', function(e) {
                    if (e.target.classList.contains('confirm-btn')) {
                        const appointmentId = e.target.getAttribute('data-id');
                        updateAppointmentStatus(appointmentId, 'confirmed');
                    }
                    if (e.target.classList.contains('cancel-btn')) {
                        const appointmentId = e.target.getAttribute('data-id');
                        updateAppointmentStatus(appointmentId, 'cancelled');
                    }
                });

                function updateAppointmentStatus(appointmentId, status) {
                    axios.post('/api/admin/appointment/update', {
                        id: appointmentId,
                        status: status
                    })
                    .then(response => {
                        if (response.data.success) {
                            alert('Status updated!');
                            table.replaceData('/api/admin/appointment');
                            window.location.href = '/admin/appointments';
                        } else {
                            alert('Failed: ' + response.data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            } else {
                console.error('Failed to load services:', response.data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching services:', error);
        });
});
