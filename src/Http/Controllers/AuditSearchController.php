<?php

namespace OwenIt\Auditing\Http\Controllers;

use Illuminate\Routing\Controller;

class AuditSearchController extends Controller
{
    public function index(AuditSearchIndexRequest $request)
    {
        dump($request->all());
    }
}
