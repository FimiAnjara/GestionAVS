@extends('layouts.app')

@section('title', 'Détails de la Caisse')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('caisse.edit', $caisse->id_caisse) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Modifier
        </a>
        <a href="{{ route('caisse.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-safe2 me-2"></i>Informations de la Caisse
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>ID Caisse:</strong>
                        </div>
                        <div class="col-sm-7">
                            <span class="badge bg-primary">{{ $caisse->id_caisse }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>Libellé:</strong>
                        </div>
                        <div class="col-sm-7">
                            <span class="badge bg-info text-dark fs-6">{{ $caisse->libelle }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>Montant actuel:</strong>
                        </div>
                        <div class="col-sm-7">
                            <span class="badge bg-success fs-6">{{ number_format($caisse->montant, 2, ',', ' ') }} Ar</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>Créée le:</strong>
                        </div>
                        <div class="col-sm-7">
                            <small class="text-muted">{{ $caisse->created_at->format('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <strong>Modifiée le:</strong>
                        </div>
                        <div class="col-sm-7">
                            <small class="text-muted">{{ $caisse->updated_at->format('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mouvements récents -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="bi bi-arrow-left-right me-2"></i>Mouvements de cette caisse
            </h5>
        </div>
        <div class="card-body">
            @if($mouvements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Origine</th>
                                <th>Description</th>
                                <th class="text-end">Débit</th>
                                <th class="text-end">Crédit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mouvements as $mvt)
                                <tr>
                                    <td>{{ $mvt->date_->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $mvt->origine }}</span>
                                    </td>
                                    <td>{{ $mvt->description }}</td>
                                    <td class="text-end">
                                        @if($mvt->debit > 0)
                                            <span class="badge bg-danger">{{ number_format($mvt->debit, 2, ',', ' ') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($mvt->credit > 0)
                                            <span class="badge bg-success">{{ number_format($mvt->credit, 2, ',', ' ') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $mouvements->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucun mouvement pour cette caisse</p>
                </div>
            @endif
        </div>
    </div>
@endsection
