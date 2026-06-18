<?php

declare(strict_types=1);

use App\Modules\CMS\Http\Controllers\Public\PublicCMSController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    // Visitor-facing CMS routes
    Route::get('/berita/{slug}', [PublicCMSController::class, 'showPost'])->name('public.posts.show');
    Route::get('/halaman/{slug}', [PublicCMSController::class, 'showPage'])->name('public.pages.show');
    Route::get('/kategori/{slug}', [PublicCMSController::class, 'showCategory'])->name('public.categories.show');
});
