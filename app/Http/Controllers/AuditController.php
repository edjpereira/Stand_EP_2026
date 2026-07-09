<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Exibe o registo de auditoria técnica.
     */
    public function index()
    {
        $activities = Activity::with('causer')
            ->latest()
            ->paginate(25);

        return view('admin.audit', compact('activities'));
    }
}
