<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DashboardExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $collection = collect();

        // Section Métadonnées
        $collection->push(['RAPPORT TABLEAU DE BORD ÉGLISE']);
        $collection->push(['']);
        $collection->push(['Période:', $this->data['metadata']['period_label']]);
        $collection->push(['Du:', $this->data['metadata']['start_date']]);
        $collection->push(['Au:', $this->data['metadata']['end_date']]);
        $collection->push(['Exporté le:', $this->data['metadata']['exported_at']]);
        $collection->push(['Exporté par:', $this->data['metadata']['exported_by']]);
        $collection->push(['']);

        // Section KPIs Principaux
        $collection->push(['INDICATEURS CLÉS DE PERFORMANCE (KPIs)']);
        $collection->push(['']);
        $collection->push(['Métrique', 'Valeur']);
        $collection->push(['Total Membres', $this->data['kpis']['total_membres']]);
        $collection->push(['Nouveaux Membres', $this->data['kpis']['nouveaux_membres']]);
        $collection->push(['Présence Moyenne', $this->data['kpis']['avg_participants']]);
        $collection->push(['Nombre de Cultes', $this->data['kpis']['nombre_cultes']]);
        $collection->push(['Total Offrandes', number_format($this->data['kpis']['total_offrandes'], 0, ',', ' ') . ' FCFA']);
        $collection->push(['FIMECO Progression', $this->data['kpis']['fimeco_progression'] . '%']);
        $collection->push(['FIMECO Actuel', $this->data['kpis']['fimeco_nom']]);
        $collection->push(['']);

        // Section Évolution des Membres
        $collection->push(['ÉVOLUTION DES MEMBRES']);
        $collection->push(['']);
        $collection->push(['Période', 'Total Membres', 'Nouveaux Membres', 'Membres Actifs', 'Visiteurs', 'Nouveaux Convertis']);

        foreach ($this->data['members_evolution'] as $member) {
            $collection->push([
                $member['period'],
                $member['total_membres'],
                $member['nouveaux_membres'],
                $member['membres_actifs'],
                $member['visiteurs'],
                $member['nouveaux_convertis']
            ]);
        }
        $collection->push(['']);

        // Section Présence aux Cultes
        $collection->push(['PRÉSENCE AUX CULTES']);
        $collection->push(['']);
        $collection->push(['Période', 'Participants Moyens', 'Physiques', 'En Ligne', 'Nouveaux Visiteurs', 'Nb Cultes', 'Taux Présence %']);

        foreach ($this->data['culte_attendance'] as $culte) {
            $collection->push([
                $culte['period'],
                $culte['avg_participants'],
                $culte['participants_physiques'],
                $culte['participants_en_ligne'],
                $culte['nouveaux_visiteurs'],
                $culte['nombre_cultes'],
                $culte['taux_presence']
            ]);
        }
        $collection->push(['']);

        // Section Offrandes
        $collection->push(['ÉVOLUTION DES OFFRANDES']);
        $collection->push(['']);
        $collection->push(['Période', 'Dîmes', 'Offrandes Ordinaires', 'Offrandes Libres', 'Offrandes Spéciales', 'Total', 'Nb Transactions']);

        foreach ($this->data['offrandes_evolution'] as $offrande) {
            $collection->push([
                $offrande['period'],
                number_format($offrande['dimes'], 0, ',', ' '),
                number_format($offrande['offrandes_ordinaires'], 0, ',', ' '),
                number_format($offrande['offrandes_libres'], 0, ',', ' '),
                number_format($offrande['offrandes_speciales'], 0, ',', ' '),
                number_format($offrande['total_offrandes'], 0, ',', ' '),
                $offrande['nombre_transactions']
            ]);
        }
        $collection->push(['']);

        // Section FIMECO
        if (!empty($this->data['fimeco_evolution'])) {
            $collection->push(['ÉVOLUTION FIMECO']);
            $collection->push(['']);
            $collection->push(['Période', 'Nb FIMECOs', 'Cible Totale', 'Collecte Totale', 'Progression %', 'Souscripteurs']);

            foreach ($this->data['fimeco_evolution'] as $fimeco) {
                $collection->push([
                    $fimeco['period'],
                    $fimeco['nombre_fimecos'],
                    number_format($fimeco['cible_totale'], 0, ',', ' '),
                    number_format($fimeco['collecte_totale'], 0, ',', ' '),
                    number_format($fimeco['progression_moyenne'], 1) . '%',
                    $fimeco['souscripteurs_totaux']
                ]);
            }
            $collection->push(['']);
        }

        // Section Ratios
        $collection->push(['RATIOS ET ANALYSES']);
        $collection->push(['']);
        $collection->push(['Métrique', 'Valeur']);
        $collection->push(['Ratio Présence/Offrande', number_format($this->data['ratios']['presence_offrande_ratio'], 0, ',', ' ') . ' FCFA/personne']);
        $collection->push(['Ratio Souscripteur/Collecte', number_format($this->data['ratios']['souscripteur_collecte_ratio'], 0, ',', ' ') . ' FCFA/souscripteur']);
        $collection->push(['']);

        // Section Tendances
        if (isset($this->data['trends'])) {
            $collection->push(['TENDANCES']);
            $collection->push(['']);
            $collection->push(['Métrique', 'Valeur']);
            $collection->push(['Évolution Offrandes', $this->data['trends']['offrandes_trend'] . '%']);
            $collection->push(['Offrandes Période Actuelle', number_format($this->data['trends']['current_offrandes'], 0, ',', ' ') . ' FCFA']);
            $collection->push(['Offrandes Période Précédente', number_format($this->data['trends']['previous_offrandes'], 0, ',', ' ') . ' FCFA']);
        }

        return $collection;
    }

    public function headings(): array
    {
        return [];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => '1f2937']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'e5e7eb']
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Titre principal
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                        'color' => ['rgb' => '1f2937']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '3b82f6']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Style pour les titres de section
                $sectionTitles = [9, 18, 30, 42]; // Lignes approximatives des titres
                foreach ($sectionTitles as $row) {
                    if ($sheet->getCell("A{$row}")->getValue()) {
                        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 14,
                                'color' => ['rgb' => 'ffffff']
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '059669']
                            ]
                        ]);
                    }
                }

                // Style pour les en-têtes de colonnes
                $headerRows = [11, 20, 32, 44];
                foreach ($headerRows as $row) {
                    $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'f3f4f6']
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'd1d5db']
                            ]
                        ]
                    ]);
                }

                // Ajuster la largeur des colonnes
                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Hauteur des lignes
                $sheet->getDefaultRowDimension()->setRowHeight(20);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Bordures pour les tableaux de données
                $dataRanges = ['A11:F17', 'A20:G27', 'A32:G39'];
                foreach ($dataRanges as $range) {
                    $sheet->getStyle($range)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'd1d5db']
                            ]
                        ]
                    ]);
                }

                // Pied de page
                $lastRow = $sheet->getHighestRow() + 2;
                $sheet->setCellValue("A{$lastRow}", "Généré automatiquement par le système de gestion d'église");
                $sheet->getStyle("A{$lastRow}")->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                        'color' => ['rgb' => '6b7280']
                    ]
                ]);
            }
        ];
    }
}
