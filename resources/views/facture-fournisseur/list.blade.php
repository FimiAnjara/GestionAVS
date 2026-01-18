@extends('app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3"><i class="bi bi-receipt"></i> Factures Fournisseur</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('facture-fournisseur.createFromBonCommande') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Nouvelle Facture
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('facture-fournisseur.list') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">ID Facture</label>
                    <input type="text" name="id" class="form-control" value="{{ request('id') }}" placeholder="FACT_...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Du</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Au</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">État</label>
                    <select name="etat" class="form-select">
                        <option value="">Tous</option>
                        <option value="1" @if(request('etat') == '1') selected @endif>Créée</option>
                        <option value="5" @if(request('etat') == '5') selected @endif>Validée Finance</option>
                        <option value="11" @if(request('etat') == '11') selected @endif>Validée DG</option>
                        <option value="0" @if(request('etat') == '0') selected @endif>Annulée</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-secondary flex-grow-1">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                    <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($factures->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID Facture</th>
                    <th>Date</th>
                    <th>Bon Commande</th>
                    <th>État</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($factures as $facture)
                <tr>
                    <td>
                        <strong>{{ $facture->id_factureFournisseur }}</strong>
                    </td>
                    <td>{{ $facture->date_->format('d/m/Y') }}</td>
                    <td>
                        @if($facture->bonCommande)
                            <a href="{{ route('bon-commande.show', $facture->bonCommande->id_bonCommande) }}">
                                {{ $facture->bonCommande->id_bonCommande }}
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $facture->etat_badge }}">{{ $facture->etat_label }}</span>
                    </td>
                    <td>{{ Str::limit($facture->description, 50) }}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('facture-fournisseur.show', $facture->id_factureFournisseur) }}" 
                               class="btn btn-outline-primary" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('facture-fournisseur.exportPdf', $facture->id_factureFournisseur) }}" 
                               class="btn btn-outline-secondary" title="PDF">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            @if($facture->etat != 0)
                            <button type="button" class="btn btn-outline-danger" 
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $facture->id_factureFournisseur }}"
                                    title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $factures->links() }}
    </div>

    @else
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> Aucune facture fournisseur
    </div>
    @endif
</div>

<!-- Modals de suppression -->
@foreach($factures as $facture)
<div class="modal fade" id="deleteModal{{ $facture->id_factureFournisseur }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer la facture <strong>{{ $facture->id_factureFournisseur }}</strong> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('facture-fournisseur.destroy', $facture->id_factureFournisseur) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
