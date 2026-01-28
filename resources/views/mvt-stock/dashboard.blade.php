@extends('layouts.app')

@section('title', 'Etat Financier du Stock')

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
        height: 350px;
    }
    .chart-container-lg {
        position: relative;
        height: 400px;
    }
    .valeur-stock {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0d6efd;
    }
    .table-stock th {
        font-weight: 600;
        background-color: #f8f9fa;
        font-size: 0.85rem;
    }
    .table-stock td {
        vertical-align: middle;
    }
    .badge-methode {
        font-size: 0.7rem;
        padding: 4px 8px;
    }
    .progress-stock {
        height: 8px;
        border-radius: 4px;
    }
</style>
@endpush

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('mvt-stock.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour aux mouvements
        </a>
        <a href="{{ route('stock.etat') }}" class="btn btn-outline-primary">
            <i class="bi bi-boxes me-2"></i>État du Stock
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>    
                    <p class="text-muted mb-0">Vue d'ensemble de la valorisation des stocks</p>
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
            <div class="card stat-card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Valeur Totale Stock</h6>
                            <h3 class="fw-bold mb-0 text-primary">{{ number_format($valeurTotaleStock, 0, ',', ' ') }} <small class="fs-6">Ar</small></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Entités</h6>
                            <h3 class="fw-bold mb-0">{{ count($stockParEntite) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Magasins</h6>
                            <h3 class="fw-bold mb-0">{{ count($stockParMagasin) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Articles en Stock</h6>
                            <h3 class="fw-bold mb-0">{{ $totalArticlesEnStock }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <!-- Valeur du Stock par Entité -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-diagram-3 text-primary"></i> Valeur du Stock par Entité
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="stockEntiteChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valeur du Stock par Magasin -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-shop text-success"></i> Valeur du Stock par Magasin
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="stockMagasinChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableaux détaillés -->
    <div class="row">
        <!-- Détail par Entité -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-building text-primary"></i> Détail par Entité
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-stock table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Entité</th>
                                    <th class="text-center">Magasins</th>
                                    <th class="text-end pe-3">Valeur Stock (Ar)</th>
                                    <th class="text-center" width="20%">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockParEntite as $item)
                                    @php
                                        $pourcentage = $valeurTotaleStock > 0 ? ($item['valeur'] / $valeurTotaleStock) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <strong>{{ $item['entite']->nom ?? 'Sans entité' }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ count($item['magasins']) }}</span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="fw-bold text-primary">{{ number_format($item['valeur'], 0, ',', ' ') }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress progress-stock flex-grow-1 me-2">
                                                    <div class="progress-bar bg-primary" style="width: {{ $pourcentage }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ number_format($pourcentage, 1) }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Aucune donnée disponible
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($stockParEntite) > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <th class="ps-3">Total</th>
                                    <th class="text-center">{{ count($stockParMagasin) }}</th>
                                    <th class="text-end pe-3 text-primary">{{ number_format($valeurTotaleStock, 0, ',', ' ') }} Ar</th>
                                    <th class="text-center">100%</th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détail par Magasin -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-shop text-success"></i> Détail par Magasin
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-stock table-hover mb-0">
                            <thead class="sticky-top bg-white">
                                <tr>
                                    <th class="ps-3">Magasin</th>
                                    <th class="text-center">Articles</th>
                                    <th class="text-end pe-3">Valeur Stock (Ar)</th>
                                    <th class="text-center" width="20%">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockParMagasin as $item)
                                    @php
                                        $pourcentage = $valeurTotaleStock > 0 ? ($item['valeur'] / $valeurTotaleStock) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <strong>{{ $item['magasin']->nom }}</strong>
                                            @if($item['magasin']->site && $item['magasin']->site->entite)
                                                <br><small class="text-muted">{{ $item['magasin']->site->entite->nom }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $item['nb_articles'] }}</span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="fw-bold text-success">{{ number_format($item['valeur'], 0, ',', ' ') }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress progress-stock flex-grow-1 me-2">
                                                    <div class="progress-bar bg-success" style="width: {{ $pourcentage }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ number_format($pourcentage, 1) }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Aucune donnée disponible
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($stockParMagasin) > 0)
                            <tfoot class="table-light sticky-bottom">
                                <tr>
                                    <th class="ps-3">Total</th>
                                    <th class="text-center">{{ $totalArticlesEnStock }}</th>
                                    <th class="text-end pe-3 text-success">{{ number_format($valeurTotaleStock, 0, ',', ' ') }} Ar</th>
                                    <th class="text-center">100%</th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Détail par Article (Top 10) -->
    @if(isset($topArticles) && count($topArticles) > 0)
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-trophy text-warning"></i> Top 10 Articles par Valeur de Stock
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-stock table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Article</th>
                                    <th class="text-center">Méthode</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix Unitaire (Ar)</th>
                                    <th class="text-end pe-3">Valeur Totale (Ar)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topArticles as $index => $item)
                                    <tr>
                                        <td class="ps-3">
                                            @if($index < 3)
                                                <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'danger') }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="text-muted">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item['article']->nom }}</strong>
                                            <br><small class="text-muted">{{ $item['article']->id_article }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-methode bg-{{ $item['methode'] == 'CMUP' ? 'primary' : ($item['methode'] == 'FIFO' ? 'success' : 'warning') }}">
                                                {{ $item['methode'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($item['quantite'], 0) }} 
                                            <small class="text-muted">{{ $item['article']->unite->abreviation ?? '' }}</small>
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item['prix_unitaire'], 0, ',', ' ') }}
                                        </td>
                                        <td class="text-end pe-3">
                                            <strong class="text-primary">{{ number_format($item['valeur'], 0, ',', ' ') }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colors = {
        primary: '#0d6efd',
        success: '#198754',
        warning: '#ffc107',
        danger: '#dc3545',
        info: '#0dcaf0',
        secondary: '#6c757d',
        purple: '#6f42c1',
        pink: '#d63384',
        orange: '#fd7e14',
        teal: '#20c997'
    };

    const colorArray = [
        colors.primary, colors.success, colors.warning, colors.info, 
        colors.danger, colors.purple, colors.pink, colors.orange, 
        colors.teal, colors.secondary
    ];

    // 1. Graphique Valeur du Stock par Entité (Doughnut)
    const entiteData = @json($stockParEntite);
    const entiteCtx = document.getElementById('stockEntiteChart').getContext('2d');
    
    new Chart(entiteCtx, {
        type: 'doughnut',
        data: {
            labels: entiteData.map(d => d.entite?.nom || 'Sans entité'),
            datasets: [{
                data: entiteData.map(d => d.valeur),
                backgroundColor: colorArray.slice(0, entiteData.length),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return context.label + ': ' + value.toLocaleString('fr-FR') + ' Ar (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // 2. Graphique Valeur du Stock par Magasin (Bar horizontal)
    const magasinData = @json($stockParMagasin);
    const magasinCtx = document.getElementById('stockMagasinChart').getContext('2d');
    
    new Chart(magasinCtx, {
        type: 'bar',
        data: {
            labels: magasinData.map(d => d.magasin?.nom || 'Magasin'),
            datasets: [{
                label: 'Valeur Stock (Ar)',
                data: magasinData.map(d => d.valeur),
                backgroundColor: colorArray,
                borderRadius: 5,
                barThickness: 25
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw || 0;
                            return 'Valeur: ' + value.toLocaleString('fr-FR') + ' Ar';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'M Ar';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'K Ar';
                            }
                            return value.toLocaleString('fr-FR') + ' Ar';
                        }
                    },
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endpush
