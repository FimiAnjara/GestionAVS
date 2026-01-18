<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .company-info h1 {
            margin: 0;
            color: #007bff;
            font-size: 24px;
        }
        .company-info p {
            margin: 5px 0;
            color: #666;
        }
        .invoice-number {
            text-align: right;
            font-size: 14px;
        }
        .invoice-number h2 {
            margin: 0;
            font-size: 28px;
            color: #007bff;
        }
        .invoice-number p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        .info-box {
            flex: 1;
            margin-right: 20px;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #007bff;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        table thead {
            background-color: #007bff;
            color: white;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
        }
        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .amount-right {
            text-align: right;
        }
        table tfoot {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        table tfoot td {
            border-top: 2px solid #007bff;
            padding: 12px;
        }
        .total-section {
            margin: 20px 0;
            text-align: right;
        }
        .total-section .total-line {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .total-line span:first-child {
            width: 200px;
            text-align: left;
        }
        .total-line span:last-child {
            width: 100px;
            text-align: right;
            font-weight: bold;
        }
        .total-amount {
            font-size: 18px;
            color: #007bff;
            border-top: 2px solid #007bff;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <h1>FACTURE FOURNISSEUR</h1>
            <p><strong>GROSSISTE ANTANANARIVO</strong></p>
        </div>
        <div class="invoice-number">
            <h2>{{ $facture->id_factureFournisseur }}</h2>
            <p>Date: {{ $facture->date_->format('d/m/Y') }}</p>
            <p>État: <strong>{{ $facture->etat_label }}</strong></p>
        </div>
    </div>

    <!-- Infos -->
    <div class="info-section">
        @if($facture->bonCommande)
        <div class="info-box">
            <h3>Bon de Commande</h3>
            <p><strong>{{ $facture->bonCommande->id_bonCommande }}</strong></p>
        </div>
        <div class="info-box">
            <h3>Fournisseur</h3>
            <p><strong>{{ $facture->bonCommande->fournisseur->nom ?? 'N/A' }}</strong></p>
            @if($facture->bonCommande->fournisseur)
                <p>{{ $facture->bonCommande->fournisseur->adresse ?? '' }}</p>
                <p>{{ $facture->bonCommande->fournisseur->email ?? '' }}</p>
            @endif
        </div>
        @endif
    </div>

    <!-- Articles Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Article</th>
                <th style="width: 20%;" class="amount-right">Quantité</th>
                <th style="width: 20%;" class="amount-right">Prix Achat</th>
                <th style="width: 20%;" class="amount-right">Montant</th>
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
                <td>{{ $ligne->article->nom ?? 'N/A' }}</td>
                <td class="amount-right">{{ number_format($ligne->quantite, 2) }}</td>
                <td class="amount-right">{{ number_format($ligne->prix_achat, 2) }}</td>
                <td class="amount-right">{{ number_format($montant, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;">TOTAL GÉNÉRAL:</td>
                <td class="amount-right">{{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Description -->
    @if($facture->description)
    <div class="info-box" style="margin: 20px 0;">
        <h3>Notes</h3>
        <p>{{ $facture->description }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Facture générée le {{ now()->format('d/m/Y H:i') }}</p>
        <p>Ce document est une facture de référence</p>
    </div>
</body>
</html>
