<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserAssetController;
use App\Http\Controllers\VideoMediaPembelajaranController;
use App\Http\Controllers\MateriBelajarController;
use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginSettingController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('welcome');
});

Route::post('/register', function () {
    // Registration logic will be implemented here
    return redirect('/login');
});

Route::get('/password-reset', function () {
    return view('welcome');
});

// Dashboard Routes - Protected by auth middleware
Route::middleware('auth')->group(function () {
    Route::get('/user-dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/marketing-dashboard', [DashboardController::class, 'marketingDashboard'])->name('marketing.dashboard');
    Route::get('/admin-dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // Redirect generic dashboard to role-specific dashboard
    Route::get('/dashboard', fn () => match(Auth::user()->role) {
        'admin' => redirect('/admin-dashboard'),
        'marketing' => redirect('/marketing-dashboard'),
        default => redirect('/user-dashboard'),
    });

    // API Routes
    Route::get('/api/notifications', [NotificationController::class, 'getDashboardNotifications'])->name('api.notifications');

    // Profile Routes
    Route::get('/profile-detail', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // User Asset Routes (All Authenticated Users)
    Route::get('listing-aset', [UserAssetController::class, 'listing'])->name('user.assets.listing');
    Route::get('listing-aset/{asset}/download', [UserAssetController::class, 'downloadPhotos'])->name('user.assets.download-photos');
    Route::get('listing-aset/{asset}/download/{photoIndex}', [UserAssetController::class, 'downloadSinglePhoto'])->name('user.assets.download-single');
    Route::get('api/aset/{asset}/broadcast', [UserAssetController::class, 'getBroadcastText'])->name('user.assets.broadcast-api');

    // Marketing Routes (Marketing Only)
    Route::middleware('role:marketing')->group(function () {
        Route::get('marketing/dashboard', [MarketingController::class, 'dashboard'])->name('marketing.dashboard');
        Route::get('marketing/assets', [MarketingController::class, 'assets'])->name('marketing.assets');
        Route::get('marketing/assets/{asset}/download', [MarketingController::class, 'downloadPhotos'])->name('marketing.download-photos');
        Route::get('marketing/learning', [MarketingController::class, 'learning'])->name('marketing.learning');
        Route::get('marketing/materi', [MarketingController::class, 'materi'])->name('marketing.materi');
        Route::get('marketing/informasi', [MarketingController::class, 'informasi'])->name('marketing.informasi');
        Route::get('marketing/pengaturan', [ProfileController::class, 'edit'])->name('marketing.pengaturan');
        Route::get('marketing/notifications', [MarketingController::class, 'notifications'])->name('marketing.notifications');
        Route::post('marketing/notifications/{notification}/read', [MarketingController::class, 'markNotificationAsRead'])->name('marketing.notification-read');
        Route::delete('marketing/notifications/{notification}', [MarketingController::class, 'deleteNotification'])->name('marketing.notification-delete');
    });

    // Asset Management Routes (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('assets', AssetController::class);
        Route::patch('assets/{asset}/status', [AssetController::class, 'updateStatus'])->name('assets.updateStatus');

        // Informasi Management Routes
        Route::resource('informasi', InformasiController::class);
        Route::post('informasi-categories', [InformasiController::class, 'storeCategory'])->name('informasi.storeCategory');
        Route::delete('informasi-categories/{id}', [InformasiController::class, 'destroyCategory'])->name('informasi.destroyCategory');

        // Content Management Routes
        Route::resource('manage-video', VideoMediaPembelajaranController::class)->parameters(['manage-video' => 'content'])->names('contents');
        Route::patch('manage-video/{content}/publish', [VideoMediaPembelajaranController::class, 'togglePublish'])->name('contents.togglePublish');
        Route::delete('manage-video/{content}/thumbnail', [VideoMediaPembelajaranController::class, 'deleteThumbnail'])->name('contents.deleteThumbnail');

        // Materi Management Routes
        Route::resource('materi', MateriBelajarController::class)->parameters(['materi' => 'material']);
        Route::patch('materi/{material}/publish', [MateriBelajarController::class, 'togglePublish'])->name('materi.togglePublish');
        Route::delete('materi/{material}/thumbnail', [MateriBelajarController::class, 'deleteThumbnail'])->name('materi.deleteThumbnail');

        // Material Category Store Route (for modal)
        Route::post('categories', [MaterialCategoryController::class, 'store'])->name('categories.store');

        // Notification Management Routes
        Route::resource('notifications', NotificationController::class);
        Route::post('notifications/bulk-send', [NotificationController::class, 'sendBulk'])->name('notifications.sendBulk');
        Route::patch('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

        // User Management Routes
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // Login Page Settings
        Route::get('admin/login-settings', [LoginSettingController::class, 'edit'])->name('admin.login-settings.edit');
        Route::patch('admin/login-settings', [LoginSettingController::class, 'update'])->name('admin.login-settings.update');
    });
});
