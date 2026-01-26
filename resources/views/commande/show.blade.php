@extends('layouts.app')

@section('title', 'Commande ' . $commande->id_commande)

@section('header-buttons')
    <a href="{{ route('commande.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark me-2" style="color: #0056b3;"></i>
                Commande {{ $commande->id_commande }}
            </h5>
            <span class="badge 
                @if($commande->etat == 1) bg-warning text-dark
                @elseif($commande->etat == 2) bg-info
                @elseif($commande->etat == 3) bg-success
                @else bg-secondary
                @endif
            ">
                @if($commande->etat == 1) Créée
                @elseif($commande->etat == 2) Confirmée
                @elseif($commande->etat == 3) Expédiée
                @else Inconnue
                @endif
            </span>
        </div>
    </div>
    <div class="card-body p-4">
        <!-- Informations générales -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-2">Informations Client</h6>
                        <p class="mb-1">
                            <strong>Client:</strong> {{ $commande->client?->nom ?? 'N/A' }}
                        </p>
                        <p class="mb-1">
                            <strong>Contact:</strong> {{ $commande->client?->contact ?? 'N/A' }}
                        </p>
                        <p class="mb-1">
                            <strong>Téléphone:</strong> {{ $commande->client?->telephone ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-2">Informations Commande</h6>
                        <p class="mb-1">
                            <strong>Date:</strong> {{ $commande->date_->format('d/m/Y') }}
                        </p>
                        <p class="mb-1">
                            <strong>Utilisateur:</strong> {{ $commande->utilisateur?->nom_utilisateur ?? 'N/A' }}
                        </p>
                        <p class="mb-0">
                            <strong>ID Commande:</strong> {{ $commande->id_commande }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles -->
        <h6 class="mb-3">
            <i class="bi bi-basket me-2" style="color: #0056b3;"></i>
            Articles Commandés
        </h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th width="5%">Photo</th>
                        <th>Article</th>
                        <th class="text-center" width="12%">Unité</th>
                        <th class="text-center" width="12%">Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commande->commandeFille as $item)
                        <tr>
                            <td class="text-center">
                                @if($item->article?->photo)
                                    <img src="{{ asset('storage/' . $item->article->photo) }}" 
                                        class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item->article?->nom ?? 'N/A' }}</td>
                            <td class="text-center">{{ $item->article?->unite?->libelle ?? 'N/A' }}</td>
                            <td class="text-center">{{ number_format($item->quantite, 2, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="bi bi-inbox"></i> Aucun article
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('commande.list') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
            </a>
            <button class="btn btn-danger" 
                onclick="deleteCommande('{{ $commande->id_commande }}')">
                <i class="bi bi-trash me-2"></i>Supprimer
            </button>
        </div>
    </div>
</div>

<script>
    function deleteCommande(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("commande.destroy", ":id") }}'.replace(':id', id);
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
