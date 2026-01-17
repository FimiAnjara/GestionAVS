@extends('layouts.app')

@section('title', 'Détails du Client')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-person-check"></i> Détails du Client</h2>
            <p class="text-muted">Informations complètes</p>
        </div>
        <div>
            <a href="{{ route('clients.edit', $client->id_client) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('clients.list') }}" class="btn btn-secondary">
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
                        <i class="bi bi-person"></i> Informations Client
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">ID Client</label>
                            <p class="fw-bold">{{ $client->id_client }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nom</label>
                            <p class="fw-bold">{{ $client->nom }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small">Créé le</label>
                            <p>{{ $client->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Modifié le</label>
                            <p>{{ $client->updated_at->format('d/m/Y à H:i') }}</p>
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
                        <i class="bi bi-trash"></i> Supprimer ce client
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
        if (confirm('Êtes-vous absolument sûr de vouloir supprimer ce client ?')) {
            fetch('{{ route("clients.destroy", $client->id_client) }}', {
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
                    window.location.href = '{{ route("clients.list") }}';
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
