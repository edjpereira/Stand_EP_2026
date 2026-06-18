<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        // Vamos buscar as atividades mais recentes, carregando também o utilizador que a causou
        $activities = Activity::with('causer')
            ->latest()
            ->paginate(25);

        return view('admin.audit', compact('activities'));
    }
}
