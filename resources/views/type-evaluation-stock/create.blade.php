@extends('layouts.app')

@section('title', 'Ajouter un Type d\'Évaluation')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <p class="text-muted">Définissez une nouvelle méthode de valorisation</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Informations du Type</h5>
                </div>
                <div class="card-body">
                    <form id="typeForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_type_evaluation_stock" class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="id_type_evaluation_stock" name="id_type_evaluation_stock" placeholder="ex: CMUP, FIFO..." required>
                                <small class="text-muted">Identifiant unique (max 50 caractères)</small>
                            </div>
                            <div class="col-md-6">
                                <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="libelle" name="libelle" placeholder="ex: Coût Moyen..." required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Détails sur le fonctionnement de cette méthode..."></textarea>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg me-2">
                                <i class="bi bi-check-circle"></i> Ajouter
                            </button>
                            <a href="{{ route('type-evaluation-stock.list') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('typeForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('{{ route("type-evaluation-stock.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.href = '{{ route("type-evaluation-stock.list") }}';
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
