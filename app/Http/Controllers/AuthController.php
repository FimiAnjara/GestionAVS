<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Magasin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Connexion utilisateur et génération du JWT
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'mdp' => 'required|string|min:6',
        ]);

        try {
            // Rechercher l'utilisateur par email avec ses relations
            $user = Utilisateur::with(['role', 'departement', 'magasin', 'site', 'entite'])
                ->where('email', $validated['email'])
                ->first();

            if (!$user || !Hash::check($validated['mdp'], $user->mdp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email ou mot de passe incorrect',
                ], 401);
            }

            // Générer le token JWT
            $token = JWTAuth::fromUser($user);

            // Récupérer les informations du magasin
            $magasin = $user->magasin;
            $site = $user->site;
            $entite = $user->entite;

            // Stocker les infos utilisateur en session pour les vues Blade
            session([
                'user_id' => $user->id_utilisateur,
                'user_email' => $user->email,
                'user_role' => $user->role?->libelle,
                'user_departement' => $user->departement?->libelle,
                'user_magasin_id' => $user->id_magasin,
                'user_magasin_nom' => $magasin?->nom ?? 'Non défini',
                'user_site_id' => $user->id_site,
                'user_site_nom' => $site?->libelle ?? 'Non défini',
                'user_entite_id' => $user->id_entite,
                'user_entite_nom' => $entite?->libelle ?? 'Non défini',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'token' => $token,
                'user' => [
                    'id' => $user->id_utilisateur,
                    'email' => $user->email,
                    'departement' => $user->departement?->libelle,
                    'role' => $user->role?->libelle,
                    'magasin' => [
                        'id' => $user->id_magasin,
                        'nom' => $magasin?->nom ?? null,
                    ],
                    'site' => [
                        'id' => $user->id_site,
                        'nom' => $site?->libelle ?? null,
                    ],
                    'entite' => [
                        'id' => $user->id_entite,
                        'nom' => $entite?->libelle ?? null,
                    ],
                ],
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du token',
            ], 500);
        }
    }

    /**
     * Déconnexion utilisateur
     */
    public function logout()
    {
        try {
            // Vider la session
            session()->flush();
            
            // Invalider le token JWT si présent
            if (JWTAuth::getToken()) {
                JWTAuth::invalidate(JWTAuth::getToken());
            }

            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie',
            ], 200);
        } catch (JWTException $e) {
            // Même en cas d'erreur, vider la session
            session()->flush();
            
            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie',
            ], 200);
        }
    }

    /**
     * Rafraîchir le token JWT
     */
    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Token rafraîchi',
                'token' => $newToken,
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rafraîchissement du token',
            ], 500);
        }
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $user->load(['role', 'departement', 'magasin', 'site', 'entite']);

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id_utilisateur,
                    'email' => $user->email,
                    'departement' => $user->departement?->libelle,
                    'role' => $user->role?->libelle,
                    'magasin' => [
                        'id' => $user->id_magasin,
                        'nom' => $user->magasin?->nom ?? null,
                    ],
                    'site' => [
                        'id' => $user->id_site,
                        'nom' => $user->site?->libelle ?? null,
                    ],
                    'entite' => [
                        'id' => $user->id_entite,
                        'nom' => $user->entite?->libelle ?? null,
                    ],
                ],
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide ou expiré',
            ], 401);
        }
    }
}
