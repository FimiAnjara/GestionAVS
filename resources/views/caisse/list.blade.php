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
                                <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteConfirmModal"
                                    data-bs-url="{{ route('caisse.destroy', $caisse->id_caisse) }}"
                                    data-bs-item="la caisse {{ $caisse->libelle }}"
                                    title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
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
