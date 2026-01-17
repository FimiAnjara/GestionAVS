<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proforma {{ $proforma->id_proformaFournisseur }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0056b3;
            padding-bottom: 20px;
        }
        .company-info h1 {
            color: #0056b3;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .document-title {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: #0056b3;
        }
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-block h3 {
            color: #0056b3;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 5px;
        }
        .info-block p {
            margin-bottom: 5px;
            font-size: 13px;
        }
        .label {
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        thead {
            background-color: #0056b3;
            color: white;
        }
        thead th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #0056b3;
        }
        tbody td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            font-size: 13px;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            margin-top: 30px;
            border-top: 2px solid #0056b3;
            padding-top: 15px;
        }
        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .total-row .label {
            width: 200px;
        }
        .total-row .value {
            width: 150px;
            text-align: right;
            font-weight: bold;
        }
        .grand-total {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #0056b3;
            font-size: 16px;
        }
        .grand-total .label {
            width: 200px;
        }
        .grand-total .value {
            width: 150px;
            text-align: right;
            background-color: #0056b3;
            color: white;
            padding: 10px;
            border-radius: 4px;
        }
        .description-section {
            margin: 30px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #0056b3;
            border-radius: 4px;
        }
        .description-section h4 {
            color: #0056b3;
            margin-bottom: 10px;
            font-size: 13px;
            text-transform: uppercase;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .status-created { color: #ffc107; }
        .status-validated { color: #28a745; }
        .status-cancelled { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>GROSSISTE</h1>
                <p style="color: #666; font-size: 12px;">Bon de Commande Fournisseur</p>
            </div>
            <div class="document-title">
                PROFORMA<br>
                <span style="font-size: 14px; color: #666;">Bon de Commande</span>
            </div>
        </div>

        <!-- Informations -->
        <div class="info-section">
            <div class="info-block">
                <h3>Détails du Document</h3>
                <p><span class="label">N° Proforma:</span> {{ $proforma->id_proformaFournisseur }}</p>
                <p><span class="label">Date:</span> {{ $proforma->date_->format('d/m/Y') }}</p>
                <p><span class="label">État:</span> ({{ $proforma->etat }}) {{ $proforma->etat_label }}</p>
            </div>

            <div class="info-block">
                <h3>Fournisseur</h3>
                <p><span class="label">Nom:</span> {{ $proforma->fournisseur->nom }}</p>
                <p><span class="label">ID:</span> {{ $proforma->fournisseur->id_fournisseur }}</p>
                @if($proforma->fournisseur->telephone)
                    <p><span class="label">Téléphone:</span> {{ $proforma->fournisseur->telephone }}</p>
                @endif
            </div>
        </div>

        <!-- Articles -->
        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Article</th>
                    <th style="width: 15%;" class="text-right">Quantité</th>
                    <th style="width: 20%;" class="text-right">Prix Unitaire</th>
                    <th style="width: 25%;" class="text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proforma->proformaFournisseurFille as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->article->nom }}</strong><br>
                            <small style="color: #666;">ID: {{ $item->article->id_article }}</small>
                        </td>
                        <td class="text-right">{{ number_format($item->quantite ?? 1, 2, ',', ' ') }}</td>
                        <td class="text-right">{{ number_format($item->prix_achat ?? 0, 0, ',', ' ') }} Ar</td>
                        <td class="text-right"><strong>{{ number_format(($item->quantite ?? 1) * ($item->prix_achat ?? 0), 0, ',', ' ') }} Ar</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Description -->
        @if($proforma->description)
            <div class="description-section">
                <h4>Remarques & Conditions</h4>
                <p>{{ $proforma->description }}</p>
            </div>
        @endif

        <!-- Totals -->
        <div class="total-section">
            <div class="total-row">
                <div class="label">Nombre d'articles:</div>
                <div class="value">{{ $proforma->proformaFournisseurFille->count() }}</div>
            </div>
            <div class="grand-total">
                <div class="label">MONTANT TOTAL:</div>
                <div class="value">{{ number_format($totalMontant, 0, ',', ' ') }} Ar</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Document généré le {{ now()->format('d/m/Y H:i') }}</p>
            <p>GROSSISTE - Système de Gestion Commerciale</p>
        </div>
    </div>
</body>
</html>
