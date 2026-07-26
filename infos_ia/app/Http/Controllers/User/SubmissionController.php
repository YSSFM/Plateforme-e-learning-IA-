<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soumission;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = Soumission::with('exercice.cours')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('user.submissions.index', compact('submissions'));
    }
}