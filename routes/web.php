<?php

use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RequestController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ------------------------
// メール認証関連ルート
// ------------------------
Route::middleware(['auth'])->group(function () {
    // 認証リンククリック後の処理
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/attendance');
    })->middleware(['signed'])->name('verification.verify');

    // 認証リクエスト再送
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証リンクを再送しました！');
    })->name('verification.send');
});

// ------------------------
// 一般ユーザー用（ログイン & メール認証済）
// ------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/start', [AttendanceController::class, 'start'])->name('attendance.start');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
    Route::post('/attendance/break-start', [AttendanceController::class, 'startBreak'])->name('attendance.break.start');
    Route::post('/attendance/break-end', [AttendanceController::class, 'endBreak'])->name('attendance.break.end');
    Route::get('/attendances', [AttendanceController::class, 'monthly'])->name('attendance.monthly');
    Route::get('/attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::post('/attendances/{attendance}/request-edit', [AttendanceController::class, 'requestEdit'])->name('attendance.request.edit');
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [RequestController::class, 'index'])->name('index');
        Route::get('/{request}', [RequestController::class, 'show'])->name('show');
    });
});

// ------------------------
// 管理者ログインリダイレクト
// ------------------------
Route::redirect('/admin/login', '/login');

// ------------------------
// 管理者専用ルート
// ------------------------
Route::prefix('admin')->middleware(['auth', 'can:admin'])->group(function () {

    Route::get('/attendances', [AdminAttendanceController::class, 'daily'])->name('admin.attendance.daily');
    Route::get('/attendances/{attendance}', [AdminAttendanceController::class, 'show'])->name('admin.attendance.show');

    Route::get('/attendances/{attendance}/edit', [AdminAttendanceController::class, 'edit'])->name('admin.attendance.edit');
    Route::put('/attendances/{attendance}', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/monthly', [UserController::class, 'showMonthlyAttendance'])->name('admin.users.monthly');
    Route::get('/users/{user}/monthly/export', [UserController::class, 'exportMonthlyCsv'])->name('admin.users.monthly.export');
});

Route::prefix('admin/requests')->middleware(['auth', 'can:admin'])->name('admin.requests.')->group(function () {
    Route::get('/', [AdminRequestController::class, 'index'])->name('index');
    Route::get('/{request}', [AdminRequestController::class, 'show'])->name('show');
    Route::post('/{request}/approve', [AdminRequestController::class, 'approve'])->name('approve');
});
