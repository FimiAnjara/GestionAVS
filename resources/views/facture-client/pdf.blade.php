@extends('layouts.pdf')

@section('title', 'Facture Client ' . $facture->id_facture_client)
@section('doc_type', 'FACTURE CLIENT')
@section('doc_id', $facture->id_facture_client)

@section('content')
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Client</div>
                <div class="info-value">
                    <strong>{{ $facture->client->nom }}</strong><br>
                    ID: {{ $facture->client->id_client }}<br>
                    @if($facture->client->telephone)
                        Tél: {{ $facture->client->telephone }}
                    @endif
                </div>
            </td>
            <td>
                <div class="info-label">Détails Facture</div>
                <div class="info-value">
                    Date: {{ $facture->date_->format('d/m/Y') }}<br>
                    État: {{ $facture->etat == 1 ? 'Créée' : 'Validée' }}<br>
                    @if($facture->id_bon_commande_client)
                        Réf Commande: {{ $facture->id_bon_commande_client }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Détail des Articles</div>
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Prix Unitaire</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($facture->factureClientFille as $ligne)
                @php 
                    $montant = $ligne->quantite * $ligne->prix;
                    $total += $montant;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $ligne->article->nom }}</strong><br>
                        <small style="color: #666;">Ref: {{ $ligne->id_article }}</small>
                    </td>
                    <td class="text-right">{{ number_format($ligne->quantite, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($ligne->prix, 0, ',', ' ') }} Ar</td>
                    <td class="text-right fw-bold">{{ number_format($montant, 0, ',', ' ') }} Ar</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-row grand-total">
            <span class="total-label">MONTANT TOTAL</span>
            <span class="total-value fw-bold">
                {{ number_format($total, 0, ',', ' ') }} Ar
            </span>
        </div>
    </div>
    <div class="clear"></div>

    @if($facture->description)
        <div class="mt-4">
            <div class="info-label">Notes Complémentaires</div>
            <div class="info-value" style="background: #f9f9f9; padding: 10px; border-radius: 4px;">
                {{ $facture->description }}
            </div>
        </div>
    @endif
@endsection
