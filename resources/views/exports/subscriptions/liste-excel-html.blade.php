{{-- resources/views/exports/subscriptions/liste-excel-html.blade.php --}}
<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta charset="UTF-8">
    <meta name="ProgId" content="Excel.Sheet">
    <meta name="Generator" content="Microsoft Excel 15">

</head>

<body>
    @php
        // Calcul des statistiques globales
        $totalSouscrit = array_sum(array_column($data, 'Montant souscrit'));
        $totalPaye = array_sum(array_column($data, 'Montant payé'));
        $totalReste = array_sum(array_column($data, 'Reste à payer'));
        $progressionMoyenne = count($data) > 0 ? array_sum(array_column($data, 'Progression (%)')) / count($data) : 0;
        $nbCompletes = count(array_filter($data, fn($s) => $s['Statut'] === 'Complètement payée'));
    @endphp

    <table border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11px;">

        <!-- EN-TÊTE DU RAPPORT -->
        <tr>
            <td colspan="11" style="background-color: #1e40af; color: white; font-size: 18px; font-weight: bold; text-align: center; padding: 15px; border-bottom: 4px solid #f59e0b;">
                RAPPORT DES SOUSCRIPTIONS
            </td>
        </tr>

        <tr>
            <td colspan="11" style="background-color: #ffffff; color: #6b7280; font-size: 11px; text-align: center; padding: 8px;">
                Total: {{ count($data) }} souscription(s) - Généré le {{ now()->format('d/m/Y à H:i:s') }}
            </td>
        </tr>

        <!-- ESPACE -->
        <tr><td colspan="11" style="height: 10px; border: none;"></td></tr>

        <!-- STATISTIQUES - LIGNE 1 -->
        <tr>
            <td colspan="2" style="background-color: #f8fafc; border: 1px solid #e5e7eb; padding: 10px; text-align: center;">
                <div style="font-size: 9px; color: #6b7280; font-weight: 500;">TOTAL SOUSCRIT</div>
                <div style="font-size: 13px; font-weight: bold; color: #3b82f6; margin-top: 3px;">{{ number_format($totalSouscrit, 0, ',', ' ') }} FCFA</div>
            </td>
            <td style="border: none;"></td>
            <td colspan="2" style="background-color: #f8fafc; border: 1px solid #e5e7eb; padding: 10px; text-align: center;">
                <div style="font-size: 9px; color: #6b7280; font-weight: 500;">TOTAL PAYÉ</div>
                <div style="font-size: 13px; font-weight: bold; color: #059669; margin-top: 3px;">{{ number_format($totalPaye, 0, ',', ' ') }} FCFA</div>
            </td>
            <td style="border: none;"></td>
            <td colspan="2" style="background-color: #f8fafc; border: 1px solid #e5e7eb; padding: 10px; text-align: center;">
                <div style="font-size: 9px; color: #6b7280; font-weight: 500;">COMPLÈTES</div>
                <div style="font-size: 13px; font-weight: bold; color: #7c3aed; margin-top: 3px;">{{ $nbCompletes }} / {{ count($data) }}</div>
            </td>
            <td style="border: none;"></td>
            <td colspan="2" style="background-color: #f8fafc; border: 1px solid #e5e7eb; padding: 10px; text-align: center;">
                <div style="font-size: 9px; color: #6b7280; font-weight: 500;">PROGRESSION MOY.</div>
                <div style="font-size: 13px; font-weight: bold; color: #f59e0b; margin-top: 3px;">{{ number_format($progressionMoyenne, 1) }}%</div>
            </td>
        </tr>

        <!-- ESPACE -->
        <tr><td colspan="11" style="height: 15px; border: none;"></td></tr>

        <!-- EN-TÊTES DU TABLEAU -->
        <tr style="background-color: #f3f4f6; font-weight: bold; color: #374151;">
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: left; white-space: nowrap;">Souscripteur</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: left; white-space: nowrap;">FIMECO</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; white-space: nowrap;">Date souscription</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; white-space: nowrap;">Date échéance</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: right; white-space: nowrap;">Montant souscrit</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: right; white-space: nowrap;">Montant payé</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: right; white-space: nowrap;">Reste à payer</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; white-space: nowrap;">Progression (%)</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; white-space: nowrap;">Statut</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; white-space: nowrap;">En retard</td>
            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; white-space: nowrap;">Nb paiements</td>
        </tr>

        <!-- DONNÉES -->
        @foreach($data as $index => $souscription)
            @php
                $progression = $souscription['Progression (%)'];
                $bgColor = $index % 2 === 0 ? '#f9fafb' : '#ffffff';

                // Couleur de progression
                $progressBgColor = $progression >= 100 ? '#d1fae5' :
                                  ($progression >= 75 ? '#fef3c7' :
                                  ($progression >= 50 ? '#dbeafe' :
                                  ($progression >= 25 ? '#fef9c3' : '#fee2e2')));

                $progressTextColor = $progression >= 100 ? '#065f46' :
                                    ($progression >= 75 ? '#92400e' :
                                    ($progression >= 50 ? '#1e40af' :
                                    ($progression >= 25 ? '#854d0e' : '#991b1b')));

                // Couleur de statut
                $statutColors = [
                    'Complètement payée' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                    'Partiellement payée' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                    'Inactive' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                ];
                $statutColor = $statutColors[$souscription['Statut']] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
            @endphp

            <tr style="background-color: {{ $bgColor }};">
                <td style="border: 1px solid #d1d5db; padding: 6px; color: #1f2937; text-align: left;">
                    <strong>{{ $souscription['Souscripteur'] }}</strong>
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; color: #1f2937; text-align: left;">
                    {{ $souscription['FIMECO'] }}
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; color: #1f2937; text-align: center;">
                    {{ $souscription['Date souscription'] }}
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; color: #1f2937; text-align: center;">
                    {{ $souscription['Date échéance'] ?? '-' }}
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; color: #1f2937; text-align: right;">
                    {{ number_format($souscription['Montant souscrit'], 0, ',', ' ') }}
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; color: #1f2937; text-align: right;">
                    {{ number_format($souscription['Montant payé'], 0, ',', ' ') }}
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; color: #1f2937; text-align: right;">
                    {{ number_format($souscription['Reste à payer'], 0, ',', ' ') }}
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center; background-color: {{ $progressBgColor }};">
                    <strong style="color: {{ $progressTextColor }};">{{ number_format($progression, 1) }}%</strong>
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center; background-color: {{ $statutColor['bg'] }};">
                    <strong style="color: {{ $statutColor['text'] }};">{{ $souscription['Statut'] }}</strong>
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">
                    @if($souscription['En retard'] === 'Oui')
                        <strong style="color: #dc2626;">Oui</strong>
                    @else
                        <span style="color: #059669;">Non</span>
                    @endif
                </td>
                <td style="border: 1px solid #d1d5db; padding: 6px; color: #1f2937; text-align: center;">
                    {{ $souscription['Nb paiements'] }}
                </td>
            </tr>
        @endforeach

        <!-- LIGNE DE TOTAUX -->
        <tr style="background-color: #059669; color: white; font-weight: bold;">
            <td colspan="4" style="border: 1px solid #047857; padding: 10px; text-align: center; font-size: 12px;">
                TOTAUX GÉNÉRAUX
            </td>
            <td style="border: 1px solid #047857; padding: 10px; text-align: right; font-size: 12px;">
                {{ number_format($totalSouscrit, 0, ',', ' ') }}
            </td>
            <td style="border: 1px solid #047857; padding: 10px; text-align: right; font-size: 12px;">
                {{ number_format($totalPaye, 0, ',', ' ') }}
            </td>
            <td style="border: 1px solid #047857; padding: 10px; text-align: right; font-size: 12px;">
                {{ number_format($totalReste, 0, ',', ' ') }}
            </td>
            <td style="border: 1px solid #047857; padding: 10px; text-align: center; font-size: 12px;">
                {{ number_format($progressionMoyenne, 1) }}%
            </td>
            <td colspan="3" style="border: 1px solid #047857; padding: 10px; text-align: center; font-size: 12px;">
                {{ $nbCompletes }} complètes sur {{ count($data) }}
            </td>
        </tr>

        <!-- ESPACE -->
        <tr><td colspan="11" style="height: 20px; border: none;"></td></tr>

        <!-- LÉGENDE -->
        <tr>
            <td colspan="11" style="background-color: #6366f1; color: white; font-weight: bold; padding: 8px; text-align: left; font-size: 12px;">
                LÉGENDE DES PROGRESSIONS
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #d1fae5; border: 1px solid #a7f3d0; padding: 8px; text-align: center; color: #065f46; font-weight: bold;">
                100% - Complète
            </td>
            <td colspan="2" style="background-color: #fef3c7; border: 1px solid #fde047; padding: 8px; text-align: center; color: #92400e; font-weight: bold;">
                75-99% - Presque
            </td>
            <td colspan="2" style="background-color: #dbeafe; border: 1px solid #93c5fd; padding: 8px; text-align: center; color: #1e40af; font-weight: bold;">
                50-74% - En cours
            </td>
            <td colspan="2" style="background-color: #fef9c3; border: 1px solid #fde047; padding: 8px; text-align: center; color: #854d0e; font-weight: bold;">
                25-49% - Début
            </td>
            <td colspan="3" style="background-color: #fee2e2; border: 1px solid #fca5a5; padding: 8px; text-align: center; color: #991b1b; font-weight: bold;">
                0-24% - Très faible
            </td>
        </tr>

        <!-- FOOTER -->
        <tr><td colspan="11" style="height: 15px; border: none;"></td></tr>
        <tr>
            <td colspan="11" style="background-color: #37393b; color: #9ca3af; padding: 10px; text-align: center; font-size: 9px;">
                @if(!empty($AppParametres->verset_biblique) && !empty($AppParametres->reference_verset))
                    <div style="color: #fbbf24; font-style: italic; margin-bottom: 5px;">"{{ $AppParametres->verset_biblique }}" - {{ $AppParametres->reference_verset }}</div>
                @endif
                <div>Généré le {{ now()->format('d/m/Y à H:i:s') }}</div>
            </td>
        </tr>

    </table>
</body>

</html>
