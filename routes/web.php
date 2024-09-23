    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\API\PresensiController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\API\IzinController;
    use App\Http\Controllers\ReportController;

    // Halaman utama
    Route::get('/', function () {
        return view('welcome');
    });

    // Rute autentikasi
    Auth::routes();

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Halaman registrasi
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Rute admin dengan middleware 'auth' dan 'role:admin'
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Dashboard admin
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Rute untuk menambah pengguna
        Route::get('/admin/add-user', [AdminController::class, 'showAddUserForm'])->name('admin.add-user-form');
        Route::post('/admin/add-user', [AdminController::class, 'addUser'])->name('admin.add-user');

        // Rute untuk mengelola pengguna
        Route::get('/admin/manage-users', [AdminController::class, 'manageUsers'])->name('admin.manage-users');
        Route::get('/admin/edit-user/{id}', [AdminController::class, 'editUser'])->name('admin.edit-user');
        Route::put('/admin/update-user/{id}', [UserController::class, 'updateUser'])->name('admin.update-user');
        Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');

        // Rute untuk mengelola presensi
        Route::get('/admin/manage-presensi', [AdminController::class, 'managePresensi'])->name('admin.manage-presensi');
        Route::get('/admin/edit-presensi/{id}', [AdminController::class, 'editPresensi'])->name('admin.edit-presensi');
        Route::put('/admin/update-presensi/{id}', [AdminController::class, 'updatePresensi'])->name('admin.update-presensi');
        Route::delete('/admin/delete-presensi/{id}', [AdminController::class, 'deletePresensi'])->name('admin.delete-presensi');
        
        // Rute untuk laporan absensi bulanan
        Route::get('/admin/monthly-report', [ReportController::class, 'monthlyReport'])->name('admin.monthly-report');

    // Rute untuk mengelola izin
    Route::middleware('auth:api')->post('/ajukan-izin', [IzinController::class, 'store']);
    Route::get('/admin/manage-izin', [IzinController::class, 'index'])->name('admin.manage-izin');
    Route::post('/admin/approve-izin/{id}', [IzinController::class, 'approve'])->name('admin.approve-izin');
    Route::post('/admin/reject-izin/{id}', [IzinController::class, 'reject'])->name('admin.reject-izin');

    });

    // Rute API untuk menyimpan presensi
    Route::middleware('auth:api')->post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');

    // Rute untuk rekap absen khusus untuk pengguna yang terautentikasi
    Route::middleware(['auth', 'role:user'])->group(function () {
        Route::get('/rekap-absen', [UserController::class, 'rekapAbsen'])->name('rekap-absen');
    });
