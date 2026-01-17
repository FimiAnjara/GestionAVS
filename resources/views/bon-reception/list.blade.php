@extends('layouts.app')

@section('title', 'Liste des Bons de Réception')

@section('header-buttons')
    <a href="{{ route('bon-reception.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nouveau Bon
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
            <form method="GET" action="{{ route('bon-reception.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-2">
                    <label for="id" class="form-label">ID</label>
                    <input type="text" class="form-control form-control-sm" id="id" name="id"
                        placeholder="BR_..." value="{{ request('id') }}">
                </div>

                <div class="col-lg-2">
                    <label for="date_from" class="form-label">De</label>
                    <input type="date" class="form-control form-control-sm" id="date_from" name="date_from"
                        value="{{ request('date_from') }}">
                </div>

                <div class="col-lg-2">
                    <label for="date_to" class="form-label">À</label>
                    <input type="date" class="form-control form-control-sm" id="date_to" name="date_to"
                        value="{{ request('date_to') }}">
                </div>

                <div class="col-lg-2">
                    <label for="etat" class="form-label">État</label>
                    <select class="form-select form-select-sm" id="etat" name="etat">
                        <option value="">-- Tous --</option>
                        <option value="1" {{ request('etat') == '1' ? 'selected' : '' }}>Créée</option>
                        <option value="11" {{ request('etat') == '11' ? 'selected' : '' }}>Réceptionnée</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </button>
                        <a href="{{ route('bon-reception.list') }}" class="btn btn-secondary btn-sm" title="Réinitialiser les filtres">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Bons -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-list me-2"></i>Bons de Réception ({{ $bonReceptions->total() }} total)
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($bonReceptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Bon Commande</th>
                                <th>État</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bonReceptions as $br)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $br->id_bonReception }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $br->date_->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $br->bonCommande->id_bonCommande }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $br->etat_badge }}">{{ $br->etat_label }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('bon-reception.show', $br->id_bonReception) }}"
                                                class="btn btn-info" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('bon-reception.exportPdf', $br->id_bonReception) }}"
                                                class="btn btn-success" title="Exporter PDF" target="_blank">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $br->id_bonReception }}"
                                                title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Suppression -->
                                <div class="modal fade" id="deleteModal{{ $br->id_bonReception }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer ce bon de réception ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('bon-reception.destroy', $br->id_bonReception) }}" method="POST" style="display:inline;">
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

                <!-- Pagination -->
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Affichage de {{ $bonReceptions->firstItem() }} à {{ $bonReceptions->lastItem() }} sur {{ $bonReceptions->total() }} bons
                    </small>
                    <nav aria-label="pagination">
                        {{ $bonReceptions->links() }}
                    </nav>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucun bon de réception trouvé</p>
                    <a href="{{ route('bon-reception.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>Créer un bon
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
