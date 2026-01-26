@extends('layouts.pdf')

@section('title', 'Bon de Livraison Client ' . $bonLivraison->id_bon_livraison_client)
@section('doc_type', 'BON DE LIVRAISON CLIENT')
@section('doc_id', $bonLivraison->id_bon_livraison_client)

@section('content')
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Info Livraison</div>
                <div class="info-value">
                    Date: {{ $bonLivraison->date_->format('d/m/Y') }}<br>
                    Magasin Source: <strong>{{ $bonLivraison->magasin->nom }}</strong><br>
                    État: {{ $bonLivraison->etat == 1 ? 'Créée' : 'Livré (Stock Sorti)' }}
                </div>
            </td>
            <td>
                <div class="info-label">Origine Commande</div>
                <div class="info-value">
                    Réf Commande: {{ $bonLivraison->id_bon_commande_client }}<br>
                    Client: <strong>{{ $bonLivraison->client->nom }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Articles Livrés</div>
    <table>
        <thead>
            <tr>
                <th>Désignation Article</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Unité</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bonLivraison->bonLivraisonClientFille as $item)
                <tr>
                    <td>
                        <strong>{{ $item->article->nom }}</strong><br>
                        <small style="color: #666;">Code: {{ $item->id_article }}</small>
                    </td>
                    <td class="text-right fw-bold">{{ number_format($item->quantite, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ $item->article->unite->libelle ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Aucun article dans cette livraison</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-row">
            <span class="total-label">Nombre de lignes</span>
            <span class="total-value">{{ $bonLivraison->bonLivraisonClientFille->count() }}</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">QUANTITÉ TOTALE</span>
            <span class="total-value fw-bold">{{ number_format($bonLivraison->bonLivraisonClientFille->sum('quantite'), 2, ',', ' ') }}</span>
        </div>
    </div>
    <div class="clear"></div>
@endsection
