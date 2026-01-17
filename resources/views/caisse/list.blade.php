@extends('layouts.app')

@section('title', 'Liste des Caisses')

@section('header-buttons')
    <a href="{{ route('caisse.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Ajouter une caisse
    </a>
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID Caisse</th>
                    <th>Libellé</th>
                    <th>Montant</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($caisses as $caisse)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $caisse->id_caisse }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $caisse->libelle }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ number_format($caisse->montant, 2, ',', ' ') }} Ar</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $caisse->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('caisse.show', $caisse->id_caisse) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('caisse.edit', $caisse->id_caisse) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $caisse->id_caisse }}" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Modal de suppression -->
                            <div class="modal fade" id="deleteModal{{ $caisse->id_caisse }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer cette caisse ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="{{ route('caisse.destroy', $caisse->id_caisse) }}" method="POST" style="display: inline;">
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
                        <td colspan="4" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            Aucune caisse trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $caisses->links('pagination::bootstrap-4') }}
    </div>
@endsection
