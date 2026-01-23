@extends('layouts.app')

@section('title', 'Liste des Sites')

@section('header-buttons')
    <a href="{{ route('site.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nouveau Site
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
            <form method="GET" action="{{ route('site.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-3">
                    <label for="localisation" class="form-label">Localisation</label>
                    <input type="text" class="form-control form-control-sm" id="localisation" name="localisation"
                        placeholder="Chercher..." value="{{ request('localisation') }}">
                </div>
                <div class="col-lg-3">
                    <label for="entite" class="form-label">Entité</label>
                    <select class="form-select form-select-sm" id="entite" name="entite">
                        <option value="">Toutes les entités</option>
                        @foreach ($entites as $entite)
                            <option value="{{ $entite->id_entite }}" {{ request('entite') == $entite->id_entite ? 'selected' : '' }}>
                                {{ $entite->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </button>
                        <a href="{{ route('site.list') }}" class="btn btn-secondary btn-sm" title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Sites -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-geo-alt me-2"></i>Tous les Sites ({{ $sites->total() }} total)
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($sites->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Localisation</th>
                                <th>Entité</th>
                                <th>Groupe</th>
                                <th class="text-center">Nb Magasins</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sites as $site)
                                <tr>
                                    <td>
                                        <strong>{{ $site->localisation }}</strong><br>
                                        <small class="text-muted">{{ $site->id_site }}</small>
                                    </td>
                                    <td>
                                        @if($site->entite)
                                            <span class="badge" style="background-color: {{ $site->entite->code_couleur ?? '#6c757d' }}">
                                                {{ $site->entite->nom }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $site->entite->groupe->nom ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $site->magasins_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('site.show', $site->id_site) }}"
                                                class="btn btn-info" title="Voir détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('site.edit', $site->id_site) }}"
                                                class="btn btn-warning" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $site->id_site }}" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Suppression -->
                                <div class="modal fade" id="deleteModal{{ $site->id_site }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir supprimer le site <strong>{{ $site->localisation }}</strong> ?</p>
                                                <p class="text-danger"><small>Cette action est irréversible.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('site.destroy', $site->id_site) }}" method="POST" class="d-inline">
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
                    {{ $sites->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <p class="text-muted mt-2">Aucun site trouvé</p>
                    <a href="{{ route('site.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Créer un site
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
