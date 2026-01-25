@extends('layouts.app')

@section('title', 'Liste des Proformas Client')

@section('header-buttons')
    <a href="{{ route('proforma-client.create') }}" class="btn btn-primary">
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
            <form method="GET" action="{{ route('proforma-client.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-2">
                    <label for="client" class="form-label">Client</label>
                    <select class="form-select form-select-sm" id="client" name="client">
                        <option value="">-- Tous --</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id_client }}"
                                {{ request('client') == $client->id_client ? 'selected' : '' }}>
                                {{ $client->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label for="id_magasin" class="form-label">Magasin</label>
                    <select class="form-select form-select-sm" id="id_magasin" name="id_magasin">
                        <option value="">-- Tous --</option>
                        @foreach ($magasins as $magasin)
                            <option value="{{ $magasin->id_magasin }}"
                                {{ request('id_magasin') == $magasin->id_magasin ? 'selected' : '' }}>
                                {{ $magasin->nom }}
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
                        <option value="">-- Tous --</option>
                        <option value="1" {{ request('etat') == '1' ? 'selected' : '' }}>Créée</option>
                        <option value="11" {{ request('etat') == '11' ? 'selected' : '' }}>Validée</option>
                        <option value="0" {{ request('etat') == '0' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('proforma-client.list') }}" class="btn btn-secondary btn-sm"
                            title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Proformas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-list me-2"></i>Proformas Client ({{ $proformas->total() }} total)
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
                                <th>Magasin</th>
                                <th>Client</th>
                                <th>Description</th>
                                <th>État</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proformas as $proforma)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $proforma->id_proforma_client }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $proforma->date_->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        @if($proforma->magasin)
                                            <span class="fw-bold">{{ $proforma->nom_magasin }}</span><br>
                                            <small class="text-muted">{{ $proforma->site_magasin }}</small>
                                        @else
                                            <small class="text-muted">N/A</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $proforma->client->nom }}</span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($proforma->description, 30) }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $badge = 'secondary';
                                            $label = 'Inconnu';
                                            if($proforma->etat == 1) { $badge = 'primary'; $label = 'Créée'; }
                                            elseif($proforma->etat == 11) { $badge = 'success'; $label = 'Validée'; }
                                            elseif($proforma->etat == 0) { $badge = 'danger'; $label = 'Annulée'; }
                                        @endphp
                                        <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('proforma-client.show', $proforma->id_proforma_client) }}"
                                                class="btn btn-info" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('proforma-client.exportPdf', $proforma->id_proforma_client) }}"
                                                class="btn btn-success" title="Exporter PDF" target="_blank">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                            @if ($proforma->etat == 1)
                                                <a href="{{ route('proforma-client.edit', $proforma->id_proforma_client) }}"
                                                    class="btn btn-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-bs-url="{{ route('proforma-client.destroy', $proforma->id_proforma_client) }}"
                                                data-bs-item="la proforma {{ $proforma->id_proforma_client }}"
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

                <!-- Pagination -->
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Affichage de {{ $proformas->firstItem() }} à {{ $proformas->lastItem() }} sur
                        {{ $proformas->total() }} proformas
                    </small>
                    <nav aria-label="pagination">
                        {{ $proformas->links() }}
                    </nav>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucune proforma trouvée</p>
                    <a href="{{ route('proforma-client.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>Créer une proforma
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
