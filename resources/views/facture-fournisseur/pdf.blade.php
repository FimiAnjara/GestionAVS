@extends('layouts.pdf')

@section('title', 'Facture ' . $facture->id_factureFournisseur)
@section('doc_type', 'FACTURE FOURNISSEUR')
@section('doc_id', $facture->id_factureFournisseur)

@section('content')
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Fournisseur</div>
                <div class="info-value">
                    @if($facture->bonCommande && $facture->bonCommande->fournisseur)
                        <strong>{{ $facture->bonCommande->fournisseur->nom }}</strong><br>
                        {{ $facture->bonCommande->fournisseur->adresse }}<br>
                        {{ $facture->bonCommande->fournisseur->email }}
                    @else
                        N/A
                    @endif
                </div>
            </td>
            <td>
                <div class="info-label">Détails Facture</div>
                <div class="info-value">
                    Date: {{ $facture->date_->format('d/m/Y') }}<br>
                    État: <span class="badge badge-{{ $facture->etat_badge }}">{{ $facture->etat_label }}</span><br>
                    @if($facture->id_bonCommande)
                        Réf Commande: {{ $facture->id_bonCommande }}
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
                <th class="text-right">Prix Achat</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($facture->factureFournisseurFille as $ligne)
                @php 
                    $montant = $ligne->quantite * $ligne->prix_achat;
                    $total += $montant;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $ligne->article->nom ?? 'N/A' }}</strong><br>
                        <small style="color: #666;">Ref: {{ $ligne->id_article }}</small>
                    </td>
                    <td class="text-right">{{ number_format($ligne->quantite, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($ligne->prix_achat, 2, ',', ' ') }} Ar</td>
                    <td class="text-right fw-bold">{{ number_format($montant, 2, ',', ' ') }} Ar</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-row">
            <span class="total-label">Montant Total HT</span>
            <span class="total-value">{{ number_format($total, 2, ',', ' ') }} Ar</span>
        </div>
        <div class="total-row">
            <span class="total-label">Montant Payé</span>
            <span class="total-value">{{ number_format($facture->montant_paye, 2, ',', ' ') }} Ar</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">RESTE À PAYER</span>
            <span class="total-value fw-bold" style="color: {{ $facture->reste_a_payer > 0 ? '#842029' : '#0f5132' }}">
                {{ number_format($facture->reste_a_payer, 2, ',', ' ') }} Ar
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

