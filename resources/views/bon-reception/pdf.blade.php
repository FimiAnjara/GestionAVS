@extends('layouts.pdf')

@section('title', 'Bon de Réception ' . $bonReception->id_bonReception)
@section('doc_type', 'BON DE RÉCEPTION')
@section('doc_id', $bonReception->id_bonReception)

@section('content')
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Info Réception</div>
                <div class="info-value">
                    Date: {{ $bonReception->date_->format('d/m/Y à H:i') }}<br>
                    Magasin: <strong>{{ $bonReception->magasin->nom }}</strong><br>
                    État: <span class="badge badge-{{ $bonReception->etat == 1 ? 'warning' : 'success' }}">
                        {{ $bonReception->etat == 1 ? 'Créée' : 'Réceptionnée' }}
                    </span>
                </div>
            </td>
            <td>
                <div class="info-label">Origine Commande</div>
                <div class="info-value">
                    Réf Commande: {{ $bonReception->bonCommande->id_bonCommande }}<br>
                    Fournisseur: <strong>{{ $bonReception->bonCommande->proformaFournisseur->fournisseur->nom }}</strong><br>
                    Date Commande: {{ $bonReception->bonCommande->date_->format('d/m/Y') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Articles Réceptionnés</div>
    <table>
        <thead>
            <tr>
                <th>Désignation Article</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Unité</th>
                <th class="text-right">Date Expiration</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bonReception->articles as $article)
                <tr>
                    <td>
                        <strong>{{ $article->article->nom }}</strong><br>
                        <small style="color: #666;">Code: {{ $article->id_article }}</small>
                    </td>
                    <td class="text-right fw-bold">{{ number_format($article->quantite, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ $article->article->unite->libelle }}</td>
                    <td class="text-right">
                        @if ($article->date_expiration)
                            {{ \Carbon\Carbon::parse($article->date_expiration)->format('d/m/Y') }}
                        @else
                            <span style="color: #999;">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Aucun article dans cette réception</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-row">
            <span class="total-label">Nombre de lignes</span>
            <span class="total-value">{{ $bonReception->articles->count() }}</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">QUANTITÉ TOTALE</span>
            <span class="total-value fw-bold">{{ number_format($bonReception->articles->sum('quantite'), 2, ',', ' ') }}</span>
        </div>
    </div>
    <div class="clear"></div>
@endsection

