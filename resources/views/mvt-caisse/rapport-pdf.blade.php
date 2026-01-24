@extends('layouts.pdf')

@section('title', 'Rapport Financier')
@section('doc_type', 'RAPPORT FINANCIER')
@section('doc_id', now()->format('d/m/Y'))

@section('content')
    <div class="section-title">Résumé de Trésorerie</div>
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Solde Total</div>
                <div class="info-value">
                    <strong style="font-size: 16pt; color: #0056b3;">{{ number_format($totalCaisses, 0, ',', ' ') }} Ar</strong><br>
                    <small>{{ $caisses->count() }} caisse(s) active(s)</small>
                </div>
            </td>
            <td class="text-right">
                <div class="info-label">Période</div>
                <div class="info-value">
                    Situation au {{ now()->format('d/m/Y à H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <table style="margin-top: 0;">
        <tr>
            <th style="background-color: #f8d7da; color: #842029; border-bottom: 2px solid #f5c2c7;">Total Débits (Sorties)</th>
            <th style="background-color: #d1e7dd; color: #0f5132; border-bottom: 2px solid #badbcc;">Total Crédits (Entrées)</th>
        </tr>
        <tr>
            <td class="text-center fw-bold" style="font-size: 14pt; color: #dc3545;">{{ number_format($mouvementsTotal->total_debit ?? 0, 0, ',', ' ') }} Ar</td>
            <td class="text-center fw-bold" style="font-size: 14pt; color: #28a745;">{{ number_format($mouvementsTotal->total_credit ?? 0, 0, ',', ' ') }} Ar</td>
        </tr>
    </table>

    <div class="section-title">Détail des Caisses</div>
    <table>
        <thead>
            <tr>
                <th>ID Caisse</th>
                <th>Libellé</th>
                <th>Date Création</th>
                <th class="text-right">Solde Actuel</th>
                <th class="text-right">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($caisses as $caisse)
                <tr>
                    <td><strong>{{ $caisse->id_caisse }}</strong></td>
                    <td>{{ $caisse->libelle }}</td>
                    <td>{{ $caisse->created_at->format('d/m/Y') }}</td>
                    <td class="text-right fw-bold">{{ number_format($caisse->montant, 0, ',', ' ') }} Ar</td>
                    <td class="text-right">
                        @if($caisse->montant > 0)
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-warning">Vide</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucune donnée disponible</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

