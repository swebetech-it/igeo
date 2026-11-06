<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectFinanceController extends Controller
{
    public function show($id)
    {
        $project = Project::with(['expenses', 'incomes'])->findOrFail($id);
        return view('finance.project-dashboard', compact('project'));
    }
}
