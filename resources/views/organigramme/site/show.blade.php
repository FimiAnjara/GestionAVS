@extends('layouts.app')

@section('title', 'Détails du Site')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('site.edit', $site->id_site) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Modifier
        </a>
        <a href="{{ route('site.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted">ID</th>
                            <td>{{ $site->id_site }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Localisation</th>
                            <td><strong>{{ $site->localisation }}</strong></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Entité</th>
                            <td>
                                @if($site->entite)
                                    <a href="{{ route('entite.show', $site->entite->id_entite) }}">
                                        <span class="badge" style="background-color: {{ $site->entite->code_couleur ?? '#6c757d' }}">
                                            {{ $site->entite->nom }}
                                        </span>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Groupe</th>
                            <td>
                                @if($site->entite && $site->entite->groupe)
                                    <a href="{{ route('groupe.show', $site->entite->groupe->id_groupe) }}">
                                        {{ $site->entite->groupe->nom }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Créé le</th>
                            <td>{{ $site->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-shop-window me-2"></i>Magasins du site ({{ $site->magasins->count() }})
                    </h6>
                    <a href="{{ route('magasin.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus"></i> Ajouter
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($site->magasins->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Coordonnées GPS</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($site->magasins as $magasin)
                                        <tr>
                                            <td>
                                                <strong>{{ $magasin->nom }}</strong><br>
                                                <small class="text-muted">{{ $magasin->id_magasin }}</small>
                                            </td>
                                            <td>
                                                <small>
                                                    <i class="bi bi-geo-alt"></i>
                                                    {{ number_format($magasin->latitude, 4) }}, {{ number_format($magasin->longitude, 4) }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('magasin.show', $magasin->id_magasin) }}" 
                                                    class="btn btn-info btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-6 text-muted"></i>
                            <p class="text-muted mt-2">Aucun magasin sur ce site</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
