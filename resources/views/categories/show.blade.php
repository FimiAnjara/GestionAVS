@extends('layouts.app')

@section('title', 'Détails de la Catégorie')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-tag"></i> Détails de la Catégorie</h2>
        </div>
        <div>
            <a href="{{ route('categories.edit', $categorie->id_categorie) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('categories.list') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-tag"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <p><label class="text-muted small">ID</label><p class="fw-bold">{{ $categorie->id_categorie }}</p></p>
                    <p><label class="text-muted small">Libellé</label><p class="fw-bold">{{ $categorie->libelle }}</p></p>
                    <p><label class="text-muted small">Créé le</label><p>{{ $categorie->created_at->format('d/m/Y H:i') }}</p></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-exclamation-circle"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-danger w-100" onclick="if(confirm('Supprimer ?')) { fetch('{{ route('categories.destroy', $categorie->id_categorie) }}', {method: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(r => r.json()).then(d => { alert(d.message); location.href='{{ route('categories.list') }}'; }); }">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
