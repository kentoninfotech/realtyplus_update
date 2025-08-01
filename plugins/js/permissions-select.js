document.addEventListener('DOMContentLoaded', function () {
    // Select All button
    const selectAllBtn = document.getElementById('selectAllPermissions');
    // Deselect All button
    const deselectAllBtn = document.getElementById('deselectAllPermissions');

    // Get all permission checkboxes
    function getPermissionCheckboxes() {
        return document.querySelectorAll('.permission-checkbox');
    }

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function (e) {
            e.preventDefault();
            getPermissionCheckboxes().forEach(cb => cb.checked = true);
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function (e) {
            e.preventDefault();
            getPermissionCheckboxes().forEach(cb => cb.checked = false);
        });
    }
});





