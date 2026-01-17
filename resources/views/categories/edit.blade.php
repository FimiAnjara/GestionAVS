@extends('layouts.app')

@section('title', 'Modifier une Catégorie')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2><i class="bi bi-pencil-square"></i> Modifier la Catégorie</h2>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="bi bi-form-check"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <form id="catForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="libelle" class="form-label"><i class="bi bi-tag"></i> Libellé</label>
                            <input type="text" class="form-control" id="libelle" name="libelle" value="{{ $categorie->libelle }}" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-lg me-2">
                            <i class="bi bi-check-circle"></i> Modifier
                        </button>
                        <a href="{{ route('categories.list') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('catForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        try {
            const response = await fetch('{{ route("categories.update", $categorie->id_categorie) }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'},
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                alert(result.message);
                window.location.href = '{{ route("categories.list") }}';
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    });
</script>
@endsection
