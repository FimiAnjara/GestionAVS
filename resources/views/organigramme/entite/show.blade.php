@extends('layouts.app')

@section('title', 'Détails de l\'Entité')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('entite.edit', $entite->id_entite) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Modifier
        </a>
        <a href="{{ route('entite.list') }}" class="btn btn-secondary">
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
                    @if ($entite->code_couleur)
                        <div class="text-center mb-3">
                            <div
                                style="width: 60px; height: 60px; border-radius: 50%; background-color: {{ $entite->code_couleur }}; margin: 0 auto;">
                            </div>
                        </div>
                    @endif
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted">ID</th>
                            <td>{{ $entite->id_entite }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nom</th>
                            <td><strong>{{ $entite->nom }}</strong></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Groupe</th>
                            <td>
                                <a href="{{ route('groupe.show', $entite->groupe->id_groupe) }}">
                                    {{ $entite->groupe->nom }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Description</th>
                            <td>{{ $entite->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Créé le</th>
                            <td>{{ $entite->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-geo-alt me-2"></i>Sites de l'entité ({{ $entite->sites->count() }})
                    </h6>
                    <a href="{{ route('site.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus"></i> Ajouter
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($entite->sites->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Localisation</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($entite->sites as $site)
                                        <tr>
                                            <td>
                                                <strong>{{ $site->localisation }}</strong><br>
                                                <small class="text-muted">{{ $site->id_site }}</small>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('site.show', $site->id_site) }}"
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
                            <p class="text-muted mt-2">Aucun site pour cette entité</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
