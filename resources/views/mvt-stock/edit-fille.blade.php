@extends('layouts.app')

@section('title', 'Modifier la ligne de mouvement')

@section('header-buttons')
    <a href="{{ route('stock.details') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour aux détails
    </a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Modifier la ligne : <span class="text-primary">{{ $fille->id_mvt_stock_fille }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('mvt-stock.updateFille', $fille->id_mvt_stock_fille) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Infos fixes -->
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Article</label>
                                <div class="form-control-plaintext fw-bold">
                                    {{ $fille->id_article }} - {{ $fille->article->nom }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Mouvement Parent</label>
                                <div class="form-control-plaintext">
                                    <a href="{{ route('mvt-stock.show', $fille->id_mvt_stock) }}" class="text-decoration-none">
                                        {{ $fille->id_mvt_stock }}
                                    </a>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Quantités -->
                            <div class="col-md-4">
                                <label for="entree" class="form-label small">Quantité Entrée</label>
                                <input type="number" step="0.01" name="entree" id="entree" 
                                    class="form-control" value="{{ old('entree', $fille->entree) }}" 
                                    {{ $fille->entree == 0 ? 'readonly bg-light' : '' }}>
                                @if($fille->entree > 0)
                                    <div class="form-text text-info small">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Le "Reste" sera ajusté automatiquement.
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label for="sortie" class="form-label small">Quantité Sortie</label>
                                <input type="number" step="0.01" name="sortie" id="sortie" 
                                    class="form-control" value="{{ old('sortie', $fille->sortie) }}"
                                    {{ $fille->sortie == 0 ? 'readonly bg-light' : '' }}>
                            </div>

                            <div class="col-md-4">
                                <label for="reste" class="form-label small">Reste (Stock disponible)</label>
                                <input type="number" step="0.01" name="reste" id="reste" 
                                    class="form-control" value="{{ old('reste', $fille->reste) }}"
                                    {{ $fille->entree == 0 ? 'readonly bg-light' : '' }}>
                                @if($fille->entree > 0)
                                    <div class="form-text text-muted small">Uniquement pour les lots d'entrée.</div>
                                @endif
                            </div>

                            <!-- Finance & Date -->
                            <div class="col-md-6">
                                <label for="prix_unitaire" class="form-label small">Prix Unitaire (Ar)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="prix_unitaire" id="prix_unitaire" 
                                        class="form-control" value="{{ old('prix_unitaire', $fille->prix_unitaire) }}">
                                    <span class="input-group-text">Ar</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="date_expiration" class="form-label small">Date d'expiration</label>
                                <input type="date" name="date_expiration" id="date_expiration" 
                                    class="form-control" value="{{ old('date_expiration', $fille->date_expiration ? $fille->date_expiration->format('Y-m-d') : '') }}">
                            </div>

                            <div class="col-12 mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('stock.details') }}" class="btn btn-light">Annuler</a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-lg me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-warning mt-4 shadow-sm border-0">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Attention :</strong> Toute modification de quantité peut impacter l'intégrité de votre inventaire et de votre comptabilité. Utilisez cette fonction avec prudence.
            </div>
        </div>
    </div>
@endsection
