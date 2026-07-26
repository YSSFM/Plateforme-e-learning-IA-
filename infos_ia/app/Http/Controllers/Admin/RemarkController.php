<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Remarque;

class RemarkController extends Controller
{
    /**
     * Afficher toutes les remarques
     */
    public function index()
    {
        $remarks = Remarque::with(['admin', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        
        $users = User::orderBy('username')->get();
        
        return view('admin.remarks.index', compact('remarks', 'users'));
    }
    
    /**
     * Ajouter une remarque
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);
        
        Remarque::create([
            'admin_id' => auth()->id(),
            'user_id' => $request->user_id,
            'message' => $request->message,
            'type' => $request->type ?? 'info'
        ]);
        
        return redirect()->back()->with('success', 'Remarque ajoutée avec succès.');
    }
    
    /**
     * Supprimer une remarque
     */
    public function destroy($id)
    {
        $remark = Remarque::findOrFail($id);
        $remark->delete();
        
        return redirect()->back()->with('success', 'Remarque supprimée.');
    }
}