<?php

namespace App\Http\Controllers;

use App\Models\Entite;
use App\Models\Groupe;
use Illuminate\Http\Request;

class EntiteController extends Controller
{
    /**
     * Afficher la liste des entités
     */
    public function list(Request $request)
    {
        $query = Entite::with('groupe')->withCount('sites');

        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }

        if ($request->filled('groupe')) {
            $query->where('id_groupe', $request->groupe);
        }

        $entites = $query->paginate(10);
        $groupes = Groupe::all();

        return view('organigramme.entite.list', compact('entites', 'groupes'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $groupes = Groupe::all();
        return view('organigramme.entite.create', compact('groupes'));
    }

    /**
     * Stocker une nouvelle entité
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:50',
            'description' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'code_couleur' => 'nullable|string|max:50',
            'id_groupe' => 'required|exists:groupe,id_groupe',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos/entites', 'public');
        }

        $id = 'ENT_' . strtoupper(uniqid());
        Entite::create([
            'id_entite' => $id,
            'nom' => $request->nom,
            'description' => $request->description,
            'logo' => $logoPath,
            'code_couleur' => $request->code_couleur,
            'id_groupe' => $request->id_groupe,
        ]);

        return redirect()->route('entite.list')->with('success', 'Entité créée avec succès');
    }

    /**
     * Afficher les détails d'une entité
     */
    public function show($id)
    {
        $entite = Entite::with(['groupe', 'sites'])->findOrFail($id);
        return view('organigramme.entite.show', compact('entite'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $entite = Entite::findOrFail($id);
        $groupes = Groupe::all();
        return view('organigramme.entite.edit', compact('entite', 'groupes'));
    }

    /**
     * Mettre à jour une entité
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:50',
            'description' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'code_couleur' => 'nullable|string|max:50',
            'id_groupe' => 'required|exists:groupe,id_groupe',
        ]);

        $entite = Entite::findOrFail($id);
        
        $logoPath = $entite->logo;
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo si existe
            if ($entite->logo && \Storage::disk('public')->exists($entite->logo)) {
                \Storage::disk('public')->delete($entite->logo);
            }
            $logoPath = $request->file('logo')->store('logos/entites', 'public');
        }

        $entite->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'logo' => $logoPath,
            'code_couleur' => $request->code_couleur,
            'id_groupe' => $request->id_groupe,
        ]);

        return redirect()->route('entite.list')->with('success', 'Entité mise à jour avec succès');
    }

    /**
     * Supprimer une entité
     */
    public function destroy($id)
    {
        $entite = Entite::findOrFail($id);
        $entite->delete();

        return redirect()->route('entite.list')->with('success', 'Entité supprimée avec succès');
    }
}
