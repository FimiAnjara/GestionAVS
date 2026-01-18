@extends('layouts.app')

@section('title', 'État du Stock')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('mvt-stock.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour aux mouvements
        </a>
    </div>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 py-3">
        <h5 class="mb-0">
            <i class="bi bi-boxes me-2" style="color: #0056b3;"></i>
            État du Stock par Magasin
        </h5>
    </div>
    <div class="card-body">
        @if($etatStock && count($etatStock) > 0)
            <ul class="nav nav-tabs mb-4" role="tablist">
                @foreach($etatStock as $key => $data)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif" 
                                id="tab-{{ $data['magasin']->id_magasin }}" 
                                data-bs-toggle="tab" 
                                data-bs-target="#content-{{ $data['magasin']->id_magasin }}" 
                                type="button" role="tab">
                            <i class="bi bi-building me-1"></i>
                            {{ $data['magasin']->nom?? $data['magasin']->designation }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($etatStock as $key => $data)
                    <div class="tab-pane fade @if($loop->first) show active @endif" 
                         id="content-{{ $data['magasin']->id_magasin }}" 
                         role="tabpanel">
                        
                        @if(count($data['articles']) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Article</th>
                                            <th class="text-center" width="15%">
                                                <i class="bi bi-plus-circle text-success me-1"></i>Entrées
                                            </th>
                                            <th class="text-center" width="15%">
                                                <i class="bi bi-dash-circle text-danger me-1"></i>Sorties
                                            </th>
                                            <th class="text-center" width="15%">
                                                <strong>Stock Restant</strong>
                                            </th>
                                            <th class="text-center" width="10%">État</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['articles'] as $article)
                                            @php
                                                $quantiteRestante = $article['quantite_restante'];
                                                $badge = 'bg-success';
                                                if ($quantiteRestante == 0) {
                                                    $badge = 'bg-danger';
                                                } elseif ($quantiteRestante < 10) {
                                                    $badge = 'bg-warning';
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{ $article['id_article'] }}</strong><br>
                                                    <small class="text-muted">{{ $article['designation'] }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success">{{ number_format($article['entree'], 0) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger">{{ number_format($article['sortie'], 0) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <strong class="fs-5">{{ number_format($quantiteRestante, 0) }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    @if($quantiteRestante > 10)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>OK
                                                        </span>
                                                    @elseif($quantiteRestante > 0)
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="bi bi-exclamation-circle me-1"></i>Faible
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-x-circle me-1"></i>Rupture
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <th colspan="5">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Total articles en stock: <strong>{{ count($data['articles']) }}</strong>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Aucun mouvement de stock pour ce magasin
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Aucun magasin ou mouvement de stock disponible
            </div>
        @endif
    </div>
</div>
@endsection
