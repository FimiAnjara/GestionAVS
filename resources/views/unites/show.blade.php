@extends('layouts.app')

@section('title', 'Détails de l\'Unité')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-rulers"></i> Détails de l'Unité</h2>
        </div>
        <div>
            <a href="{{ route('unites.edit', $unite->id_unite) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('unites.list') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-rulers"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <p><label class="text-muted small">ID</label><p class="fw-bold">{{ $unite->id_unite }}</p></p>
                    <p><label class="text-muted small">Libellé</label><p class="fw-bold">{{ $unite->libelle }}</p></p>
                    <p><label class="text-muted small">Créé le</label><p>{{ $unite->created_at->format('d/m/Y H:i') }}</p></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-exclamation-circle"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-danger w-100" onclick="if(confirm('Supprimer ?')) { fetch('{{ route('unites.destroy', $unite->id_unite) }}', {method: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(r => r.json()).then(d => { alert(d.message); location.href='{{ route('unites.list') }}'; }); }">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
