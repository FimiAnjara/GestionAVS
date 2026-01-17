@extends('layouts.app')

@section('title', 'Liste des Proformas Fournisseur')

@section('header-buttons')
    <a href="{{ route('proforma-fournisseur.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nouvelle Proforma
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
            <form method="GET" action="{{ route('proforma-fournisseur.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-3">
                    <label for="fournisseur" class="form-label">Fournisseur</label>
                    <select class="form-select form-select-sm" id="fournisseur" name="fournisseur">
                        <option value="">-- Tous les fournisseurs --</option>
                        @foreach ($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id_fournisseur }}"
                                {{ request('fournisseur') == $fournisseur->id_fournisseur ? 'selected' : '' }}>
                                {{ $fournisseur->nom }}
                            </option>
                        @endforeach
                    </select>
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
                        <option value="">-- Tous les états --</option>
                        <option value="1" {{ request('etat') == '1' ? 'selected' : '' }}>Créée</option>
                        <option value="5" {{ request('etat') == '5' ? 'selected' : '' }}>Validée par Finance</option>
                        <option value="11" {{ request('etat') == '11' ? 'selected' : '' }}>Validée par DG</option>
                        <option value="0" {{ request('etat') == '0' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <div class="col-lg-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search me-2"></i>Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Proformas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-list me-2"></i>Proformas ({{ $proformas->total() }} total)
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($proformas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Fournisseur</th>
                                <th>Description</th>
                                <th>État</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proformas as $proforma)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $proforma->id_proformaFournisseur }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $proforma->date_->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $proforma->fournisseur->nom }}</span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($proforma->description, 40) }}</small>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $proforma->etat_badge }}">{{ $proforma->etat_label }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('proforma-fournisseur.show', $proforma->id_proformaFournisseur) }}"
                                                class="btn btn-info" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('proforma-fournisseur.exportPdf', $proforma->id_proformaFournisseur) }}"
                                                class="btn btn-success" title="Exporter PDF" target="_blank">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                            @if ($proforma->etat == 1)
                                                <a href="{{ route('proforma-fournisseur.edit', $proforma->id_proformaFournisseur) }}"
                                                    class="btn btn-warning  " title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                {{-- <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#changeEtatModal{{ $proforma->id_proformaFournisseur }}"
                                                    title="Changer l'état">
                                                    <i class="bi bi-pencil"></i>
                                                </button> --}}
                                            @endif
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $proforma->id_proformaFournisseur }}"
                                                title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Changer État -->
                                <div class="modal fade" id="changeEtatModal{{ $proforma->id_proformaFournisseur }}"
                                    tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Changer l'État</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form
                                                action="{{ route('proforma-fournisseur.etat', $proforma->id_proformaFournisseur) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <label for="etat" class="form-label">Nouvel État</label>
                                                    <select class="form-select" name="etat" required>
                                                        @if ($proforma->etat == 1)
                                                            <option value="5">Validée par Finance</option>
                                                            <option value="0">Annulée</option>
                                                        @elseif($proforma->etat == 5)
                                                            <option value="11">Validée par DG</option>
                                                            <option value="1">Retour à Créée</option>
                                                            <option value="0">Annulée</option>
                                                        @elseif($proforma->etat == 11)
                                                            <option value="1">Retour à Créée</option>
                                                            <option value="0">Annulée</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Valider</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Suppression -->
                                <div class="modal fade" id="deleteModal{{ $proforma->id_proformaFournisseur }}"
                                    tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer cette proforma?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Annuler</button>
                                                <form
                                                    action="{{ route('proforma-fournisseur.destroy', $proforma->id_proformaFournisseur) }}"
                                                    method="POST" style="display:inline;">
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
                <div class="p-3 border-top">
                    {{ $proformas->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucune proforma trouvée</p>
                    <a href="{{ route('proforma-fournisseur.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>Créer une proforma
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
