<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('clients.list', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:250',
        ]);

        try {
            $idClient = 'CLI-' . time();
            
            Client::create([
                'id_client' => $idClient,
                'nom' => $validated['nom'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client ajouté avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du client'
            ], 500);
        }
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.show', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:250',
        ]);

        try {
            $client->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Client modifié avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Client supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $clients = Client::whereNull('deleted_at')
            ->where('nom', 'like', "%{$search}%")
            ->get();
        
        return response()->json($clients);
    }
}
