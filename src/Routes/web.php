<?php

use Illuminate\Support\Facades\Route;

//Route::middleware(config('audit.search_middleware_name'))
//     ->get('admin/audits/search', [AuditSearchController::class, 'index'])
//     ->name('admin.audits.search');

Route::get('admin/audits/search', function () {
    echo 'hihihi';
});