@extends('layouts.app')

@section('title', 'Liste des Groupes')

@section('header-buttons')
    <a href="{{ route('groupe.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nouveau Groupe
    </a>
@endsection

@section('content')
    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtres et Recherche
            </h6>
        </div>
        <div class="card-body p-3">
            <form method="GET" action="{{ route('groupe.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-4">
                    <label for="nom" class="form-label">Nom du Groupe</label>
                    <input type="text" class="form-control form-control-sm" id="nom" name="nom"
                        placeholder="Chercher par nom..." value="{{ request('nom') }}">
                </div>
                <div class="col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </button>
                        <a href="{{ route('groupe.list') }}" class="btn btn-secondary btn-sm" title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Groupes -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-diagram-3 me-2"></i>Tous les Groupes ({{ $groupes->total() }} total)
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($groupes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th class="text-center">Nb Entités</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupes as $groupe)
                                <tr>
                                    <td>
                                        <small class="text-muted">{{ $groupe->id_groupe }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $groupe->nom }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $groupe->entites_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('groupe.show', $groupe->id_groupe) }}"
                                                class="btn btn-info" title="Voir détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('groupe.edit', $groupe->id_groupe) }}"
                                                class="btn btn-warning" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-bs-url="{{ route('groupe.destroy', $groupe->id_groupe) }}"
                                                data-bs-item="le groupe {{ $groupe->nom }}"
                                                title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    {{ $groupes->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <p class="text-muted mt-2">Aucun groupe trouvé</p>
                    <a href="{{ route('groupe.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Créer un groupe
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
