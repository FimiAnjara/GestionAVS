<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use Illuminate\Http\Request;

class GroupeController extends Controller
{
    /**
     * Afficher la liste des groupes
     */
    public function list(Request $request)
    {
        $query = Groupe::withCount('entites');

        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }

        $groupes = $query->paginate(10);

        return view('organigramme.groupe.list', compact('groupes'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('organigramme.groupe.create');
    }

    /**
     * Stocker un nouveau groupe
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:50',
        ]);

        $id = 'GRP_' . strtoupper(uniqid());
        Groupe::create([
            'id_groupe' => $id,
            'nom' => $request->nom,
        ]);

        return redirect()->route('groupe.list')->with('success', 'Groupe créé avec succès');
    }

    /**
     * Afficher les détails d'un groupe
     */
    public function show($id)
    {
        $groupe = Groupe::with('entites')->findOrFail($id);
        return view('organigramme.groupe.show', compact('groupe'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $groupe = Groupe::findOrFail($id);
        return view('organigramme.groupe.edit', compact('groupe'));
    }

    /**
     * Mettre à jour un groupe
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:50',
        ]);

        $groupe = Groupe::findOrFail($id);
        $groupe->update([
            'nom' => $request->nom,
        ]);

        return redirect()->route('groupe.list')->with('success', 'Groupe mis à jour avec succès');
    }

    /**
     * Supprimer un groupe
     */
    public function destroy($id)
    {
        $groupe = Groupe::findOrFail($id);
        $groupe->delete();

        return redirect()->route('groupe.list')->with('success', 'Groupe supprimé avec succès');
    }
}
