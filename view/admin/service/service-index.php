<?php 
session_start();
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jessa Luxe Salon - Services</title>
    <link rel="stylesheet" href="/css/nav.css">
    <link rel="stylesheet" href="/css/style.css">

    <!-- Tabulator CDN -->
    <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
    <script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>
<body>

<?php include __DIR__ . '/../../navigation/admin-nav.php'; ?>

<div class="container">
    <p>Manage salon services</p>

    <button id="add-service-btn">Add Service</button>
    <div id="services-table"></div>
    </div>

    <!-- Add Service Modal -->
    <div id="addServiceModal" class="modal">
        <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2 id="modal-title">Add Service</h2>
        <form id="add-service-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" id="service-name" required>
                </div>
                <div class="form-group">
                    <label>Duration (minutes):</label>
                    <input type="number" id="service-duration" required>
                </div>
            </div>

            <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" id="service-price" required>
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea id="service-description" required></textarea>
            </div>

            <button type="submit" id="modal-submit-btn">Save</button>
            <input type="hidden" id="service-id">
        </form>
    </div>
</div>

<script src="/js/services-table.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addServiceBtn = document.getElementById('add-service-btn');
        const modal = document.getElementById('addServiceModal');
        const closeModal = document.getElementById('closeModal');
        const form = document.getElementById('add-service-form');
        const modalTitle = document.getElementById('modal-title');
        const modalSubmitBtn = document.getElementById('modal-submit-btn');
        const serviceIdInput = document.getElementById('service-id');

        let isEditMode = false;

        // Open modal for adding
        addServiceBtn.addEventListener('click', () => {
            isEditMode = false;
            modalTitle.textContent = 'Add Service';
            modalSubmitBtn.textContent = 'Save';
            form.reset();
            serviceIdInput.value = '';
            modal.style.display = 'flex';
        });

        // Close modal
        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });

        // Delegate edit button clicks
        document.addEventListener('click', function(e) {
            if (e.target?.classList?.contains('edit-btn')) {
                const serviceId = e.target.dataset.id;

                // Fetch service details
                axios.get(`/api/admin/services/${serviceId}`)
                    .then(response => {
                        const service = response.data.data;
                        document.getElementById('service-name').value = service.name;
                        document.getElementById('service-description').value = service.description;
                        document.getElementById('service-duration').value = service.duration;
                        document.getElementById('service-price').value = service.price;
                        serviceIdInput.value = serviceId;

                        isEditMode = true;
                        modalTitle.textContent = 'Edit Service';
                        modalSubmitBtn.textContent = 'Update';
                        modal.style.display = 'flex'; // <-- THIS pops up the modal!
                    })
                    .catch(err => {
                        console.error('Error fetching service:', err);
                        alert('Failed to fetch service details.');
                    });
            }
        });

        // Handle form submit
        form.addEventListener('submit', function(e) {
        e.preventDefault();

        const serviceData = {
            name: document.getElementById('service-name').value,
            description: document.getElementById('service-description').value,
            duration: document.getElementById('service-duration').value,
            price: document.getElementById('service-price').value
        };

        console.log('Submitting serviceData:', serviceData); // <-- ADD THIS LINE

        if (isEditMode) {
            const serviceId = serviceIdInput.value;
            axios.put(`/api/admin/services/${serviceId}`, serviceData)
                .then(response => {
                    if (response.data.success) {
                        alert('Service updated successfully!');
                        modal.style.display = 'none';
                        location.reload();
                    } else {
                        alert('Failed to update service: ' + response.data.message);
                    }
                })
                .catch(error => {
                    console.error('Error updating service:', error);
                    alert('An error occurred.');
                });
        } else {
            axios.post('/api/admin/services', serviceData)
                .then(response => {
                    if (response.data.success) {
                        alert('Service added successfully!');
                        modal.style.display = 'none';
                        form.reset();
                        location.reload();
                    } else {
                        alert('Failed to add service: ' + response.data.message);
                    }
                })
                .catch(error => {
                    console.error('Error adding service:', error);
                    alert('An error occurred.');
                });
        }
    });

    
});

function openEditModal(serviceId) {
    // Your code to open and populate the edit modal for the service with this ID
    //   console.log("Opening edit modal for service ID:", serviceId);
    // e.g., show modal, fill form fields with service data...
}

</script>

</body>
</html>
