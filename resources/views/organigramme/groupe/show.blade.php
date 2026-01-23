@extends('layouts.app')

@section('title', 'Détails du Groupe')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('groupe.edit', $groupe->id_groupe) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Modifier
        </a>
        <a href="{{ route('groupe.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted">ID</th>
                            <td>{{ $groupe->id_groupe }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nom</th>
                            <td><strong>{{ $groupe->nom }}</strong></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Créé le</th>
                            <td>{{ $groupe->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-building me-2"></i>Entités du groupe ({{ $groupe->entites->count() }})
                    </h6>
                    <a href="{{ route('entite.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus"></i> Ajouter
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($groupe->entites->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Description</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupe->entites as $entite)
                                        <tr>
                                            <td>
                                                @if($entite->code_couleur)
                                                    <span class="badge" style="background-color: {{ $entite->code_couleur }}">
                                                        {{ $entite->nom }}
                                                    </span>
                                                @else
                                                    <strong>{{ $entite->nom }}</strong>
                                                @endif
                                            </td>
                                            <td>{{ $entite->description ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('entite.show', $entite->id_entite) }}" 
                                                    class="btn btn-info btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-6 text-muted"></i>
                            <p class="text-muted mt-2">Aucune entité dans ce groupe</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
