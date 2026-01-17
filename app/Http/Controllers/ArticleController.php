<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\Unite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['categorie', 'unite'])
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('articles.list', compact('articles'));
    }

    public function create()
    {
        $categories = Categorie::whereNull('deleted_at')->get();
        $unites = Unite::whereNull('deleted_at')->get();
        return view('articles.create', compact('categories', 'unites'));
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Categorie::whereNull('deleted_at')->get();
        $unites = Unite::whereNull('deleted_at')->get();
        return view('articles.edit', compact('article', 'categories', 'unites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:250',
            'stock' => 'required|numeric|min:0',
            'id_categorie' => 'required|exists:categorie,id_categorie',
            'id_unite' => 'required|exists:unite,id_unite',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('articles', 'public');
            }

            $idArticle = 'ART-' . time();
            
            Article::create([
                'id_article' => $idArticle,
                'nom' => $validated['nom'],
                'stock' => $validated['stock'],
                'id_categorie' => $validated['id_categorie'],
                'id_unite' => $validated['id_unite'],
                'photo' => $photoPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article ajouté avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'article'
            ], 500);
        }
    }

    public function show($id)
    {
        $article = Article::with(['categorie', 'unite'])->findOrFail($id);
        return view('articles.show', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:250',
            'stock' => 'required|numeric|min:0',
            'id_categorie' => 'required|exists:categorie,id_categorie',
            'id_unite' => 'required|exists:unite,id_unite',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('photo')) {
                if ($article->photo) {
                    Storage::disk('public')->delete($article->photo);
                }
                $validated['photo'] = $request->file('photo')->store('articles', 'public');
            }

            $article->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Article modifié avec succès'
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
