@extends('layouts.pdf')

@section('title', 'Proforma ' . $proforma->id_proformaFournisseur)
@section('doc_type', 'FACTURE PROFORMA')
@section('doc_id', $proforma->id_proformaFournisseur)

@section('content')
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Fournisseur</div>
                <div class="info-value">
                    <strong>{{ $proforma->fournisseur->nom }}</strong><br>
                    ID: {{ $proforma->fournisseur->id_fournisseur }}<br>
                    @if($proforma->fournisseur->telephone)
                        Tél: {{ $proforma->fournisseur->telephone }}
                    @endif
                </div>
            </td>
            <td>
                <div class="info-label">Info Devis</div>
                <div class="info-value">
                    Date: {{ $proforma->date_->format('d/m/Y') }}<br>
                    État: <span class="badge badge-{{ $proforma->etat_badge }}">{{ $proforma->etat_label }}</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Articles Concernés</div>
    <table>
        <thead>
            <tr>
                <th>Désignation Article</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Prix Unitaire</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proforma->proformaFournisseurFille as $item)
                <tr>
                    <td>
                        <strong>{{ $item->article->nom }}</strong><br>
                        <small style="color: #666;">Ref: {{ $item->article->id_article }}</small>
                    </td>
                    <td class="text-right">{{ number_format($item->quantite ?? 1, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($item->prix_achat ?? 0, 0, ',', ' ') }} Ar</td>
                    <td class="text-right fw-bold">{{ number_format(($item->quantite ?? 1) * ($item->prix_achat ?? 0), 0, ',', ' ') }} Ar</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-row">
            <span class="total-label">Nb. Articles</span>
            <span class="total-value">{{ $proforma->proformaFournisseurFille->count() }}</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">MONTANT TOTAL ESTIMÉ</span>
            <span class="total-value fw-bold">{{ number_format($totalMontant, 0, ',', ' ') }} Ar</span>
        </div>
    </div>
    <div class="clear"></div>

    @if($proforma->description)
        <div class="mt-4">
            <div class="info-label">Notes & Conditions Spécifiques</div>
            <div class="info-value" style="background: #f9f9f9; padding: 10px; border-radius: 4px;">
                {{ $proforma->description }}
            </div>
        </div>
    @endif
@endsection

