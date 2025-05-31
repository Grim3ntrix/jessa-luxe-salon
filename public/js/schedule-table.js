document.addEventListener('DOMContentLoaded', function() {
    axios.get('/api/admin/schedules')
        .then(response => {
            if (response.data.success) {
                const schedules = response.data.data;

                new Tabulator("#schedule-table", {
                    data: schedules,
                    layout: "fitColumns",
                    pagination: "local",
                    paginationSize: 10,
                    columns: [
                        { title: "ID", field: "id", width: 50 },
                        { title: "Service Name", field: "service.name", minWidth: 150 },
                        { 
                            title: "Description", 
                            field: "service.description", 
                            minWidth: 150, 
                            formatter: "textarea", 
                            tooltip: true
                        },
                        { 
                            title: "Duration", 
                            field: "service.duration", 
                            minWidth: 100,
                            formatter: function(cell) {
                                return cell.getValue() + " min.";
                            }
                        },
                        { 
                            title: "Price", 
                            field: "service.price", 
                            minWidth: 100,
                            formatter: "money",
                            formatterParams: {
                                symbol: "₱",
                                thousand: ",",
                                precision: 2
                            }
                        },
                        { 
                            title: "Schedule Date", 
                            field: "schedule_date", 
                            minWidth: 150 
                        },
                        { 
                            title: "Schedule Time", 
                            field: "schedule_time", 
                            minWidth: 150 
                        },
                        { 
                        title: "Status", 
                        field: "status", 
                        minWidth: 100,
                            formatter: function(cell) {
                                const status = cell.getValue().toLowerCase();

                                const statusClassMap = {
                                    confirmed: 'badge rounded-pill bg-success',
                                    cancelled: 'badge rounded-pill bg-primary',
                                    cancelled: 'badge rounded-pill bg-secondary'
                                };

                                const badgeClass = statusClassMap[status] || 'badge rounded-pill bg-light';

                                return `<span class="${badgeClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
                            }
                        },
                        {
                            title: "Actions",
                            formatter: function(cell) {
                                const id = cell.getRow().getData().id;
                                return `
                                    <button class="edit-btn" data-id="${id}">Edit</button>
                                    <button class="delete-btn" data-id="${id}">Delete</button>
                                `;
                            },
                            width: 200
                        }
                    ]
                });
            } else {
                console.error('Failed to load schedules:', response.data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching schedules:', error);
        });
});

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('edit-btn')) {
        const id = event.target.dataset.id;
        openEditModal(id);  // you can define this elsewhere
    }

    if (event.target.classList.contains('delete-btn')) {
        const id = event.target.dataset.id;
        if (confirm('Are you sure you want to delete this schedule?')) {
            axios.delete('/api/admin/schedules', { data: { id } })
                .then(response => {
                    alert(response.data.message);
                    location.reload();
                })
                .catch(error => {
                    console.error('Delete failed:', error);
                });
        }
    }
});
