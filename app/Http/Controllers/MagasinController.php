<?php

namespace App\Http\Controllers;

use App\Models\Magasin;
use App\Models\Emplacement;
use App\Models\Stock;
use App\Models\Site;
use App\Models\Entite;
use App\Models\Groupe;
use Illuminate\Http\Request;

class MagasinController extends Controller
{
    /**
     * Afficher la liste des magasins
     */
    public function list(Request $request)
    {
        $query = Magasin::query();

        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }

        $magasins = $query->paginate(10);

        return view('organigramme.magasin.list', compact('magasins'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $sites = Site::with('entite.groupe')->get();
        return view('organigramme.magasin.create', compact('sites'));
    }

    /**
     * Stocker un nouveau magasin
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'id_site' => 'nullable|exists:site,id_site',
        ]);

        $id = 'MAG_' . uniqid();
        Magasin::create([
            'id_magasin' => $id,
            'nom' => $request->nom,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'id_site' => $request->id_site,
        ]);

        return redirect()->route('magasin.list')->with('success', 'Magasin créé avec succès');
    }

    /**
     * Afficher les détails d'un magasin
     */
    public function show($id)
    {
        $magasin = Magasin::findOrFail($id);
        
        return view('organigramme.magasin.show', compact('magasin'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $magasin = Magasin::findOrFail($id);
        $sites = Site::with('entite.groupe')->get();
        
        return view('organigramme.magasin.edit', compact('magasin', 'sites'));
    }

    /**
     * Mettre à jour un magasin
     */
    public function update(Request $request, $id)
    {
        $magasin = Magasin::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'id_site' => 'nullable|exists:site,id_site',
        ]);

        $magasin->update([
            'nom' => $request->nom,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'id_site' => $request->id_site,
        ]);

        return redirect()->route('magasin.show', $magasin->id_magasin)->with('success', 'Magasin modifié avec succès');
    }

    /**
     * Supprimer un magasin
     */
    public function destroy($id)
    {
        $magasin = Magasin::findOrFail($id);
        $magasin->delete();

        return redirect()->route('magasin.list')->with('success', 'Magasin supprimé');
    }

    /**
     * Afficher la carte des magasins
     */
    public function carte(Request $request)
    {
        $query = Magasin::with(['site.entite.groupe']);

        // Filtre par groupe
        if ($request->filled('id_groupe')) {
            $query->whereHas('site.entite', function($q) use ($request) {
                $q->where('id_groupe', $request->id_groupe);
            });
        }

        // Filtre par entité
        if ($request->filled('id_entite')) {
            $query->whereHas('site', function($q) use ($request) {
                $q->where('id_entite', $request->id_entite);
            });
        }

        // Filtre par site
        if ($request->filled('id_site')) {
            $query->where('id_site', $request->id_site);
        }

        // Filtre par nom
        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }

        $magasins = $query->get();

        $locations = $magasins->map(function ($magasin) {
            $entite = $magasin->site?->entite;
            return [
                'id' => $magasin->id_magasin,
                'nom' => $magasin->nom,
                'latitude' => floatval($magasin->latitude),
                'longitude' => floatval($magasin->longitude),
                'site' => $magasin->site?->localisation,
                'entite' => $entite?->nom,
                'groupe' => $entite?->groupe?->nom,
                'code_couleur' => $entite?->code_couleur ?? '#1a73e8',
            ];
        });

        $totalMagasins = Magasin::count();
        $magasinsAffiches = $locations->count();

        // Données pour les filtres
        $groupes = Groupe::orderBy('nom')->get();
        $entites = Entite::orderBy('nom')->get();
        $sites = Site::orderBy('localisation')->get();

        return view('organigramme.magasin.carte', compact(
            'locations', 
            'magasins', 
            'totalMagasins', 
            'magasinsAffiches',
            'groupes',
            'entites',
            'sites'
        ));
    }

    /**
     * API : Récupérer les magasins en JSON
     */
    public function getMagasins(Request $request)
    {
        $query = Magasin::with(['site.entite.groupe']);

        // Filtre par groupe
        if ($request->filled('id_groupe')) {
            $query->whereHas('site.entite', function($q) use ($request) {
                $q->where('id_groupe', $request->id_groupe);
            });
        }

        // Filtre par entité
        if ($request->filled('id_entite')) {
            $query->whereHas('site', function($q) use ($request) {
                $q->where('id_entite', $request->id_entite);
            });
        }

        // Filtre par site
        if ($request->filled('id_site')) {
            $query->where('id_site', $request->id_site);
        }

        // Filtre par nom
        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }

        $magasins = $query->get();

        $locations = $magasins->map(function ($magasin) {
            $entite = $magasin->site?->entite;
            return [
                'id' => $magasin->id_magasin,
                'nom' => $magasin->nom,
                'latitude' => floatval($magasin->latitude),
                'longitude' => floatval($magasin->longitude),
                'site' => $magasin->site?->localisation,
                'entite' => $entite?->nom,
                'groupe' => $entite?->groupe?->nom,
                'code_couleur' => $entite?->code_couleur ?? '#1a73e8',
            ];
        });

        return response()->json($locations);
    }

    /**
     * API : Récupérer les entités par groupe
     */
    public function getEntitesByGroupe(Request $request)
    {
        $entites = Entite::when($request->filled('id_groupe'), function($q) use ($request) {
            $q->where('id_groupe', $request->id_groupe);
        })->orderBy('nom')->get(['id_entite', 'nom', 'code_couleur']);

        return response()->json($entites);
    }

    /**
     * API : Récupérer les sites par entité
     */
    public function getSitesByEntite(Request $request)
    {
        $sites = Site::when($request->filled('id_entite'), function($q) use ($request) {
            $q->where('id_entite', $request->id_entite);
        })->orderBy('localisation')->get(['id_site', 'localisation as nom']);

        return response()->json($sites);
    }

    /**
     * API : Récupérer les magasins par site
     */
    public function getMagasinsBySite(Request $request)
    {
        $magasins = Magasin::when($request->filled('id_site'), function($q) use ($request) {
            $q->where('id_site', $request->id_site);
        })->orderBy('nom')->get(['id_magasin', 'nom']);

        return response()->json($magasins);
    }
}

