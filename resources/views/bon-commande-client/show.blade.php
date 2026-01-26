@extends('layouts.app')

@section('title', 'Détails Bon de Commande Client')

@section('header-buttons')
    <a href="{{ route('bon-commande-client.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-lg-8">
            <!-- Informations générales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark me-2"></i>Informations Générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label text-muted">ID</label>
                            <p class="fw-bold text-primary">{{ $bonCommande->id_bon_commande_client }}</p>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label text-muted">Date</label>
                            <p class="fw-bold">{{ $bonCommande->date_->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label text-muted">Client</label>
                            <p class="fw-bold">{{ $bonCommande->client->nom }}</p>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label text-muted">Proforma</label>
                            <p class="fw-bold">
                                @if($bonCommande->id_proforma_client)
                                    <a href="{{ route('proforma-client.show', $bonCommande->id_proforma_client) }}" class="text-decoration-none">
                                        {{ $bonCommande->id_proforma_client }}
                                    </a>
                                @else
                                    <span class="text-muted italic">Aucune</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">État</label>
                        <p class="fw-bold">
                            <span class="badge bg-{{ $bonCommande->etat == 1 ? 'warning' : 'success' }} fs-6">
                                {{ $bonCommande->etat == 1 ? 'Créée' : 'Validée' }}
                            </span>
                        </p>
                    </div>
                    @if($bonCommande->magasin)
                    <div class="mb-3">
                        <label class="form-label text-muted">Magasin Source</label>
                        <p class="fw-bold">
                            {{ $bonCommande->magasin->nom }}<br>
                            <small class="text-muted fw-normal">{{ $bonCommande->magasin->site?->localisation }}</small>
                        </p>
                    </div>
                    @endif
                    @if ($bonCommande->description)
                        <div class="mb-0">
                            <label class="form-label text-muted">Description</label>
                            <p>{{ $bonCommande->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Articles -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-box me-2"></i>Articles ({{ $bonCommande->bonCommandeClientFille->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if ($bonCommande->bonCommandeClientFille->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">Photo</th>
                                        <th>Article</th>
                                        <th class="text-end">Quantité</th>
                                        <th class="text-end">Prix Unitaire (Ar)</th>
                                        <th class="text-end">Montant (Ar)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach ($bonCommande->bonCommandeClientFille as $item)
                                        @php $montant = $item->quantite * $item->prix; $total += $montant; @endphp
                                        <tr>
                                            <td class="text-center">
                                                @if($item->article->photo)
                                                    <img src="{{ asset('storage/' . $item->article->photo) }}" 
                                                         class="rounded shadow-sm" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $item->article->nom }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->id_article }}</small>
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($item->quantite, 2, ',', ' ') }}
                                                <span class="badge bg-success ms-1">{{ $item->article->unite->libelle ?? '-' }}</span>
                                            </td>
                                            <td class="text-end">{{ number_format($item->prix, 2, ',', ' ') }}</td>
                                            <td class="text-end fw-bold">{{ number_format($montant, 2, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">TOTAL</th>
                                        <th class="text-end fw-bold">{{ number_format($total, 2, ',', ' ') }} Ar</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Aucun article</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <!-- Exporter PDF -->
                        <a href="{{ route('bon-commande-client.exportPdf', $bonCommande->id_bon_commande_client) }}" 
                            class="btn btn-success" target="_blank">
                            <i class="bi bi-file-pdf me-2"></i>Exporter PDF
                        </a>

                        <!-- Valider (si état = 1) -->
                        @if ($bonCommande->etat == 1)
                            <form action="{{ route('bon-commande-client.etat', $bonCommande->id_bon_commande_client) }}" method="POST" class="d-grid">
                                @csrf
                                <input type="hidden" name="etat" value="11">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Valider le Bon
                                </button>
                            </form>
                        @endif

                        @if ($bonCommande->etat == 11)
                            <a href="{{ route('facture-client.create', ['bon_commande_id' => $bonCommande->id_bon_commande_client]) }}" 
                                class="btn btn-primary">
                                <i class="bi bi-receipt me-2"></i>Créer Facture
                            </a>
                            <a href="{{ route('bon-livraison-client.create', ['bon_commande_id' => $bonCommande->id_bon_commande_client]) }}" 
                                class="btn btn-warning">
                                <i class="bi bi-truck me-2"></i>Créer Bon de Livraison
                            </a>
                        @endif

                        <!-- Supprimer -->
                        <button type="button" class="btn btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteConfirmModal"
                            data-bs-url="{{ route('bon-commande-client.destroy', $bonCommande->id_bon_commande_client) }}"
                            data-bs-item="le bon de commande client {{ $bonCommande->id_bon_commande_client }}"
                            title="Supprimer">
                            <i class="bi bi-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
