<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\ProjectsController::class, 'landing']);



// HOME
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// CLIENTS
Route::get('clients', [App\Http\Controllers\HomeController::class, 'clients'])->name('clients');
Route::get('new-client', [App\Http\Controllers\HomeController::class, 'newClient'])->name('new-client');
Route::post('new-client', [App\Http\Controllers\HomeController::class, 'saveClient'])->name('saveClient');
Route::post('saveClient/{cid}', [App\Http\Controllers\HomeController::class, 'updateClient'])->name('update.client');
Route::get('edit-client/{cid}', [App\Http\Controllers\HomeController::class, 'editClient'])->name('edit-client');

// PERSONNELS
Route::get('personnel', [App\Http\Controllers\PersonnelController::class, 'index'])->name('personnel');
Route::get('new-personnel', [App\Http\Controllers\PersonnelController::class, 'newPersonnel'])->name('new.personnel');
Route::get('personnel/staffs', [App\Http\Controllers\PersonnelController::class, 'allStaffs'])->name('staffs');
Route::get('personnel/workers', [App\Http\Controllers\PersonnelController::class, 'allWorkers'])->name('workers');
Route::get('personnel/contractors', [App\Http\Controllers\PersonnelController::class, 'allContractors'])->name('contractors');
Route::get('personnel/{id}', [App\Http\Controllers\PersonnelController::class, 'showPersonnel'])->name('show.personnel');
Route::post('new-personnel', [App\Http\Controllers\PersonnelController::class, 'createPersonnel'])->name('create.personnel');
Route::get('edit-personnel/{id}', [App\Http\Controllers\PersonnelController::class, 'editPersonnel'])->name('edit.personnel');
Route::put('personnel/{id}', [App\Http\Controllers\PersonnelController::class, 'updatePersonnel'])->name('update.personnel');
Route::post('personnel/{id}', [App\Http\Controllers\PersonnelController::class, 'deletePersonnel'])->name('delete.personnel');

// OWNERS
Route::get('owners', [App\Http\Controllers\OwnerController::class, 'index'])->name('owners');
Route::get('new-owner', [App\Http\Controllers\OwnerController::class, 'newOwner'])->name('new.owner');
Route::post('create-owner', [App\Http\Controllers\OwnerController::class, 'createOwner'])->name('create.owner');
Route::get('edit-owner/{id}', [App\Http\Controllers\OwnerController::class, 'editOwner'])->name('edit.owner');
Route::put('update-owner/{id}', [App\Http\Controllers\OwnerController::class, 'updateOwner'])->name('update.owner');
Route::get('owner/{id}', [App\Http\Controllers\OwnerController::class, 'showOwner'])->name('show.owner');
Route::post('delete-owner/{id}', [App\Http\Controllers\OwnerController::class, 'deleteOwner'])->name('delete.owner');

// TENANTS
Route::get('tenants', [App\Http\Controllers\TenantController::class, 'index'])->name('tenants');
Route::get('new-tenant', [App\Http\Controllers\TenantController::class, 'newTenant'])->name('new.tenant');
Route::post('create-tenant', [App\Http\Controllers\TenantController::class, 'createTenant'])->name('create.tenant');
Route::get('edit-tenant/{id}', [App\Http\Controllers\TenantController::class, 'editTenant'])->name('edit.tenant');
Route::put('update-tenant/{id}', [App\Http\Controllers\TenantController::class, 'updateTenant'])->name('update.tenant');
Route::get('tenant/{id}', [App\Http\Controllers\TenantController::class, 'showTenant'])->name('show.tenant');
Route::post('delete-tenant/{id}', [App\Http\Controllers\TenantController::class, 'deleteTenant'])->name('delete.tenant');

// AGENTS
Route::get('agents', [App\Http\Controllers\AgentController::class, 'index'])->name('agents');
Route::get('new-agent', [App\Http\Controllers\AgentController::class, 'newAgent'])->name('new.agent');
Route::post('create-agent', [App\Http\Controllers\AgentController::class, 'createAgent'])->name('create.agent');
Route::get('edit-agent/{id}', [App\Http\Controllers\AgentController::class, 'editAgent'])->name('edit.agent');
Route::put('update-agent/{id}', [App\Http\Controllers\AgentController::class, 'updateAgent'])->name('update.agent');
Route::get('agent/{id}', [App\Http\Controllers\AgentController::class, 'showAgent'])->name('show.agent');
Route::post('delete-agent/{id}', [App\Http\Controllers\AgentController::class, 'deleteAgent'])->name('delete.agent');

// PROPERTIES
Route::get('properties', [App\Http\Controllers\PropertyController::class, 'index'])->name('properties');
Route::get('properties/create', [App\Http\Controllers\PropertyController::class, 'newProperty'])->name('new.property');
Route::post('properties', [App\Http\Controllers\PropertyController::class, 'createProperty'])->name('create.property');
Route::get('properties/{id}/edit', [App\Http\Controllers\PropertyController::class, 'editProperty'])->name('edit.property');
Route::get('properties/{id}', [App\Http\Controllers\PropertyController::class, 'showProperty'])->name('show.property');
Route::put('properties/{id}', [App\Http\Controllers\PropertyController::class, 'updateProperty'])->name('update.property');
Route::get('properties/{propertyId}/units', [App\Http\Controllers\PropertyController::class, 'propertyUnit'])->name('property.units');
Route::post('properties/delete-property/{id}', [App\Http\Controllers\PropertyController::class, 'deleteProperty'])->name('delete.property');
// OWNERS PROPERTY
Route::get('owner/{id}/properties', [App\Http\Controllers\PropertyController::class, 'ownerProperty'])->name('owner.property');
Route::get('owner/{id}/new-property', [App\Http\Controllers\PropertyController::class, 'AddOwnerProperty'])->name('add.owner.property');
// HANDLE PROPERTY IMAGES
Route::post('/properties/{id}/upload-image', [App\Http\Controllers\PropertyController::class, 'uploadPropertyImage'])->name('property.uploadImage');
Route::post('/properties/{id}/featured-image/{image}', [App\Http\Controllers\PropertyController::class, 'setFeaturedImage'])->name('property.setFeaturedImage');
Route::delete('/properties/images/{propertyImage}', [App\Http\Controllers\PropertyController::class, 'deleteImage'])->name('property.deleteImage');

// PROPERTY UNITS, LEASES, VIEWINGS, TASKS, MAINTENANCE REQUEST, DOCUMENTS,

// UNITS
Route::prefix('properties')->group(function () {
    Route::get('/units/', [App\Http\Controllers\UnitController::class, 'newUnit'])->name('new.units');
    Route::get('/{propertyId}/units/create', [App\Http\Controllers\UnitController::class, 'newUnit'])->name('new.unit');
    Route::post('/units', [App\Http\Controllers\UnitController::class, 'createUnit'])->name('create.unit');
    Route::get('/units/{id}', [App\Http\Controllers\UnitController::class, 'showUnit'])->name('show.unit');
    Route::get('/units/{id}/edit', [App\Http\Controllers\UnitController::class, 'editUnit'])->name('edit.unit');
    Route::put('/units/{id}', [App\Http\Controllers\UnitController::class, 'updateUnit'])->name('update.unit');
    Route::post('/delete-unit/{id}', [App\Http\Controllers\UnitController::class, 'deleteUnit'])->name('delete.unit');
    // HANDLE UNIT IMAGES
    Route::post('/units/{id}/upload-image', [App\Http\Controllers\UnitController::class, 'uploadUnitImage'])->name('unit.uploadImage');
    Route::post('/units/{id}/featured-image/{image}', [App\Http\Controllers\UnitController::class, 'setFeaturedImage'])->name('unit.setFeaturedImage');
    Route::delete('/units/images/{unitImage}', [App\Http\Controllers\UnitController::class, 'deleteImage'])->name('unit.deleteImage');
});
// LEASES
Route::get('/property/leases', [App\Http\Controllers\LeaseController::class, 'index'])->name('leases');
Route::get('properties/leases/create', [App\Http\Controllers\LeaseController::class, 'newLease'])->name('new.lease');
Route::post('properties/leases', [App\Http\Controllers\LeaseController::class, 'createLease'])->name('create.lease');
Route::get('properties/{id}/leases', [App\Http\Controllers\LeaseController::class, 'propertyLease'])->name('property.leases');
Route::put('properties/leases/{id}', [App\Http\Controllers\LeaseController::class, 'updateLease'])->name('update.lease');
Route::delete('properties/leases/{id}', [App\Http\Controllers\LeaseController::class, 'deleteLease'])->name('delete.lease');
// AJAX route for dynamic unit dropdown in lease forms
Route::get('/get-units-by-property', [App\Http\Controllers\LeaseController::class, 'getUnitsByProperty'])->name('get.units.by.property');

// VIEWINGS
Route::get('properties/{id}/viewings', [App\Http\Controllers\ViewingController::class, 'propertyViewing'])->name('property.viewing');
Route::get('units/{id}/viewings', [App\Http\Controllers\ViewingController::class, 'unitViewing'])->name('unit.viewing');
Route::post('/viewings/{redir_to}', [App\Http\Controllers\ViewingController::class, 'createViewing'])->name('create.viewing'); // 'redir_to' redirect location 
Route::put('/viewings/{id}/{redir_to}', [App\Http\Controllers\ViewingController::class, 'updateViewing'])->name('update.viewing'); // 'redir_to' redirect location 
Route::delete('/viewings/{id}/{redir_to}', [App\Http\Controllers\ViewingController::class, 'deleteViewing'])->name('delete.viewing');
// TASKS
Route::get('properties/{id}/tasks', [App\Http\Controllers\PropertyTaskController::class, 'propertyTask'])->name('property.tasks');
Route::post('properties/tasks', [App\Http\Controllers\PropertyTaskController::class, 'createTask'])->name('create.task');
Route::put('properties/tasks/{id}', [App\Http\Controllers\PropertyTaskController::class, 'updateTask'])->name('update.task');
Route::delete('properties/tasks/{id}', [App\Http\Controllers\PropertyTaskController::class, 'deleteTask'])->name('delete.task');
// MAINTENANCE REQUESTS
Route::get('properties/{id}/maintenance-requests', [App\Http\Controllers\MaintenanceRequestController::class, 'propertyMaintenanceRequest'])->name('property.maintenanceRequest');
Route::get('units/{id}/maintenance-requests', [App\Http\Controllers\MaintenanceRequestController::class, 'unitMaintenanceRequest'])->name('unit.maintenanceRequest');
Route::post('maintenance-requests/{redir_to}', [App\Http\Controllers\MaintenanceRequestController::class, 'createMaintenanceRequest'])->name('create.maintenanceRequest');
Route::put('maintenance-requests/{id}/{redir_to}', [App\Http\Controllers\MaintenanceRequestController::class, 'updateMaintenanceRequest'])->name('update.maintenanceRequest');
Route::delete('maintenance-requests/{id}/{redir_to}', [App\Http\Controllers\MaintenanceRequestController::class, 'deleteMaintenanceRequest'])->name('delete.maintenanceRequest');
// DOCUMENTS
Route::get('properties/{id}/documents', [App\Http\Controllers\DocumentController::class, 'propertyDocument'])->name('property.document');

// ROLES & PERMISSIONS
Route::resource('role', App\Http\Controllers\RoleController::class);
Route::get('/roles', [App\Http\Controllers\UserPermissionController::class, 'index'])->name('users.role');
Route::get('/roles/{user}/edit', [App\Http\Controllers\UserPermissionController::class, 'edit'])->name('user.role.edit');
Route::post('/roles/{user}/update', [App\Http\Controllers\UserPermissionController::class, 'update'])->name('user.role.update');

// PROJECTS
Route::get('projects', [App\Http\Controllers\ProjectsController::class, 'index'])->name('projects');
Route::get('client-projects/{cid}', [App\Http\Controllers\ProjectsController::class, 'clientProjects'])->name('client-projects');
Route::get('/new-project/{cid}', [App\Http\Controllers\ProjectsController::class, 'create'])->name('new-project');
Route::get('/edit-project/{pid}', [App\Http\Controllers\ProjectsController::class, 'editProject'])->name('edit-project');
Route::get('/addproject', [App\Http\Controllers\ProjectsController::class, 'newProject'])->name('addproject');
Route::post('save-project', [App\Http\Controllers\ProjectsController::class, 'store'])->name('save-project');
Route::get('/project-dashboard/{pid}', [App\Http\Controllers\ProjectsController::class, 'projectDashboard'])->name('project-dashboard');
// Related to PROJECTS
Route::get('/project/{pid}/transactions', [App\Http\Controllers\ProjectsController::class, 'projectTransactions'])->name('project-transactions');
Route::get('/project/{pid}/materials', [App\Http\Controllers\ProjectsController::class, 'projectMaterials'])->name('project-materials');
Route::get('/project/{pid}/workers', [App\Http\Controllers\ProjectsController::class, 'projectWorkers'])->name('project-workers');
Route::get('/project/{pid}/tasks', [App\Http\Controllers\ProjectsController::class, 'projectTasks'])->name('project-tasks');
Route::get('/project/{pid}/reports', [App\Http\Controllers\ProjectsController::class, 'projectReports'])->name('project-reports');
Route::get('/project/{pid}/milestones', [App\Http\Controllers\ProjectsController::class, 'projectMilestones'])->name('project-milestones');

// Route::get('/project-milestone/{cid}', [App\Http\Controllers\ProjectsController::class, 'create'])->name('project-milestone');
// Route::get('/project-task/{cid}', [App\Http\Controllers\ProjectsController::class, 'create'])->name('project-task');


// MILESTONES
Route::get('milestones', [App\Http\Controllers\ProjectMilestonesController::class, 'index'])->name('milestones');
Route::get('new-milestone/{pid}', [App\Http\Controllers\ProjectMilestonesController::class, 'create'])->name('new-milestone');
Route::post('savemilestone', [App\Http\Controllers\ProjectMilestonesController::class, 'saveMilestone'])->name('savemilestone');
Route::get('milestone/{mid}', [App\Http\Controllers\ProjectMilestonesController::class, 'milestone'])->name('milestone');
Route::get('milestone-task/{mid}', [App\Http\Controllers\ProjectMilestonesController::class, 'milestoneTask'])->name('milestone-task');
Route::post('savemilestoneTask', [App\Http\Controllers\ProjectMilestonesController::class, 'saveMilestoneTask'])->name('savemilestoneTask');


//TASK
Route::get('tasks', [App\Http\Controllers\TasksController::class, 'index'])->name('tasks');
Route::get('project-task/{tid}', [App\Http\Controllers\TasksController::class, 'create'])->name('project-task');
Route::get('task/{tid}', [App\Http\Controllers\TasksController::class, 'viewTask'])->name('task');
Route::post('addWorkers', [App\Http\Controllers\TasksController::class, 'addWorkers'])->name('addWorkers');
Route::post('addMaterialsUsed', [App\Http\Controllers\TasksController::class, 'addMaterialsUsed'])->name('addMaterialsUsed');
Route::post('change_task_status', [App\Http\Controllers\TasksController::class, 'change_task_status'])->name('change_task_status');
Route::get('del-task/{tid}', [App\Http\Controllers\TasksController::class, 'destroy'])->name('del-task');
Route::get('new-task', [App\Http\Controllers\TasksController::class, 'newTask'])->name('new-task');
Route::post('saveTask', [App\Http\Controllers\TasksController::class, 'saveTask'])->name('saveTask');
Route::get('/completetask/{id}', [App\Http\Controllers\TasksController::class, 'completetask'])->name('completetask');
Route::get('/inprogresstask/{id}', [App\Http\Controllers\TasksController::class, 'inprogresstask'])->name('inprogresstask');


//REPORT
Route::get('milestone-report/{tid}', [App\Http\Controllers\MilestoneReportsController::class, 'create'])->name('milestone-report');

Route::get('new-task-report/{tid}', [App\Http\Controllers\MilestoneReportsController::class, 'newtaskReport'])->name('new-task-report');
Route::post('addtaskreport', [App\Http\Controllers\MilestoneReportsController::class, 'store'])->name('addtaskreport');
Route::get('task-report/{trid}', [App\Http\Controllers\MilestoneReportsController::class, 'milestonetaskReport'])->name('task-report');
Route::post('change_task_report_status', [App\Http\Controllers\MilestoneReportsController::class, 'change_task_report_status'])->name('change_task_report_status');

//REPORT
Route::get('/add-file', [App\Http\Controllers\ProjectFilesController::class, 'create'])->name('add-file');
Route::get('/addp-file/{pid}', [App\Http\Controllers\ProjectFilesController::class, 'addProjectFile'])->name('addp-file');

Route::post('save-file', [App\Http\Controllers\ProjectFilesController::class, 'store'])->name('save-file');
Route::get('/file/{fid}', [App\Http\Controllers\ProjectFilesController::class, 'file'])->name('file');

// MATERIALS
Route::get('/materials', [App\Http\Controllers\MaterialsController::class, 'index'])->name('materials');
Route::post('/addmaterial', [App\Http\Controllers\MaterialsController::class, 'store'])->name('addmaterial');
Route::get('/material/{id}', [App\Http\Controllers\MaterialsController::class, 'material'])->name('material');
Route::get('/delete-mat/{id}', [App\Http\Controllers\MaterialsController::class, 'destroy'])->name('delete-mat');

// SUPPLIERS
Route::get('/suppliers', [App\Http\Controllers\SuppliersController::class, 'index'])->name('suppliers');
Route::post('/addsupplier', [App\Http\Controllers\SuppliersController::class, 'store'])->name('addsupplier');
Route::get('/supplier/{id}', [App\Http\Controllers\SuppliersController::class, 'supplier'])->name('supplier');
Route::get('/delete-sup/{id}', [App\Http\Controllers\SuppliersController::class, 'destroy'])->name('delete-sup');

// SUPPLIES
Route::get('/supplies', [App\Http\Controllers\MaterialSuppliesController::class, 'index'])->name('supplies');
Route::post('/addsupply', [App\Http\Controllers\MaterialSuppliesController::class, 'store'])->name('addsupply');
Route::get('/supply/{id}', [App\Http\Controllers\MaterialSuppliesController::class, 'supply'])->name('supply');
Route::get('/delete-sp/{id}', [App\Http\Controllers\MaterialSuppliesController::class, 'destroy'])->name('delete-sp');


// MATERIAL CHECKOUTS
Route::get('/mcheckouts', [App\Http\Controllers\MaterialCheckoutsController::class, 'index'])->name('mcheckouts');
Route::post('/addmcheckout', [App\Http\Controllers\MaterialCheckoutsController::class, 'store'])->name('addmcheckout');
Route::get('/delete-mtc/{id}/{mid}/{qty}', [App\Http\Controllers\MaterialCheckoutsController::class, 'destroy'])->name('delete-mtc');
Route::post('addMaterialsUsed', [App\Http\Controllers\MaterialCheckoutsController::class, 'addMaterialsUsed'])->name('addMaterialsUsed');

// ACCOUNT HEADS
Route::get('/account-heads', [App\Http\Controllers\AccountheadsController::class, 'index'])->name('account-heads');
Route::post('/addaccounthead', [App\Http\Controllers\AccountheadsController::class, 'store'])->name('addaccounthead');
Route::get('/delete-acch/{id}', [App\Http\Controllers\AccountheadsController::class, 'destroy'])->name('delete-acch');

// ACCOUNT HEADS
Route::get('/categories', [App\Http\Controllers\CategoriesController::class, 'index'])->name('categories');
Route::post('/addcategory', [App\Http\Controllers\CategoriesController::class, 'store'])->name('addcategory');
Route::get('/delete-cat/{id}', [App\Http\Controllers\CategoriesController::class, 'destroy'])->name('delete-cat');

// TRANSACTIONS
Route::get('/transactions', [App\Http\Controllers\TransactionsController::class, 'index'])->name('transactions');
Route::post('/addtransaction', [App\Http\Controllers\TransactionsController::class, 'store'])->name('addtransaction');
Route::get('/delete-trans/{id}', [App\Http\Controllers\TransactionsController::class, 'delTrans'])->name('delete-trans');

Route::post('/settings', [App\Http\Controllers\HomeController::class, 'settings'])->name('settings');

//LOGOUT
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class,'logout']);

Auth::routes();
