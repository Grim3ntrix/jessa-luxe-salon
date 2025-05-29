document.addEventListener('DOMContentLoaded', function() {
    axios.get('/api/admin/services')
        .then(response => {
            if (response.data.success) {
                const services = response.data.data;

                new Tabulator("#services-table", {
                    data: services,
                    layout: "fitColumns",
                    pagination: "local",
                    paginationSize: 10,
                    columns: [
                        { title: "ID", field: "id" },
                        { title: "Name", field: "name", minWidth: 120  },
                        { 
                            title: "Description", 
                            field: "description", 
                            minWidth: 100, 
                            tooltip: true },
                        {                             
                            title: "Duration", 
                            field: "duration",
                            formatter: "textarea", 
                            minWidth: 100,
                            formatter: function(cell, formatterParams) {
                                return cell.getValue() + " min.";
                            } 
                        },
                        { title: "Price", 
                            field: "price",
                            formatter: "money",
                            formatterParams: {
                                symbol: "₱",
                                thousand: ",",
                                precision: 2
                            }
                         },
                        { 
                        title: "Actions", 
                            formatter: function(cell, formatterParams, onRendered){
                                const id = cell.getRow().getData().id;
                                return `
                                    <button class="edit-btn" data-id="${id}">Edit</button>
                                    <button class="delete-btn" data-id="${id}">Delete</button>
                                `;
                            }, 
                            width: 150 
                        }
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

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('edit-btn')) {
        const id = event.target.dataset.id;
        // Fetch row data or open modal with prefilled fields
        openEditModal(id);
    }

    if (event.target.classList.contains('delete-btn')) {
        const id = event.target.dataset.id;
        if (confirm('Are you sure you want to delete this service?')) {
            axios.delete('/api/admin/services', { data: { id } })
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
