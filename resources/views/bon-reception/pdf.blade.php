<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bon de Réception {{ $bonReception->id_bonReception }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* En-tête */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 3px solid #1a73e8;
            padding-bottom: 15px;
        }

        .header-left h1 {
            font-size: 28px;
            color: #1a73e8;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .header-left p {
            color: #666;
            font-size: 10px;
            margin: 2px 0;
        }

        .header-right {
            text-align: right;
        }

        .header-right .badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .badge-yellow { background-color: #ffc107; color: #000; }
        .badge-blue { background-color: #0066cc; color: #fff; }
        .badge-green { background-color: #28a745; color: #fff; }

        /* Informations */
        .info-section {
            display: flex;
            gap: 30px;
            margin-bottom: 25px;
        }

        .info-block {
            flex: 1;
        }

        .info-block h3 {
            font-size: 11px;
            color: #1a73e8;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-block p {
            font-size: 10px;
            margin: 4px 0;
            line-height: 1.6;
        }

        .info-block strong {
            display: inline-block;
            min-width: 80px;
            font-weight: 600;
        }

        /* Tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        thead {
            background-color: #1a73e8;
            color: white;
        }

        th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #1a73e8;
        }

        td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            font-size: 10px;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f0f7ff;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Footer */
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            text-align: right;
            font-size: 9px;
            color: #999;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
            white-space: nowrap;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f7ff;
            border: 1px solid #d0e1f9;
            border-radius: 4px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 11px;
        }

        .summary-item strong {
            color: #1a73e8;
        }

        page-break-inside: avoid;
    </style>
</head>
<body>
    <div class="watermark">GROSSISTE</div>

    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="header-left">
                <h1>📦 BON DE RÉCEPTION</h1>
                <p>Gestion des Achats - GROSSISTE</p>
            </div>
            <div class="header-right">
                @if ($bonReception->etat == 1)
                    <div class="badge badge-yellow">CRÉÉE</div>
                @elseif ($bonReception->etat == 11)
                    <div class="badge badge-green">RÉCEPTIONNÉE</div>
                @else
                    <div class="badge badge-yellow">{{ $bonReception->etat }}</div>
                @endif
                <p style="margin-top: 5px; font-size: 10px;">
                    <strong>{{ $bonReception->id_bonReception }}</strong><br>
                    {{ $bonReception->date_->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <!-- Informations -->
        <div class="info-section">
            <div class="info-block">
                <h3>Bon de Réception</h3>
                <p><strong>ID :</strong> {{ $bonReception->id_bonReception }}</p>
                <p><strong>Date :</strong> {{ $bonReception->date_->format('d/m/Y à H:i') }}</p>
                <p><strong>État :</strong> {{ $bonReception->etat == 1 ? 'Créée' : 'Réceptionnée' }}</p>
            </div>

            <div class="info-block">
                <h3>Bon de Commande</h3>
                <p><strong>ID :</strong> {{ $bonReception->bonCommande->id_bonCommande }}</p>
                <p><strong>Fournisseur :</strong> {{ $bonReception->bonCommande->proformaFournisseur->fournisseur->nom }}</p>
                <p><strong>Date :</strong> {{ $bonReception->bonCommande->date_->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Tableau des articles -->
        <table>
            <thead>
                <tr>
                    <th style="width: 35%;">Article</th>
                    <th style="width: 20%;" class="text-center">Quantité</th>
                    <th style="width: 20%;">Unité</th>
                    <th style="width: 25%;" class="text-center">Date Expiration</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bonReception->articles as $article)
                    <tr>
                        <td>
                            <strong>{{ $article->article->nom }}</strong><br>
                            <small>{{ $article->id_article }}</small>
                        </td>
                        <td class="text-center">{{ number_format($article->quantite, 2, ',', ' ') }}</td>
                        <td>{{ $article->article->unite->libelle }}</td>
                        <td class="text-center">
                            @if ($article->date_expiration)
                                {{ \Carbon\Carbon::parse($article->date_expiration)->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Aucun article</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Résumé -->
        <div class="summary">
            <div class="summary-item">
                <strong>Nombre d'articles :</strong>
                <span>{{ $bonReception->articles->count() }}</span>
            </div>
            <div class="summary-item">
                <strong>Quantité totale reçue :</strong>
                <span>{{ number_format($bonReception->articles->sum('quantite'), 2, ',', ' ') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Document généré le {{ now()->format('d/m/Y à H:i:s') }} | GROSSISTE ERP
        </div>
    </div>
</body>
</html>
