@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    use App\Helpers\PermissionHelper;
    $userRole = session('user_role');
@endphp

<div class="container-fluid py-3">
    <!-- En-tête du Dashboard -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-speedometer2 text-primary"></i> Tableau de bord
                </h2>
                <p class="text-muted mb-0">
                    Bienvenue, <strong>{{ session('user_email', 'Utilisateur') }}</strong> 
                    <span class="badge bg-primary">{{ $userRole ?? 'Non défini' }}</span>
                </p>
            </div>
            <div>
                <span class="text-muted">
                    <i class="bi bi-building me-1"></i> {{ session('user_departement', 'Non défini') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistiques selon les permissions -->
    <div class="row mb-4">
        @if(PermissionHelper::hasMenuAccess($userRole, 'clients'))
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card shadow-sm border-0 h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Clients</h6>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Client::whereNull('deleted_at')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(PermissionHelper::hasMenuAccess($userRole, 'fournisseurs'))
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card shadow-sm border-0 h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-briefcase text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Fournisseurs</h6>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Fournisseur::whereNull('deleted_at')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(PermissionHelper::hasMenuAccess($userRole, 'articles'))
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card shadow-sm border-0 h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="bi bi-box text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Articles</h6>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Article::whereNull('deleted_at')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(PermissionHelper::hasMenuAccess($userRole, 'magasin'))
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card shadow-sm border-0 h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="bi bi-shop text-info" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Magasins</h6>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Magasin::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Actions Rapides selon les permissions -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-lightning-fill text-warning"></i> Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'proforma-fournisseur', 'create'))
                        <div class="col-md-4">
                            <a href="{{ route('proforma-fournisseur.create') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-file-earmark-plus"></i> Nouvelle Demande
                            </a>
                        </div>
                        @endif
                        
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-commande', 'create'))
                        <div class="col-md-4">
                            <a href="{{ route('bon-commande.create') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-cart-plus"></i> Bon de Commande
                            </a>
                        </div>
                        @endif
                        
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-reception', 'create'))
                        <div class="col-md-4">
                            <a href="{{ route('bon-reception.create') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-box-arrow-in-down"></i> Bon de Réception
                            </a>
                        </div>
                        @endif
                        
                        @if(PermissionHelper::hasMenuAccess($userRole, 'clients', 'create'))
                        <div class="col-md-4">
                            <a href="{{ route('clients.create') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-person-plus"></i> Nouveau Client
                            </a>
                        </div>
                        @endif
                        
                        @if(PermissionHelper::hasMenuAccess($userRole, 'fournisseurs', 'create'))
                        <div class="col-md-4">
                            <a href="{{ route('fournisseurs.create') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-briefcase-fill"></i> Nouveau Fournisseur
                            </a>
                        </div>
                        @endif
                        
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-stock', 'create'))
                        <div class="col-md-4">
                            <a href="{{ route('mvt-stock.create') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-arrow-left-right"></i> Mouvement Stock
                            </a>
                        </div>
                        @endif
                        
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-caisse', 'create'))
                        <div class="col-md-4">
                            <a href="{{ route('mvt-caisse.create') }}" class="btn btn-outline-danger w-100">
                                <i class="bi bi-cash-stack"></i> Mouvement Caisse
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations Utilisateur -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-person-circle text-primary"></i> Mon Profil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-person text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold">{{ session('user_email', 'Utilisateur') }}</h6>
                            <small class="text-muted">{{ $userRole ?? 'Non défini' }}</small>
                        </div>
                    </div>
                    <hr>
                    <p class="small mb-2">
                        <i class="bi bi-building me-2 text-muted"></i>
                        <strong>Département:</strong> {{ session('user_departement', 'Non défini') }}
                    </p>
                    <p class="small mb-0">
                        <i class="bi bi-clock me-2 text-muted"></i>
                        <strong>Connecté:</strong> {{ now()->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableaux selon les permissions -->
    <div class="row">
        @if(PermissionHelper::hasMenuAccess($userRole, 'clients', 'list'))
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-people text-primary"></i> Derniers Clients
                    </h5>
                    <a href="{{ route('clients.list') }}" class="btn btn-sm btn-outline-primary">
                        Voir tout <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Contact</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Client::whereNull('deleted_at')->latest()->limit(5)->get() as $client)
                                <tr>
                                    <td>{{ $client->nom }}</td>
                                    <td><small class="text-muted">{{ $client->telephone ?? '-' }}</small></td>
                                    <td><small class="text-muted">{{ $client->created_at->format('d/m/Y') }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Aucun client</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(PermissionHelper::hasMenuAccess($userRole, 'fournisseurs', 'list'))
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-briefcase text-success"></i> Derniers Fournisseurs
                    </h5>
                    <a href="{{ route('fournisseurs.list') }}" class="btn btn-sm btn-outline-success">
                        Voir tout <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Contact</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Fournisseur::whereNull('deleted_at')->latest()->limit(5)->get() as $fournisseur)
                                <tr>
                                    <td>{{ $fournisseur->nom }}</td>
                                    <td><small class="text-muted">{{ $fournisseur->telephone ?? '-' }}</small></td>
                                    <td><small class="text-muted">{{ $fournisseur->created_at->format('d/m/Y') }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Aucun fournisseur</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Tableaux Bons de Commande -->
    <div class="row">
        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-commande', 'list'))
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-cart text-success"></i> Derniers Bons de Commande (Achat)
                    </h5>
                    <a href="{{ route('bon-commande.list') }}" class="btn btn-sm btn-outline-success">
                        Voir tout <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Fournisseur</th>
                                <th>Date</th>
                                <th>État</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\BonCommande::with(['proformaFournisseur.fournisseur'])->whereNull('deleted_at')->latest()->limit(5)->get() as $bc)
                                <tr>
                                    <td><strong>{{ $bc->id_bonCommande }}</strong></td>
                                    <td><small>{{ $bc->proformaFournisseur?->fournisseur?->nom ?? 'N/A' }}</small></td>
                                    <td><small class="text-muted">{{ $bc->date_?->format('d/m/Y') ?? '-' }}</small></td>
                                    <td>
                                        @if($bc->etat == 1)
                                            <span class="badge bg-warning text-dark">Créée</span>
                                        @elseif($bc->etat == 2)
                                            <span class="badge bg-info">En cours</span>
                                        @else
                                            <span class="badge bg-success">Reçue</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Aucun bon de commande</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-commande-client', 'list'))
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-bag-check text-primary"></i> Derniers Bons de Commande (Vente)
                    </h5>
                    <a href="{{ route('bon-commande-client.list') }}" class="btn btn-sm btn-outline-primary">
                        Voir tout <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>État</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Ventes\BonCommandeClient::with('client')->whereNull('deleted_at')->latest()->limit(5)->get() as $bcc)
                                <tr>
                                    <td><strong>{{ $bcc->id_bon_commande_client }}</strong></td>
                                    <td><small>{{ $bcc->client?->nom ?? 'N/A' }}</small></td>
                                    <td><small class="text-muted">{{ $bcc->date_?->format('d/m/Y') ?? '-' }}</small></td>
                                    <td>
                                        @if($bcc->etat == 1)
                                            <span class="badge bg-warning text-dark">Créée</span>
                                        @elseif($bcc->etat == 2)
                                            <span class="badge bg-info">Confirmée</span>
                                        @else
                                            <span class="badge bg-success">Expédiée</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Aucun bon de commande client</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Vérifier l'authentification au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('jwt_token');
        if (!token) {
            window.location.href = '/login';
        }
    });
</script>
@endpush
@endsection
