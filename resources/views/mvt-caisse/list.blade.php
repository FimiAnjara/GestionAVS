@extends('layouts.app')

@section('title', 'Liste des Mouvements de Caisse')

@section('header-buttons')
    <a href="{{ route('mvt-caisse.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Saisir un mouvement
    </a>
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Caisse</th>
                    <th>Origine</th>
                    <th>Description</th>
                    <th class="text-end">Débit</th>
                    <th class="text-end">Crédit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $mvt)
                    <tr>
                        <td>
                            <small>{{ $mvt->date_->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $mvt->caisse->id_caisse }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $mvt->origine }}</span>
                        </td>
                        <td>
                            <small>{{ Str::limit($mvt->description, 50) }}</small>
                        </td>
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
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('mvt-caisse.show', $mvt->id_mvt_caisse) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('mvt-caisse.edit', $mvt->id_mvt_caisse) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $mvt->id_mvt_caisse }}" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Modal de suppression -->
                            <div class="modal fade" id="deleteModal{{ $mvt->id_mvt_caisse }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer ce mouvement ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="{{ route('mvt-caisse.destroy', $mvt->id_mvt_caisse) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            Aucun mouvement trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $mouvements->links('pagination::bootstrap-4') }}
    </div>
@endsection
