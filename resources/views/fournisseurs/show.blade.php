@extends('layouts.app')

@section('title', 'Détails du Fournisseur')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-building"></i> Détails du Fournisseur</h2>
            <p class="text-muted">Informations complètes</p>
        </div>
        <div>
            <a href="{{ route('fournisseurs.edit', $fournisseur->id_fournisseur) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('fournisseurs.list') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Détails -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-building"></i> Informations Fournisseur
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">ID Fournisseur</label>
                            <p class="fw-bold">{{ $fournisseur->id_fournisseur }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nom</label>
                            <p class="fw-bold">{{ $fournisseur->nom }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small"><i class="bi bi-geo-alt"></i> Lieu</label>
                            <p class="fw-bold">{{ $fournisseur->lieux }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small">Créé le</label>
                            <p>{{ $fournisseur->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Modifié le</label>
                            <p>{{ $fournisseur->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte d'actions -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-exclamation-circle"></i> Actions
                    </h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-danger w-100" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i> Supprimer ce fournisseur
                    </button>
                    <p class="small text-muted mt-3">
                        Cette action est irréversible. Soyez prudent.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        if (confirm('Êtes-vous absolument sûr de vouloir supprimer ce fournisseur ?')) {
            fetch('{{ route("fournisseurs.destroy", $fournisseur->id_fournisseur) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = '{{ route("fournisseurs.list") }}';
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
            });
        }
    }
</script>
@endsection
