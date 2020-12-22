<?php

use Illuminate\Support\Facades\Route;
use Label84\MailViewer\Http\Controllers\MailViewerController;

Route::get('/', [MailViewerController::class, 'index'])->name('mailviewer.index');
Route::get('/analytics', [MailViewerController::class, 'analytics'])->name('mailviewer.analytics');
Route::get('/{mailViewerItem:uuid}', [MailViewerController::class, 'show'])->name('mailviewer.show');
