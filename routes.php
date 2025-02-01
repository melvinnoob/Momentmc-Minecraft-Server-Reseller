<?php

use Illuminate\Support\Facades\Route;
use App\Extensions\Servers\Momentmcreseller\Momentmcreseller;

// Route to configure the VPS
Route::get('/config/{product}/{invoice_id}', [Momentmcreseller::class, 'config'])
    ->name('extensions.momentmcreseller.config');


Route::get('/console/{product}/{invoice_id}', [Momentmcreseller::class, 'console'])->name('extensions.momentmcreseller.console');

Route::post('/power', [Momentmcreseller::class, 'power'])->name('extensions.momentmcreseller.power');

Route::get('/files/{product}/{invoice_id}', [Momentmcreseller::class, 'files'])->name('extensions.momentmcreseller.files');

Route::get('/files/{product}/{invoice_id}/list', [Momentmcreseller::class, 'listFiles']) ->name('extensions.momentmcreseller.files.list');

Route::get('/files/{product}/{invoice_id}/download', [Momentmcreseller::class, 'downloadFile']) ->name('extensions.momentmcreseller.files.download');

Route::get('/files/{product}/{invoice_id}/edit', [Momentmcreseller::class, 'editFile']) ->name('extensions.momentmcreseller.files.edit');

Route::post('/files/{product}/{invoice_id}/contents/save', [Momentmcreseller::class, 'save']) ->name('extensions.momentmcreseller.files.contents.save');

Route::post('/files/{product}/{invoice_id}/delete', [Momentmcreseller::class, 'delete']) ->name('extensions.momentmcreseller.files.delete');


