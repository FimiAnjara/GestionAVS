<?php

namespace App\Http\Controllers;

use App\Models\Magasin;
use App\Models\Emplacement;
use App\Models\Stock;
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

        return view('magasin.list', compact('magasins'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('magasin.create');
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
        ]);

        $id = 'MAG_' . uniqid();
        Magasin::create([
            'id_magasin' => $id,
            'nom' => $request->nom,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('magasin.list')->with('success', 'Magasin créé avec succès');
    }

    /**
     * Afficher les détails d'un magasin
     */
    public function show($id)
    {
        $magasin = Magasin::findOrFail($id);
        
        return view('magasin.show', compact('magasin'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $magasin = Magasin::findOrFail($id);
        
        return view('magasin.edit', compact('magasin'));
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
        ]);

        $magasin->update([
            'nom' => $request->nom,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
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
        $query = Magasin::query();

        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }

        $magasins = $query->get();

        $locations = $magasins->map(function ($magasin) {
            return [
                'id' => $magasin->id_magasin,
                'nom' => $magasin->nom,
                'latitude' => floatval($magasin->latitude),
                'longitude' => floatval($magasin->longitude),
            ];
        });

        $totalMagasins = Magasin::count();
        $magasinsAntananarivo = $locations->count();

        return view('magasin.carte', compact('locations', 'magasins', 'totalMagasins', 'magasinsAntananarivo'));
    }

    /**
     * API : Récupérer les magasins en JSON
     */
    public function getMagasins(Request $request)
    {
        $query = Magasin::query();

        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }

        $magasins = $query->get();

        $locations = $magasins->map(function ($magasin) {
            return [
                'id' => $magasin->id_magasin,
                'nom' => $magasin->nom,
                'latitude' => floatval($magasin->latitude),
                'longitude' => floatval($magasin->longitude),
            ];
        });

        return response()->json($locations);
    }
}

