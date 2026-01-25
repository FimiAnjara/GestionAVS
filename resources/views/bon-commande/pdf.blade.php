@extends('layouts.pdf')

@section('title', 'Bon de Commande ' . $bonCommande->id_bonCommande)
@section('doc_type', 'BON DE COMMANDE')
@section('doc_id', $bonCommande->id_bonCommande)

@section('content')
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Fournisseur</div>
                <div class="info-value">
                    <strong>{{ $bonCommande->proformaFournisseur->fournisseur->nom }}</strong><br>
                    {{ $bonCommande->proformaFournisseur->fournisseur->adresse }}
                </div>
            </td>
            <td>
                <div class="info-label">Détails Commande</div>
                <div class="info-value">
                    Date: {{ $bonCommande->date_->format('d/m/Y') }}<br>
                    État: <span class="badge badge-{{ $bonCommande->etat == 1 ? 'warning' : ($bonCommande->etat == 5 ? 'info' : 'success') }}">
                        {{ $bonCommande->etat == 1 ? 'Créée' : ($bonCommande->etat == 5 ? 'Validée Finance' : 'Validée DG') }}
                    </span><br>
                    Réf Proforma: {{ $bonCommande->proformaFournisseur->id_proformaFournisseur }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Articles Commandés</div>
    <table>
        <thead>
            <tr>
                <th>Désignation Article</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Prix Unitaire</th>
                <th class="text-right">Montant Total</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($articles as $article)
                @php 
                    $montant = $article->quantite * $article->prix_achat;
                    $total += $montant;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $article->article->nom }}</strong><br>
                        <small style="color: #666;">Code: {{ $article->id_article }}</small>
                    </td>
                    <td class="text-right">{{ number_format($article->quantite, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($article->prix_achat, 2, ',', ' ') }} Ar</td>
                    <td class="text-right fw-bold">{{ number_format($montant, 2, ',', ' ') }} Ar</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-row">
            <span class="total-label">Sous-Total</span>
            <span class="total-value">{{ number_format($total, 2, ',', ' ') }} Ar</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">TOTAL (NET)</span>
            <span class="total-value fw-bold">{{ number_format($total, 2, ',', ' ') }} Ar</span>
        </div>
    </div>
    <div class="clear"></div>

    @if ($bonCommande->description)
        <div class="mt-4">
            <div class="info-label">Notes / Instructions</div>
            <div class="info-value" style="background: #f9f9f9; padding: 10px; border-radius: 4px;">
                {{ $bonCommande->description }}
            </div>
        </div>
    @endif
@endsection

