@extends('layouts.app')

@section('title', 'Ajouter un Client')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="mb-4">
        <div>
            <h2><i class="bi bi-plus-circle"></i> Ajouter un nouveau Client</h2>
            <p class="text-muted">Remplissez le formulaire ci-dessous</p>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-form-check"></i> Informations du Client
                    </h5>
                </div>
                <div class="card-body">
                    <form id="clientForm" method="POST" action="{{ route('clients.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="bi bi-person"></i> Nom du client <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Nom complet" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg me-2">
                                <i class="bi bi-check-circle"></i> Ajouter
                            </button>
                            <a href="{{ route('clients.list') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations utiles -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Aide
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        <strong>Champs obligatoires:</strong>
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success"></i> Nom du client</li>
                    </ul>
                    <hr>
                    <p class="small text-muted">
                        Un identifiant unique sera généré automatiquement pour ce nouveau client.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('clientForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            nom: formData.get('nom')
        };

        try {
            const response = await fetch('{{ route("clients.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.href = '{{ route("clients.list") }}';
            } else {
                alert('Erreur: ' + result.message);
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        }
    });
</script>
@endsection
