@extends('layouts.app')

@section('title', 'Dashboard Global - Direction')

@push('styles')
<style>
    .stat-card {
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .mini-stat {
        padding: 1rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .progress-custom {
        height: 8px;
        border-radius: 4px;
    }
    .table-dashboard th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
@php
    use App\Helpers\PermissionHelper;
    $userRole = session('user_role');
@endphp

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
            <!-- Stock par Entité -->
            <div class="row mb-4">
                <div class="col-xl-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="bi bi-diagram-3 text-primary"></i> Stock par Entité
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="entiteStockChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Chiffre d'affaires par Entité -->
                <div class="col-xl-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="bi bi-bar-chart text-success"></i> Chiffre d'affaires par Entité
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="entiteCAChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-graph-up-arrow text-primary"></i> Dashboard Global
                    </h2>
                    <p class="text-muted mb-0">Vue d'ensemble de l'activité de l'entreprise</p>
                </div>
                <div>
                    <span class="badge bg-primary fs-6">
                        <i class="bi bi-calendar3 me-1"></i> {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Clients</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['clients']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Fournisseurs</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['fournisseurs']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Articles</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['articles']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Magasins</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['magasins']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques principaux -->
    <div class="row mb-4">
        <!-- Évolution Achats/Ventes -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-graph-up text-primary"></i> Évolution Achats vs Ventes (12 derniers mois)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- États des bons de commande -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-pie-chart text-success"></i> États Bons de Commande
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="etatBCChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Achats et Ventes -->
    <div class="row mb-4">
        <!-- Achats -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-cart-check text-danger"></i> Processus Achats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="mini-stat text-center">
                                <i class="bi bi-file-earmark-text text-primary fs-2"></i>
                                <h4 class="fw-bold mt-2 mb-0">{{ $achats['demandes'] }}</h4>
                                <small class="text-muted">Demandes d'achat</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mini-stat text-center">
                                <i class="bi bi-cart text-success fs-2"></i>
                                <h4 class="fw-bold mt-2 mb-0">{{ $achats['bons_commande'] }}</h4>
                                <small class="text-muted">Bons de commande</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mini-stat text-center">
                                <i class="bi bi-box-arrow-in-down text-info fs-2"></i>
                                <h4 class="fw-bold mt-2 mb-0">{{ $achats['bons_reception'] }}</h4>
                                <small class="text-muted">Bons de réception</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mini-stat text-center">
                                <i class="bi bi-receipt text-warning fs-2"></i>
                                <h4 class="fw-bold mt-2 mb-0">{{ $achats['factures'] }}</h4>
                                <small class="text-muted">Factures fournisseur</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventes -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-graph-up-arrow text-success"></i> Processus Ventes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="mini-stat text-center">
                                <i class="bi bi-file-earmark-check text-primary fs-2"></i>
                                <h4 class="fw-bold mt-2 mb-0">{{ $ventes['proformas'] }}</h4>
                                <small class="text-muted">Proformas</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mini-stat text-center">
                                <i class="bi bi-bag-check text-success fs-2"></i>
                                <h4 class="fw-bold mt-2 mb-0">{{ $ventes['commandes'] }}</h4>
                                <small class="text-muted">Commandes</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mini-stat text-center">
                                <i class="bi bi-truck text-info fs-2"></i>
                                <h4 class="fw-bold mt-2 mb-0">{{ $ventes['livraisons'] }}</h4>
                                <small class="text-muted">Livraisons</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="chart-container" style="height: 150px;">
                        <canvas id="ventesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mouvements Caisse et Stock par Magasin -->
    <div class="row mb-4">
        <!-- Mouvements Caisse -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-cash-stack text-success"></i> Mouvements de Caisse (6 derniers mois)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="caisseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock par Magasin -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-boxes text-warning"></i> Mouvements par Magasin
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="magasinChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Articles et Solde Caisses -->
    <div class="row mb-4">
        <!-- Top 5 Articles -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-trophy text-warning"></i> Top 5 Articles Commandés
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Article</th>
                                    <th class="text-end pe-4">Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topArticles as $index => $article)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge {{ $index === 0 ? 'bg-warning' : ($index === 1 ? 'bg-secondary' : 'bg-dark') }}">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td>{{ $article->nom }}</td>
                                    <td class="text-end pe-4">
                                        <strong>{{ number_format($article->total, 0) }}</strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Aucune donnée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solde des Caisses -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-safe text-info"></i> Solde des Caisses
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Caisse</th>
                                    <th class="text-end pe-4">Solde</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($caisses as $caisse)
                                <tr>
                                    <td class="ps-4">
                                        <i class="bi bi-safe2 text-primary me-2"></i>
                                        {{ $caisse['nom'] }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="{{ $caisse['solde'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ number_format($caisse['solde'], 2, ',', ' ') }} Ar
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">Aucune caisse</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Couleurs personnalisées
    const colors = {
        primary: 'rgb(13, 110, 253)',
        success: 'rgb(25, 135, 84)',
        warning: 'rgb(255, 193, 7)',
        danger: 'rgb(220, 53, 69)',
        info: 'rgb(13, 202, 240)',
        secondary: 'rgb(108, 117, 125)',
    };

    // 1. Graphique Évolution Achats/Ventes
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: @json($moisLabels),
            datasets: [
                {
                    label: 'Achats',
                    data: @json($achatsParMois),
                    borderColor: colors.danger,
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Ventes',
                    data: @json($ventesParMois),
                    borderColor: colors.success,
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    fill: true,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // 2. Graphique États Bons de Commande (Doughnut)
    const etatBCCtx = document.getElementById('etatBCChart').getContext('2d');
    new Chart(etatBCCtx, {
        type: 'doughnut',
        data: {
            labels: ['En cours', 'Validés', 'Réceptionnés', 'Annulés'],
            datasets: [{
                data: [
                    {{ $etatsBC['en_cours'] }},
                    {{ $etatsBC['valides'] }},
                    {{ $etatsBC['recus'] }},
                    {{ $etatsBC['annules'] }}
                ],
                backgroundColor: [
                    colors.warning,
                    colors.primary,
                    colors.success,
                    colors.danger
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            cutout: '60%'
        }
    });

    // 3. Graphique Ventes (Bar)
    const ventesCtx = document.getElementById('ventesChart').getContext('2d');
    new Chart(ventesCtx, {
        type: 'bar',
        data: {
            labels: ['Proformas', 'Commandes', 'Livraisons'],
            datasets: [{
                data: [{{ $ventes['proformas'] }}, {{ $ventes['commandes'] }}, {{ $ventes['livraisons'] }}],
                backgroundColor: [colors.primary, colors.success, colors.info],
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // 4. Graphique Mouvements Caisse
    const caisseCtx = document.getElementById('caisseChart').getContext('2d');
    const caisseData = @json($caisseParMois);
    new Chart(caisseCtx, {
        type: 'bar',
        data: {
            labels: caisseData.map(d => d.mois),
            datasets: [
                {
                    label: 'Entrées',
                    data: caisseData.map(d => d.entrees),
                    backgroundColor: colors.success,
                    borderRadius: 5,
                },
                {
                    label: 'Sorties',
                    data: caisseData.map(d => d.sorties),
                    backgroundColor: colors.danger,
                    borderRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('fr-FR') + ' Ar';
                        }
                    }
                }
            }
        }
    });

    // 5. Graphique Stock par Magasin
    const magasinCtx = document.getElementById('magasinChart').getContext('2d');
    const magasinData = @json($stockParMagasin);
    new Chart(magasinCtx, {
        type: 'bar',
        data: {
            labels: magasinData.map(d => d.nom),
            datasets: [{
                label: 'Mouvements',
                data: magasinData.map(d => d.articles),
                backgroundColor: [colors.primary, colors.success, colors.warning, colors.info, colors.danger],
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
