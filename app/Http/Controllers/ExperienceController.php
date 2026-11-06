<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experience;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::orderBy('year', 'desc')->paginate(10);
        return view('public.experiences', compact('experiences'));
    }
}
