<?php

use Illuminate\Support\Facades\Route;
use OwenIt\Auditing\Http\Controllers\AuditSearchController;

// 'auth:admin-api'
Route::middleware(config('audit.search_middleware_name'))
     ->get('admin/audits/search', [AuditSearchController::class, 'index'])
     ->name('admin.audits.search');