@extends('layouts.app')

@section('title', 'Modifier une Unité')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2><i class="bi bi-pencil-square"></i> Modifier l'Unité</h2>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="bi bi-form-check"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <form id="uniForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="libelle" class="form-label"><i class="bi bi-rulers"></i> Libellé</label>
                            <input type="text" class="form-control" id="libelle" name="libelle" value="{{ $unite->libelle }}" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-lg me-2">
                            <i class="bi bi-check-circle"></i> Modifier
                        </button>
                        <a href="{{ route('unites.list') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('uniForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        try {
            const response = await fetch('{{ route("unites.update", $unite->id_unite) }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'},
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                alert(result.message);
                window.location.href = '{{ route("unites.list") }}';
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    });
</script>
@endsection
