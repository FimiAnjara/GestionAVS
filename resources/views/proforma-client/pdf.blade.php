@extends('layouts.pdf')

@section('title', 'Proforma ' . $proforma->id_proforma_client)
@section('doc_type', 'FACTURE PROFORMA')
@section('doc_id', $proforma->id_proforma_client)

@section('content')
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Client</div>
                <div class="info-value">
                    <strong>{{ $proforma->client->nom }}</strong><br>
                    ID: {{ $proforma->client->id_client }}<br>
                    @if($proforma->client->telephone)
                        Tél: {{ $proforma->client->telephone }}
                    @endif
                </div>
            </td>
            <td>
                <div class="info-label">Info Devis</div>
                <div class="info-value">
                    Date: {{ $proforma->date_->format('d/m/Y') }}<br>
                    @php
                        $label = 'Inconnu';
                        if($proforma->etat == 1) { $label = 'Créée'; }
                        elseif($proforma->etat == 11) { $label = 'Validée'; }
                        elseif($proforma->etat == 0) { $label = 'Annulée'; }
                    @endphp
                    État: {{ $label }}
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
            @foreach($proforma->proformaClientFille as $item)
                <tr>
                    <td>
                        <strong>{{ $item->article->nom }}</strong><br>
                        <small style="color: #666;">Ref: {{ $item->id_article }}</small>
                    </td>
                    <td class="text-right">{{ number_format($item->quantite, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($item->prix, 0, ',', ' ') }} Ar</td>
                    <td class="text-right fw-bold">{{ number_format($item->quantite * $item->prix, 0, ',', ' ') }} Ar</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $totalMontant = $proforma->proformaClientFille->sum(function($item) { 
            return $item->quantite * $item->prix; 
        });
    @endphp

    <div class="total-box">
        <div class="total-row">
            <span class="total-label">Nb. Articles</span>
            <span class="total-value">{{ $proforma->proformaClientFille->count() }}</span>
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
