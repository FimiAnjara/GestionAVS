@extends('layouts.pdf')

@section('title', 'Mouvement Stock ' . $mouvement->id_mvt_stock)
@section('doc_type', 'MOUVEMENT DE STOCK')
@section('doc_id', $mouvement->id_mvt_stock)

@section('content')
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Détails Mouvement</div>
                <div class="info-value">
                    Date: {{ $mouvement->date_->format('d/m/Y H:i') }}<br>
                    Nature: <span class="badge badge-{{ $mouvement->entree > 0 ? 'success' : 'danger' }}">
                        {{ $mouvement->entree > 0 ? 'ENTRÉE' : 'SORTIE' }}
                    </span>
                </div>
            </td>
            <td>
                <div class="info-label">Localisation & Source</div>
                <div class="info-value">
                    Emplacement: <strong>{{ $mouvement->emplacement->libelle }}</strong><br>
                    Département: {{ $mouvement->emplacement->departement->libelle }}<br>
                    @if ($mouvement->id_bonReception)
                        Source: Reception {{ $mouvement->id_bonReception }}
                    @elseif ($mouvement->id_bonCommande)
                        Source: Commande {{ $mouvement->id_bonCommande }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Article et Quantité</div>
    <table>
        <thead>
            <tr>
                <th>Désignation Article</th>
                <th class="text-right">Quantité Mvt</th>
                <th class="text-right">Unité</th>
                <th class="text-right">Stock Après Mvt</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $mouvement->article->nom }}</strong><br>
                    <small style="color: #666;">Ref: {{ $mouvement->id_article }}</small><br>
                    <small>Catégorie: {{ $mouvement->article->categorie->libelle }}</small>
                </td>
                <td class="text-right fw-bold" style="color: {{ $mouvement->entree > 0 ? '#0f5132' : '#842029' }}">
                    {{ $mouvement->entree > 0 ? '+' : '-' }}{{ number_format($mouvement->entree > 0 ? $mouvement->entree : $mouvement->sortie, 2, ',', ' ') }}
                </td>
                <td class="text-right">{{ $mouvement->article->unite->libelle }}</td>
                <td class="text-right fw-bold">{{ number_format($mouvement->stock->quantite, 2, ',', ' ') }}</td>
            </tr>
        </tbody>
    </table>

    @if ($mouvement->date_expiration)
        <div class="mt-4">
            <div class="info-label">Contrôle Qualité</div>
            <div class="info-value">
                Date d'expiration enregistrée: <strong>{{ \Carbon\Carbon::parse($mouvement->date_expiration)->format('d/m/Y') }}</strong>
            </div>
        </div>
    @endif

    @if ($mouvement->description)
        <div class="mt-4">
            <div class="info-label">Commentaires du Magasinier</div>
            <div class="info-value" style="background: #f9f9f9; padding: 10px; border-radius: 4px;">
                {{ $mouvement->description }}
            </div>
        </div>
    @endif
@endsection

