<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mouvement de Stock {{ $mouvement->id_mvt_stock }}</title>
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
            font-size: 24px;
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

        .badge-entry { background-color: #28a745; color: #fff; }
        .badge-exit { background-color: #dc3545; color: #fff; }

        .header-right p {
            font-size: 10px;
            margin: 5px 0;
        }

        .header-right strong {
            font-size: 12px;
            color: #1a73e8;
        }

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
            min-width: 100px;
            font-weight: 600;
        }

        /* Conteneurs */
        .detail-block {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .detail-block h3 {
            font-size: 11px;
            color: #1a73e8;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 10px;
        }

        .detail-item strong {
            color: #333;
        }

        .detail-item span {
            color: #666;
        }

        /* Table */
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

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .quantity-box {
            display: inline-block;
            background-color: #f0f7ff;
            border: 2px solid #1a73e8;
            border-radius: 4px;
            padding: 8px 12px;
            font-weight: bold;
            color: #1a73e8;
            font-size: 12px;
        }

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
    </style>
</head>
<body>
    <div class="watermark">GROSSISTE</div>

    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="header-left">
                <h1>↔️ MOUVEMENT DE STOCK</h1>
                <p>Gestion des Stocks - GROSSISTE</p>
            </div>
            <div class="header-right">
                @if ($mouvement->entree > 0)
                    <div class="badge badge-entry">ENTRÉE</div>
                @else
                    <div class="badge badge-exit">SORTIE</div>
                @endif
                <p>
                    <strong>{{ $mouvement->id_mvt_stock }}</strong><br>
                    {{ $mouvement->date_->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <!-- Article -->
        <div class="info-section">
            <div class="info-block" style="flex: 2;">
                <h3>Article</h3>
                <p style="font-size: 12px; margin-bottom: 5px;">
                    <strong style="display: block; color: #1a73e8;">{{ $mouvement->article->nom }}</strong>
                </p>
                <p><strong>ID :</strong> {{ $mouvement->id_article }}</p>
                <p><strong>Catégorie :</strong> {{ $mouvement->article->categorie->libelle }}</p>
                <p><strong>Unité :</strong> {{ $mouvement->article->unite->libelle }}</p>
            </div>

            <div class="info-block">
                <h3>Quantité</h3>
                <div style="text-align: center; padding: 15px 0;">
                    <div class="quantity-box">
                        {{ number_format($mouvement->entree > 0 ? $mouvement->entree : $mouvement->sortie, 2, ',', ' ') }}
                        {{ $mouvement->article->unite->libelle }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Localisation -->
        <div class="detail-block">
            <h3>Localisation</h3>
            <div class="detail-item">
                <strong>Emplacement :</strong>
                <span>{{ $mouvement->emplacement->libelle }}</span>
            </div>
            <div class="detail-item">
                <strong>Département :</strong>
                <span>{{ $mouvement->emplacement->departement->libelle }}</span>
            </div>
            @if ($mouvement->date_expiration)
                <div class="detail-item">
                    <strong>Date d'Expiration :</strong>
                    <span>{{ \Carbon\Carbon::parse($mouvement->date_expiration)->format('d/m/Y') }}</span>
                </div>
            @endif
        </div>

        <!-- Stock -->
        <div class="detail-block">
            <h3>Situation du Stock</h3>
            <div class="detail-item">
                <strong>Quantité actuelle en stock :</strong>
                <span>{{ number_format($mouvement->stock->quantite, 2, ',', ' ') }} {{ $mouvement->article->unite->libelle }}</span>
            </div>
        </div>

        <!-- Source -->
        @if ($mouvement->id_bonReception)
            <div class="detail-block">
                <h3>Source du Mouvement</h3>
                <div class="detail-item">
                    <strong>Bon de Réception :</strong>
                    <span>{{ $mouvement->bonReception->id_bonReception }}</span>
                </div>
                <div class="detail-item">
                    <strong>Date Réception :</strong>
                    <span>{{ $mouvement->bonReception->date_->format('d/m/Y') }}</span>
                </div>
            </div>
        @elseif ($mouvement->id_bonCommande)
            <div class="detail-block">
                <h3>Source du Mouvement</h3>
                <div class="detail-item">
                    <strong>Bon de Commande :</strong>
                    <span>{{ $mouvement->bonCommande->id_bonCommande }}</span>
                </div>
                <div class="detail-item">
                    <strong>Date Commande :</strong>
                    <span>{{ $mouvement->bonCommande->date_->format('d/m/Y') }}</span>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            Document généré le {{ now()->format('d/m/Y à H:i:s') }} | GROSSISTE ERP
        </div>
    </div>
</body>
</html>
