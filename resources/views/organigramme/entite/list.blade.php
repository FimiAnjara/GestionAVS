@extends('layouts.app')

@section('title', 'Liste des Entités')

@section('header-buttons')
    <a href="{{ route('entite.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nouvelle Entité
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
            <form method="GET" action="{{ route('entite.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control form-control-sm" id="nom" name="nom"
                        placeholder="Chercher par nom..." value="{{ request('nom') }}">
                </div>
                <div class="col-lg-3">
                    <label for="groupe" class="form-label">Groupe</label>
                    <select class="form-select form-select-sm" id="groupe" name="groupe">
                        <option value="">Tous les groupes</option>
                        @foreach ($groupes as $groupe)
                            <option value="{{ $groupe->id_groupe }}" {{ request('groupe') == $groupe->id_groupe ? 'selected' : '' }}>
                                {{ $groupe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </button>
                        <a href="{{ route('entite.list') }}" class="btn btn-secondary btn-sm" title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Entités -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-building me-2"></i>Toutes les Entités ({{ $entites->total() }} total)
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($entites->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Groupe</th>
                                <th>Description</th>
                                <th class="text-center">Nb Sites</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($entites as $entite)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($entite->code_couleur)
                                                <span class="me-2" style="width: 12px; height: 12px; border-radius: 50%; background-color: {{ $entite->code_couleur }}; display: inline-block;"></span>
                                            @endif
                                            <div>
                                                <strong>{{ $entite->nom }}</strong><br>
                                                <small class="text-muted">{{ $entite->id_entite }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $entite->groupe->nom ?? '-' }}</span>
                                    </td>
                                    <td>{{ $entite->description ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $entite->sites_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('entite.show', $entite->id_entite) }}"
                                                class="btn btn-info" title="Voir détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('entite.edit', $entite->id_entite) }}"
                                                class="btn btn-warning" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $entite->id_entite }}" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Suppression -->
                                <div class="modal fade" id="deleteModal{{ $entite->id_entite }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir supprimer l'entité <strong>{{ $entite->nom }}</strong> ?</p>
                                                <p class="text-danger"><small>Cette action est irréversible.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('entite.destroy', $entite->id_entite) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    {{ $entites->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <p class="text-muted mt-2">Aucune entité trouvée</p>
                    <a href="{{ route('entite.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Créer une entité
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
