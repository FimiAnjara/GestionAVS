@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-5">
    <!-- En-tête du Dashboard -->
    <div class="mb-5">
        <h1 class="display-4 fw-bold">
            <i class="bi bi-speedometer2 text-primary"></i> Dashboard
        </h1>
        <p class="text-muted lead">Bienvenue sur votre tableau de bord de gestion</p>
    </div>

    <!-- Statistiques -->
    <div class="row mb-5">
        <!-- Carte Clients -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people text-primary" style="font-size: 2.5rem;"></i>
                    <h6 class="card-title mt-3 text-muted">Total Clients</h6>
                    <p class="display-6 fw-bold text-primary">
                        {{ \App\Models\Client::whereNull('deleted_at')->count() }}
                    </p>
                    <a href="{{ route('clients.list') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right"></i> Voir la liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Carte Fournisseurs -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <i class="bi bi-briefcase text-success" style="font-size: 2.5rem;"></i>
                    <h6 class="card-title mt-3 text-muted">Total Fournisseurs</h6>
                    <p class="display-6 fw-bold text-success">
                        {{ \App\Models\Fournisseur::whereNull('deleted_at')->count() }}
                    </p>
                    <a href="{{ route('fournisseurs.list') }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-arrow-right"></i> Voir la liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Carte Synthèse -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up text-info" style="font-size: 2.5rem;"></i>
                    <h6 class="card-title mt-3 text-muted">Total Tiers</h6>
                    <p class="display-6 fw-bold text-info">
                        {{ \App\Models\Client::whereNull('deleted_at')->count() + \App\Models\Fournisseur::whereNull('deleted_at')->count() }}
                    </p>
                    <a href="{{ route('clients.list') }}" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-arrow-right"></i> Gérer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning-fill"></i> Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('clients.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-person-plus"></i> Ajouter un Client
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('fournisseurs.create') }}" class="btn btn-success w-100">
                                <i class="bi bi-briefcase-plus"></i> Ajouter un Fournisseur
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('clients.list') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-list-ul"></i> Liste des Clients
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('fournisseurs.list') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-list-ul"></i> Liste Fournisseurs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations Système -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Informations
                    </h5>
                </div>
                <div class="card-body">
                    <p class="small">
                        <strong>Application:</strong> GestionAVS<br>
                        <strong>Version:</strong> 1.0.0<br>
                        <strong>Langue:</strong> Français<br>
                        <strong>Thème:</strong> Bootstrap 5<br>
                        <strong>Date/Heure:</strong><br>
                        <span class="text-muted">{{ now()->format('d/m/Y H:i:s') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers Clients -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people"></i> Derniers Clients
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Client::whereNull('deleted_at')->latest()->limit(5)->get() as $client)
                                <tr>
                                    <td>{{ $client->nom }}</td>
                                    <td>
                                        <small class="text-muted">{{ $client->created_at->format('d/m/Y') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Aucun client</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Derniers Fournisseurs -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-briefcase"></i> Derniers Fournisseurs
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Fournisseur::whereNull('deleted_at')->latest()->limit(5)->get() as $fournisseur)
                                <tr>
                                    <td>{{ $fournisseur->nom }}</td>
                                    <td>
                                        <small class="text-muted">{{ $fournisseur->created_at->format('d/m/Y') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Aucun fournisseur</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
