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



// function selectAllPermissions(checked) {
//     document.querySelectorAll('.permission-checkbox').forEach(function(cb) {
//         cb.checked = checked;
//     });
//     // Uncheck the other control to avoid confusion
//     if(checked) {
//         document.getElementById('deselectAllPermissions').checked = false;
//     } else {
//         document.getElementById('selectAllPermissions').checked = false;
//     }
// }

// document.addEventListener('DOMContentLoaded', function() {
//     var selectAll = document.getElementById('selectAllPermissions');
//     var deselectAll = document.getElementById('deselectAllPermissions');
//     if(selectAll) {
//         selectAll.addEventListener('change', function() {
//             if(this.checked) {
//                 selectAllPermissions(true);
//             }
//         });
//     }
//     if(deselectAll) {
//         deselectAll.addEventListener('change', function() {
//             if(this.checked) {
//                 selectAllPermissions(false);
//             }
//         });
//     }
// });



