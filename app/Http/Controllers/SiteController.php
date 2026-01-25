<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Entite;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Afficher la liste des sites
     */
    public function list(Request $request)
    {
        $query = Site::with('entite.groupe')->withCount('magasins');

        if ($request->filled('localisation')) {
            $query->where('localisation', 'like', '%' . $request->localisation . '%');
        }

        if ($request->filled('entite')) {
            $query->where('id_entite', $request->entite);
        }

        $sites = $query->paginate(10);
        $entites = Entite::all();

        return view('organigramme.site.list', compact('sites', 'entites'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $entites = Entite::with('groupe')->get();
        return view('organigramme.site.create', compact('entites'));
    }

    /**
     * Stocker un nouveau site
     */
    public function store(Request $request)
    {
        $request->validate([
            'localisation' => 'required|string|max:50',
            'id_entite' => 'required|exists:entite,id_entite',
        ]);

        $id = 'SITE_' . strtoupper(uniqid());
        Site::create([
            'id_site' => $id,
            'localisation' => $request->localisation,
            'id_entite' => $request->id_entite,
        ]);

        return redirect()->route('site.list')->with('success', 'Site créé avec succès');
    }

    /**
     * Afficher les détails d'un site
     */
    public function show($id)
    {
        $site = Site::with(['entite.groupe', 'magasins'])->findOrFail($id);
        return view('organigramme.site.show', compact('site'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $site = Site::findOrFail($id);
        $entites = Entite::with('groupe')->get();
        return view('organigramme.site.edit', compact('site', 'entites'));
    }

    /**
     * Mettre à jour un site
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'localisation' => 'required|string|max:50',
            'id_entite' => 'required|exists:entite,id_entite',
        ]);

        $site = Site::findOrFail($id);
        $site->update([
            'localisation' => $request->localisation,
            'id_entite' => $request->id_entite,
        ]);

        return redirect()->route('site.list')->with('success', 'Site mis à jour avec succès');
    }

    /**
     * Supprimer un site
     */
    public function destroy($id)
    {
        $site = Site::findOrFail($id);
        $site->delete();

        return redirect()->route('site.list')->with('success', 'Site supprimé avec succès');
    }
}
