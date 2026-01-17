<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Financier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: white;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0056b3;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #0056b3;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .date-report {
            text-align: right;
            font-size: 12px;
            color: #999;
            margin-bottom: 20px;
        }
        .summary {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .summary-box {
            flex: 1;
            min-width: 200px;
            border: 2px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .summary-box h3 {
            margin: 0 0 10px 0;
            color: #0056b3;
            font-size: 14px;
            text-transform: uppercase;
        }
        .summary-box .amount {
            font-size: 20px;
            font-weight: bold;
            color: #0056b3;
            margin: 10px 0;
        }
        .summary-box.debit .amount {
            color: #dc3545;
        }
        .summary-box.credit .amount {
            color: #28a745;
        }
        .section-title {
            color: #0056b3;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 8px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #0056b3;
            color: white;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #0056b3;
        }
        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tbody tr:hover {
            background-color: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-box {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .stat-box-value {
            font-size: 18px;
            font-weight: bold;
            color: #0056b3;
            margin-top: 10px;
        }
        .empty-message {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RAPPORT FINANCIER</h1>
        <p>État des Caisses et Mouvements</p>
    </div>

    <div class="date-report">
        Généré le: <strong>{{ now()->format('d/m/Y à H:i') }}</strong>
    </div>

    <!-- Résumé Global -->
    <div class="summary">
        <div class="summary-box">
            <h3>Solde Total</h3>
            <div class="amount">{{ number_format($totalCaisses, 0, ',', ' ') }} Ar</div>
            <p style="margin: 0; font-size: 12px; color: #666;">{{ $caisses->count() }} caisse(s)</p>
        </div>
        <div class="summary-box debit">
            <h3>Total Débits</h3>
            <div class="amount">{{ number_format($mouvementsTotal->total_debit ?? 0, 0, ',', ' ') }} Ar</div>
            <p style="margin: 0; font-size: 12px; color: #666;">Sorties de caisse</p>
        </div>
        <div class="summary-box credit">
            <h3>Total Crédits</h3>
            <div class="amount">{{ number_format($mouvementsTotal->total_credit ?? 0, 0, ',', ' ') }} Ar</div>
            <p style="margin: 0; font-size: 12px; color: #666;">Entrées de caisse</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-box">
            <h4 style="margin: 0; color: #666; font-size: 12px;">Nombre de Caisses</h4>
            <div class="stat-box-value">{{ $caisses->count() }}</div>
        </div>
        <div class="stat-box">
            <h4 style="margin: 0; color: #dc3545; font-size: 12px;">Total Débits</h4>
            <div class="stat-box-value" style="color: #dc3545;">{{ number_format($mouvementsTotal->total_debit ?? 0, 0, ',', ' ') }} Ar</div>
        </div>
        <div class="stat-box">
            <h4 style="margin: 0; color: #28a745; font-size: 12px;">Total Crédits</h4>
            <div class="stat-box-value" style="color: #28a745;">{{ number_format($mouvementsTotal->total_credit ?? 0, 0, ',', ' ') }} Ar</div>
        </div>
    </div>

    <!-- Détail des Caisses -->
    <h2 class="section-title">Détail des Caisses</h2>
    @if($caisses->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID Caisse</th>
                    <th>Libellé</th>
                    <th class="text-right">Solde</th>
                    <th>Statut</th>
                    <th>Date de Création</th>
                </tr>
            </thead>
            <tbody>
                @foreach($caisses as $caisse)
                    <tr>
                        <td><strong>{{ $caisse->id_caisse }}</strong></td>
                        <td>{{ $caisse->libelle }}</td>
                        <td class="text-right"><strong>{{ number_format($caisse->montant, 0, ',', ' ') }} Ar</strong></td>
                        <td>
                            @if($caisse->montant > 0)
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-warning">Vide</span>
                            @endif
                        </td>
                        <td>{{ $caisse->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-message">Aucune caisse trouvée</div>
    @endif

    <div class="footer">
        <p>Ce rapport a été généré automatiquement par le système de gestion financière.</p>
        <p>Pour plus de détails ou des informations supplémentaires, veuillez consulter les mouvements détaillés des caisses.</p>
    </div>
</body>
</html>
