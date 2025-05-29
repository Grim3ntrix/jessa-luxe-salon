<?php 
session_start();
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jessa Luxe Salon - Schedules</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <p>Manage daily schedules</p>

    <button id="add-schedule-btn">Add Schedule</button>

    <div id="schedule-table"></div>
</div>

<!-- Add Schedule Modal -->
<div id="addScheduleModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeScheduleModal">&times;</span>
        <h2 id="schedule-modal-title">Add Schedule</h2>
        <form id="add-schedule-form">
            <div class="form-group">
                <label>Date:</label>
                <input type="date" id="schedule-date" required>
            </div>

            <div class="form-group">
                <label>Time:</label>
                <input type="time" id="schedule-time" required>
            </div>

            <div class="form-group">
                <label>Service:</label>
                <select id="schedule-service" required>
                    <option value="">Select service</option>
                    <!-- Populate options dynamically if you want -->
                </select>
            </div>

            <button type="submit" id="schedule-modal-submit-btn">Save</button>
            <input type="hidden" id="schedule-id">
            <input type="hidden" id="schedule-status">
        </form>
    </div>
</div>

<script src="/js/schedule-table.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const addScheduleBtn = document.getElementById('add-schedule-btn');
    const modal = document.getElementById('addScheduleModal');
    const closeModal = document.getElementById('closeScheduleModal');
    const form = document.getElementById('add-schedule-form');
    const modalTitle = document.getElementById('schedule-modal-title');
    const modalSubmitBtn = document.getElementById('schedule-modal-submit-btn');
    const scheduleIdInput = document.getElementById('schedule-id');

    axios.get('/api/admin/services')
      .then(res => {
        if (res.data.success) {
          const select = document.getElementById('schedule-service');
          res.data.data.forEach(service => {
            const option = document.createElement('option');
            option.value = service.id;
            option.textContent = service.name;
            select.appendChild(option);
          });
        }
      })
      .catch(err => console.error('Failed to load services:', err));

    let isEditMode = false;

    // Open modal for adding
    addScheduleBtn.addEventListener('click', () => {
        isEditMode = false;
        modalTitle.textContent = 'Add Schedule';
        modalSubmitBtn.textContent = 'Save';
        form.reset();
        scheduleIdInput.value = '';
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

    // Delegate edit button clicks (assuming edit buttons have class 'edit-btn' and data-id)
    document.addEventListener('click', function(e) {
        if (e.target?.classList?.contains('edit-btn')) {
            const scheduleId = e.target.dataset.id;

            // Fetch schedule details - adjust URL to your API endpoint
            axios.get(`/api/admin/schedules/${scheduleId}`)
                .then(response => {
                    const schedule = response.data.data;

                    document.getElementById('schedule-service').value = schedule.service_id;
                    document.getElementById('schedule-date').value = schedule.schedule_date;
                    document.getElementById('schedule-time').value = schedule.schedule_time;
                    document.getElementById('schedule-status').value = schedule.status;

                    scheduleIdInput.value = scheduleId;

                    isEditMode = true;
                    modalTitle.textContent = 'Edit Schedule';
                    modalSubmitBtn.textContent = 'Update';
                    modal.style.display = 'flex';
                })
                .catch(err => {
                    console.error('Error fetching schedule:', err);
                    alert('Failed to fetch schedule details.');
                });
        }
    });

    // Handle form submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const baseScheduleData = {
            service_id: document.getElementById('schedule-service').value,
            schedule_date: document.getElementById('schedule-date').value,
            schedule_time: document.getElementById('schedule-time').value,
        };

        if (isEditMode) {
            baseScheduleData.schedule_status = document.getElementById('schedule-status').value;

            const scheduleId = scheduleIdInput.value;
            axios.put(`/api/admin/schedules/${scheduleId}`, baseScheduleData)
                .then(response => {
                    if (response.data.success) {
                        alert('Schedule updated successfully!');
                        modal.style.display = 'none';
                        location.reload();
                    } else {
                        alert('Failed to update schedule: ' + response.data.message);
                    }
                })
                .catch(error => {
                    console.error('Error updating schedule:', error);
                    alert('An error occurred.');
                });
        } else {
            axios.post('/api/admin/schedules', baseScheduleData)
                .then(response => {
                    if (response.data.success) {
                        alert('Schedule added successfully!');
                        modal.style.display = 'none';
                        form.reset();
                        location.reload();
                    } else {
                        alert('Failed to add schedule: ' + response.data.message);
                    }
                })
                .catch(error => {
                    console.error('Error adding schedule:', error);
                    alert('An error occurred.');
                });
        }
    });
});
</script>

</body>
</html>
