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
                        { title: "Name", field: "name" },
                        { title: "Description", field: "description" },
                        { title: "Duration", field: "duration" },
                        { title: "Price", field: "price" }
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