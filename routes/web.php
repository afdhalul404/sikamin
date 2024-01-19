<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalSidangController;
use App\Models\JadwalSidang;
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

/** for side bar menu active */
function set_active( $route ) {
    if( is_array( $route ) ){
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('home');
    });
    Route::get('home',function()
    {
        return view('home');
    });
});

Auth::routes();
Route::group(['namespace' => 'App\Http\Controllers\Auth'],function()
{
    // ----------------------------login ------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
        Route::post('change/password', 'changePassword')->name('change/password');
    });

    // ----------------------------- register -------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register','storeUser')->name('register');    
    });
});

Route::group(['namespace' => 'App\Http\Controllers'],function()
{
    // -------------------------- main dashboard ----------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->middleware('auth')->name('home');
        Route::get('user/profile/page', 'userProfile')->middleware('auth')->name('user/profile/page');
        Route::get('teacher/dashboard', 'teacherDashboardIndex')->middleware('auth')->name('teacher/dashboard');
        Route::get('student/dashboard', 'studentDashboardIndex')->middleware('auth')->name('student/dashboard');
        Route::get('superadmin/dashboard', 'superadminDashboardIndex')->middleware('auth')->name('superadmin/dashboard');
        Route::get('staff/dashboard', 'staffDashboardIndex')->middleware('auth')->name('staff/dashboard');
    });

    // ----------------------------- user controller ---------------------//
    Route::controller(UserManagementController::class)->group(function () {
        Route::get('list/users', 'index')->middleware('auth')->name('list/users');
        Route::post('change/password', 'changePassword')->name('change/password');
        Route::get('view/user/edit/{id}', 'userView')->middleware('auth');
        Route::post('user/update', 'userUpdate')->name('user/update');
        Route::post('user/delete', 'userDelete')->name('user/delete');
        Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */

    });

    // ------------------------ setting -------------------------------//
    Route::controller(Setting::class)->group(function () {
        Route::get('setting/page', 'index')->middleware('auth')->name('setting/page');
    });

    // ------------------------ student -------------------------------//
    Route::controller(StudentController::class)->group(function () {
        Route::get('student/list', 'student')->middleware('auth')->name('student/list'); // list student
        Route::get('student/grid', 'studentGrid')->middleware('auth')->name('student/grid'); // grid student
        Route::get('student/add/page', 'studentAdd')->middleware('auth')->name('student/add/page'); // page student
        Route::post('student/add/save', 'studentSave')->name('student/add/save'); // save record student
        Route::get('student/edit/{id}', 'studentEdit'); // view for edit
        Route::post('student/update', 'studentUpdate')->name('student/update'); // update record student
        Route::post('student/delete', 'studentDelete')->name('student/delete'); // delete record student
        Route::get('student/profile/{id}', 'studentProfile')->middleware('auth'); // profile student

        Route::get('student/jadwalsidang', 'studentJadwalSidang')->middleware('auth')->name('student/jadwalsidang'); // jadwalsidang student
    });

     // ------------------------ Jadwal Sidang student -------------------------------//
     Route::controller(JadwalSidangController::class)->group(function () {
        // Mahasiswa
        Route::get('student/jadwalsidang', 'studentJadwalSidang')->middleware('auth')->name('student/jadwalsidang'); // jadwalsidang student
        Route::get('student/jadwalsidangproposal', 'jadwalSidangProposal')->middleware('auth')->name('student/jadwalsidangproposal'); // jadwalsidang proposal
        Route::get('student/jadwalsidanghasil', 'jadwalSidangHasil')->middleware('auth')->name('student/jadwalsidanghasil'); // jadwalsidang hasil
        Route::get('student/jadwalsidangskripsi', 'jadwalSidangSkripsi')->middleware('auth')->name('student/jadwalsidangskripsi'); // jadwalsidang skripsi
        Route::get('student/addjadwalsidang', 'studentaddJadwalSidang')->name('student/addJadwalSidang'); // add Jadwal Sidang record student
        Route::post('student/savejadwalsidang', 'studentsaveJadwalSidang')->name('student/saveJadwalSidang'); // save Jadwal Sidang record student

        Route::post('student/jadwalsidangproposal', 'simpanJadwalProposal')->middleware('auth')->name('student/simpanjadwalsidangproposal'); // simpan jadwalsidang proposal
        Route::post('student/jadwalsidanghasil', 'simpanJadwalHasil')->middleware('auth')->name('student/simpanjadwalsidanghasil'); // simpan jadwalsidang hasil
        Route::post('student/jadwalsidangskripsi', 'simpanJadwalSkripsi')->middleware('auth')->name('student/simpanjadwalsidangskripsi'); // simpan jadwalsidang skripsi

        // Dosen
        Route::get('dosen/jadwalsidangmahasiswa', 'jadwalSidangMahasiswa')->middleware('auth')->name('dosen/jadwalsidangmahasiswa'); // jadwalsidang proposal Mahasiswa
    });

    // ------------------------ Dosen -------------------------------//
    Route::controller(DosenController::class)->group(function () {
        Route::get('dosen/add/page', 'dosenAdd')->middleware('auth')->name('dosen/add/page'); // page dosen
        Route::get('dosen/list/page', 'dosenList')->middleware('auth')->name('dosen/list/page'); // page dosen
        Route::get('dosen/grid/page', 'dosenGrid')->middleware('auth')->name('dosen/grid/page'); // page grid dosen
        Route::post('dosen/save', 'saveRecord')->middleware('auth')->name('dosen/save'); // save record
        Route::get('dosen/edit/{user_id}', 'editRecord'); // view dosen record
        Route::post('dosen/update', 'updateRecorddosen')->middleware('auth')->name('dosen/update'); // update record
        Route::post('dosen/delete', 'dosenDelete')->name('dosen/delete'); // delete record dosen
    });

    // ------------------------ teacher -------------------------------//
    Route::controller(TeacherController::class)->group(function () {
        Route::get('teacher/add/page', 'teacherAdd')->middleware('auth')->name('teacher/add/page'); // page teacher
        Route::get('teacher/list/page', 'teacherList')->middleware('auth')->name('teacher/list/page'); // page teacher
        Route::get('teacher/grid/page', 'teacherGrid')->middleware('auth')->name('teacher/grid/page'); // page grid teacher
        Route::post('teacher/save', 'saveRecord')->middleware('auth')->name('teacher/save'); // save record
        Route::get('teacher/edit/{user_id}', 'editRecord'); // view teacher record
        Route::post('teacher/update', 'updateRecordTeacher')->middleware('auth')->name('teacher/update'); // update record
        Route::post('teacher/delete', 'teacherDelete')->name('teacher/delete'); // delete record teacher
    });

    // ----------------------- department -----------------------------//
    Route::controller(DepartmentController::class)->group(function () {
        Route::get('department/list/page', 'departmentList')->middleware('auth')->name('department/list/page'); // department/list/page
        Route::get('department/add/page', 'indexDepartment')->middleware('auth')->name('department/add/page'); // page add department
        Route::get('department/edit/{department_id}', 'editDepartment'); // page add department
        Route::post('department/save', 'saveRecord')->middleware('auth')->name('department/save'); // department/save
        Route::post('department/update', 'updateRecord')->middleware('auth')->name('department/update'); // department/update
        Route::post('department/delete', 'deleteRecord')->middleware('auth')->name('department/delete'); // department/delete
        Route::get('get-data-list', 'getDataList')->name('get-data-list'); // get data list

    });

    // ----------------------- subject -----------------------------//
    Route::controller(SubjectController::class)->group(function () {
        Route::get('subject/list/page', 'subjectList')->middleware('auth')->name('subject/list/page'); // subject/list/page
        Route::get('subject/add/page', 'subjectAdd')->middleware('auth')->name('subject/add/page'); // subject/add/page
        Route::post('subject/save', 'saveRecord')->name('subject/save'); // subject/save
        Route::post('subject/update', 'updateRecord')->name('subject/update'); // subject/update
        Route::post('subject/delete', 'deleteRecord')->name('subject/delete'); // subject/delete
        Route::get('subject/edit/{subject_id}', 'subjectEdit'); // subject/edit/page
    });

    // ----------------------- invoice -----------------------------//
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('invoice/list/page', 'invoiceList')->middleware('auth')->name('invoice/list/page'); // subjeinvoicect/list/page
        Route::get('invoice/paid/page', 'invoicePaid')->middleware('auth')->name('invoice/paid/page'); // invoice/paid/page
        Route::get('invoice/overdue/page', 'invoiceOverdue')->middleware('auth')->name('invoice/overdue/page'); // invoice/overdue/page
        Route::get('invoice/draft/page', 'invoiceDraft')->middleware('auth')->name('invoice/draft/page'); // invoice/draft/page
        Route::get('invoice/recurring/page', 'invoiceRecurring')->middleware('auth')->name('invoice/recurring/page'); // invoice/recurring/page
        Route::get('invoice/cancelled/page', 'invoiceCancelled')->middleware('auth')->name('invoice/cancelled/page'); // invoice/cancelled/page
        Route::get('invoice/grid/page', 'invoiceGrid')->middleware('auth')->name('invoice/grid/page'); // invoice/grid/page
        Route::get('invoice/add/page', 'invoiceAdd')->middleware('auth')->name('invoice/add/page'); // invoice/add/page
        Route::get('invoice/edit/page', 'invoiceEdit')->middleware('auth')->name('invoice/edit/page'); // invoice/edit/page
        Route::get('invoice/view/page', 'invoiceView')->middleware('auth')->name('invoice/view/page'); // invoice/view/page
        Route::get('invoice/settings/page', 'invoiceSettings')->middleware('auth')->name('invoice/settings/page'); // invoice/settings/page
        Route::get('invoice/settings/tax/page', 'invoiceSettingsTax')->middleware('auth')->name('invoice/settings/tax/page'); // invoice/settings/tax/page
        Route::get('invoice/settings/bank/page', 'invoiceSettingsBank')->middleware('auth')->name('invoice/settings/bank/page'); // invoice/settings/bank/page
    });

    // ----------------------- accounts ----------------------------//
    Route::controller(AccountsController::class)->group(function () {
        Route::get('account/fees/collections/page', 'index')->middleware('auth')->name('account/fees/collections/page'); // account/fees/collections/page
    });
});