<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleFille;
use App\Models\Categorie;
use App\Models\Unite;
use App\Models\Entite;
use App\Models\TypeEvaluationStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function index()
    {
        $query = Article::with(['categorie', 'unite', 'entite', 'articleFille'])
            ->whereNull('deleted_at');
        
        // Filtres
        if (request('categorie_id')) {
            $query->where('id_categorie', request('categorie_id'));
        }
        
        if (request('unite_id')) {
            $query->where('id_unite', request('unite_id'));
        }

        if (request('entite_id')) {
            $query->where('id_entite', request('entite_id'));
        }

        if (request('type_evaluation_id')) {
            $query->where('id_type_evaluation_stock', request('type_evaluation_id'));
        }
        
        $articles = $query->with('typeEvaluation')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $categories = Categorie::whereNull('deleted_at')->get();
        $unites = Unite::whereNull('deleted_at')->get();
        $entites = Entite::whereNull('deleted_at')->get();
        $typeEvaluations = TypeEvaluationStock::whereNull('deleted_at')->get();
        
        return view('articles.list', compact('articles', 'categories', 'unites', 'entites', 'typeEvaluations'));
    }

    public function create()
    {
        $categories = Categorie::whereNull('deleted_at')->get();
        $unites = Unite::whereNull('deleted_at')->get();
        $entites = Entite::whereNull('deleted_at')->get();
        $typeEvaluations = TypeEvaluationStock::whereNull('deleted_at')->get();
        return view('articles.create', compact('categories', 'unites', 'entites', 'typeEvaluations'));
    }

    public function edit($id)
    {
        $article = Article::with('articleFille')->findOrFail($id);
        $categories = Categorie::whereNull('deleted_at')->get();
        $unites = Unite::whereNull('deleted_at')->get();
        $entites = Entite::whereNull('deleted_at')->get();
        $typeEvaluations = TypeEvaluationStock::whereNull('deleted_at')->get();
        return view('articles.edit', compact('article', 'categories', 'unites', 'entites', 'typeEvaluations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:250',
            'id_categorie' => 'required|exists:categorie,id_categorie',
            'id_unite' => 'required|exists:unite,id_unite',
            'id_entite' => 'required|exists:entite,id_entite',
            'id_type_evaluation_stock' => 'required|exists:type_evaluation_stock,id_type_evaluation_stock',
            'prix_vente' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('articles', 'public');
            }

            $idArticle = 'ART-' . time();
            
            $article = Article::create([
                'id_article' => $idArticle,
                'nom' => $validated['nom'],
                'id_categorie' => $validated['id_categorie'],
                'id_unite' => $validated['id_unite'],
                'id_entite' => $validated['id_entite'],
                'id_type_evaluation_stock' => $validated['id_type_evaluation_stock'],
                'photo' => $photoPath,
            ]);

            // Créer ArticleFille avec le prix de vente
            ArticleFille::create([
                'id_articleFille' => 'AF-' . time(),
                'id_article' => $idArticle,
                'prix' => $validated['prix_vente'],
                'date_' => now(),
                'quantite' => 0,
                'id_unite' => $validated['id_unite'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Article ajouté avec succès'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'article: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $article = Article::with(['categorie', 'unite', 'entite', 'typeEvaluation', 'articleFille'])->findOrFail($id);
        return view('articles.show', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:250',
            'id_categorie' => 'required|exists:categorie,id_categorie',
            'id_unite' => 'required|exists:unite,id_unite',
            'id_entite' => 'required|exists:entite,id_entite',
            'id_type_evaluation_stock' => 'required|exists:type_evaluation_stock,id_type_evaluation_stock',
            'prix_vente' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('photo')) {
                if ($article->photo) {
                    Storage::disk('public')->delete($article->photo);
                }
                $validated['photo'] = $request->file('photo')->store('articles', 'public');
            }

            $article->update([
                'nom' => $validated['nom'],
                'id_categorie' => $validated['id_categorie'],
                'id_unite' => $validated['id_unite'],
                'id_entite' => $validated['id_entite'],
                'id_type_evaluation_stock' => $validated['id_type_evaluation_stock'],
                'photo' => $validated['photo'] ?? $article->photo,
            ]);

            // Mettre à jour ou créer ArticleFille avec le prix de vente
            $articleFille = ArticleFille::where('id_article', $id)->first();
            if ($articleFille) {
                $articleFille->update([
                    'prix' => $validated['prix_vente'],
                    'date_' => now(),
                    'id_unite' => $validated['id_unite'],
                ]);
            } else {
                ArticleFille::create([
                    'id_articleFille' => 'AF-' . time(),
                    'id_article' => $id,
                    'prix' => $validated['prix_vente'],
                    'date_' => now(),
                    'quantite' => 0,
                    'id_unite' => $validated['id_unite'],
                ]);
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Article modifié avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $article = Article::findOrFail($id);
            if ($article->photo) {
                Storage::disk('public')->delete($article->photo);
            }
            $article->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Article supprimé avec succès'
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
        
        $articles = Article::with(['categorie', 'unite'])
            ->whereNull('deleted_at')
            ->where('nom', 'like', "%{$search}%")
            ->get();
        
        return response()->json($articles);
    }
}
