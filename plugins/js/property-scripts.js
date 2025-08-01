 document.addEventListener('DOMContentLoaded', function () {
    // Get DOM elements once on page load
    const viewingModalElement = document.getElementById('viewing-modal');
    const form = document.getElementById('viewing-form');
    const modalTitle = document.getElementById('viewingModalLabel');
    const viewingMethodInput = document.getElementById('viewing-method');
    const modalSubmitBtn = document.getElementById('modal-submit-btn');

    // Initialize Bootstrap modal instance
    const viewingModal = new bootstrap.Modal(viewingModalElement);

    // Function to open the modal for creating a new viewing
    window.openCreateModal = function() {
        form.reset();
        modalTitle.textContent = 'Schedule New Viewing';
        form.action = `{{ route('create.viewing') }}`;
        viewingMethodInput.value = 'POST';
        modalSubmitBtn.textContent = 'Save Viewing';
        viewingModal.show();
    };

    // Function to open the modal for editing an existing viewing
    window.openEditModal = function(button) {
        const row = button.closest('tr');

        // Get data from data attributes
        const viewingId = row.dataset.viewingId;
        const clientName = row.dataset.clientName;
        const clientEmail = row.dataset.clientEmail;
        const clientPhone = row.dataset.clientPhone;
        const scheduledAt = row.dataset.scheduledAt;
        const agentId = row.dataset.agentId;
        const status = row.dataset.status;
        const notes = row.dataset.notes;

        // Update modal title and form action
        modalTitle.textContent = 'Edit Viewing';
        form.action = `{{ route('update.viewing', ['id' => 'VIEWING_ID']) }}`.replace('VIEWING_ID', viewingId);
        viewingMethodInput.value = 'PUT';
        modalSubmitBtn.textContent = 'Update Viewing';

        // Populate form fields
        document.getElementById('client_name').value = clientName;
        document.getElementById('client_email').value = clientEmail;
        document.getElementById('client_phone').value = clientPhone;
        document.getElementById('scheduled_at').value = scheduledAt;
        document.getElementById('agent_id').value = agentId;
        document.getElementById('status').value = status;
        document.getElementById('notes').value = notes || '';

        viewingModal.show();
    };

});