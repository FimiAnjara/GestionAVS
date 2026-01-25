@extends('layouts.pdf')

@section('title', 'Bon de Commande Client ' . $bonCommande->id_bon_commande_client)
@section('doc_type', 'BON DE COMMANDE CLIENT')
@section('doc_id', $bonCommande->id_bon_commande_client)

@section('content')
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Client</div>
                <div class="info-value">
                    <strong>{{ $bonCommande->client->nom }}</strong><br>
                    ID: {{ $bonCommande->client->id_client }}
                </div>
            </td>
            <td>
                <div class="info-label">Détails Commande</div>
                <div class="info-value">
                    Date: {{ $bonCommande->date_->format('d/m/Y') }}<br>
                    État: <span class="badge badge-{{ $bonCommande->etat == 1 ? 'warning' : 'success' }}">
                        {{ $bonCommande->etat == 1 ? 'Créée' : 'Validée' }}
                    </span><br>
                    Réf Proforma: {{ $bonCommande->id_proforma_client ?? 'N/A' }}
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
            @foreach ($bonCommande->bonCommandeClientFille as $item)
                @php 
                    $montant = $item->quantite * $item->prix;
                    $total += $montant;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $item->article->nom }}</strong><br>
                        <small style="color: #666;">Code: {{ $item->id_article }}</small>
                    </td>
                    <td class="text-right">{{ number_format($item->quantite, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($item->prix, 2, ',', ' ') }} Ar</td>
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
