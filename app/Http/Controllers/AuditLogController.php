<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::latest()->paginate(15);
        return view('logs.index', compact('logs'));
    }
}
