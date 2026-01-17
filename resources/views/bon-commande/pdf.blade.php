<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10mm;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 12px;
        }
        .info-label {
            font-weight: bold;
            color: #333;
        }
        .info-value {
            color: #666;
        }
        .section-title {
            background-color: #f5f5f5;
            padding: 8px 10px;
            margin-top: 15px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 12px;
            border-left: 3px solid #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 10px;
        }
        table thead {
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        table tfoot {
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
        }
        table tfoot td {
            padding: 8px;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .description-box {
            background-color: #fafafa;
            padding: 10px;
            margin-top: 15px;
            border-left: 3px solid #333;
            font-size: 11px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .state-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 11px;
        }
        .state-created {
            background-color: #fff3cd;
            color: #856404;
        }
        .state-finance {
            background-color: #cfe2ff;
            color: #084298;
        }
        .state-dg {
            background-color: #d1e7dd;
            color: #0f5132;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BON DE COMMANDE FOURNISSEUR</h1>
        <p>GROSSISTE - Système de Gestion Commerciale</p>
    </div>

    <div>
        <div class="info-row">
            <span><span class="info-label">ID:</span> <span class="info-value">{{ $bonCommande->id_bonCommande }}</span></span>
            <span><span class="info-label">Date:</span> <span class="info-value">{{ $bonCommande->date_->format('d/m/Y') }}</span></span>
        </div>
        <div class="info-row">
            <span><span class="info-label">Fournisseur:</span> <span class="info-value">{{ $bonCommande->proformaFournisseur->fournisseur->nom }}</span></span>
            <span><span class="info-label">État:</span> 
                <span class="state-badge state-{{ $bonCommande->etat == 1 ? 'created' : ($bonCommande->etat == 5 ? 'finance' : 'dg') }}">
                    {{ $bonCommande->etat == 1 ? 'Créée' : ($bonCommande->etat == 5 ? 'Validée par Finance' : 'Validée par DG') }}
                </span>
            </span>
        </div>
        <div class="info-row">
            <span><span class="info-label">Proforma:</span> <span class="info-value">{{ $bonCommande->proformaFournisseur->id_proformaFournisseur }}</span></span>
        </div>
    </div>

    <div class="section-title">ARTICLES</div>
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Prix Unitaire (Ar)</th>
                <th class="text-right">Montant (Ar)</th>
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
                    <td>{{ $article->article->nom }}</td>
                    <td class="text-right">{{ number_format($article->quantite, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($article->prix_achat, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($montant, 2, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($total, 2, ',', ' ') }} Ar</td>
            </tr>
        </tfoot>
    </table>

    @if ($bonCommande->description)
        <div class="description-box">
            <strong>Remarques/Description:</strong><br>
            {{ $bonCommande->description }}
        </div>
    @endif

    <div class="footer">
        <p>Généré le {{ now()->format('d/m/Y à H:i') }} - GROSSISTE</p>
    </div>
</body>
</html>
