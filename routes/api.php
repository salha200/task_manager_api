<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\TaskDependencyController;
use App\Http\Controllers\ReportController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::prefix('tasks')->group(function () {
    Route::post('/', [TaskController::class, 'create']); // إنشاء مهمة
    Route::put('{id}/status', [TaskController::class, 'updateStatus']); // تحديث حالة المهمة
    Route::put('{id}/reassign', [TaskController::class, 'reassign']); // إعادة تعيين مهمة
    Route::post('{id}/comments', [CommentController::class, 'addComment']); // إضافة تعليق لمهمة
    Route::post('{id}/attachments', [AttachmentController::class, 'addAttachment']); // إضافة مرفق لمهمة
    Route::get('{id}', [TaskController::class, 'show']); // عرض تفاصيل مهمة
    Route::post('{id}/assign', [TaskController::class, 'assignTask']); // تعيين مهمة لمستخدم
});

Route::get('/reports/daily-tasks', [ReportController::class, 'generateDailyReport']);
