<?php

declare(strict_types=1);

use App\Modules\Media\Http\Controllers\Admin\MediaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    Route::get('/media', [MediaController::class, 'index'])->name('admin.media.index');
    Route::post('/media/upload', [MediaController::class, 'store'])->name('admin.media.store');
    Route::post('/media/folder', [MediaController::class, 'createFolder'])->name('admin.media.folder.create');
    Route::delete('/media/{id}', [MediaController::class, 'destroy'])->name('admin.media.destroy');
    Route::delete('/media/folder/{id}', [MediaController::class, 'destroyFolder'])->name('admin.media.folder.destroy');
});
