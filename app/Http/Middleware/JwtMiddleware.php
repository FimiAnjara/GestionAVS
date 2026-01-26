<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    /**
     * Vérifier la validité du JWT dans les headers
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Récupérer le token depuis Authorization header
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token JWT manquant',
                ], 401);
            }

            // Valider et authentifier le token
            $user = JWTAuth::authenticate($token);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non trouvé',
                ], 401);
            }

            // Ajouter l'utilisateur à la requête
            $request->merge(['user' => $user]);

            return $next($request);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide ou expiré: ' . $e->getMessage(),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'authentification',
            ], 500);
        }
    }
}
