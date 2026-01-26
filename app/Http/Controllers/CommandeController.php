<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Client;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Afficher la liste des commandes clients
     */
    public function list(Request $request)
    {
        $query = Commande::with(['client', 'utilisateur']);
        
        // Filtrer par client
        if ($request->filled('id_client')) {
            $query->where('id_client', $request->id_client);
        }
        
        // Filtrer par date
        if ($request->filled('date_from')) {
            $query->where('date_', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_', '<=', $request->date_to);
        }
        
        // Filtrer par état
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        
        // Filtrer par ID
        if ($request->filled('id')) {
            $query->where('id_commande', 'like', '%' . $request->id . '%');
        }
        
        $commandes = $query->latest('date_')->paginate(10);
        $clients = Client::all();
        
        return view('commande.list', compact('commandes', 'clients'));
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show($id)
    {
        $commande = Commande::with(['client', 'utilisateur', 'commandeFille.article'])->findOrFail($id);
        return view('commande.show', compact('commande'));
    }

    /**
     * Supprimer une commande
     */
    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->delete();
        
        return redirect()->route('commande.list')->with('success', 'Commande supprimée avec succès');
    }
}
