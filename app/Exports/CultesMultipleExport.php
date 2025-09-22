<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class CultesMultipleExport implements WithMultipleSheets
{
    protected $cultes;

    public function __construct($cultes)
    {
        $this->cultes = $cultes;
    }

    public function sheets(): array
    {
        return [
            new CultesResumeSheet($this->cultes),
            new CultesDetailSheet($this->cultes),
            new CultesFinancierSheet($this->cultes),
        ];
    }
}

// Feuille de resume de tous les cultes
class CultesResumeSheet implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithTitle
{
    protected $cultes;

    public function __construct($cultes)
    {
        $this->cultes = $cultes;
    }

    public function title(): string
    {
        return 'Resume des Cultes';
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function collection()
    {
        return $this->cultes->map(function ($culte) {
            return [
                $culte->titre,
                $culte->type_culte_libelle,
                $culte->date_culte->format('d/m/Y'),
                $culte->heure_debut ? \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') : '',
                $culte->lieu,
                $culte->statut_libelle,
                $culte->nombre_participants ?? 0,
                $culte->nombre_conversions ?? 0,
                $culte->nombre_baptemes ?? 0,
                number_format($culte->offrande_totale ?? 0, 0) . ' FCFA',
                $culte->note_globale ?? '',
                $culte->pasteurPrincipal?->nom_complet ?? 'Non assigne',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Titre',
            'Type',
            'Date',
            'Heure',
            'Lieu',
            'Statut',
            'Participants',
            'Conversions',
            'Baptemes',
            'Offrandes',
            'Note',
            'Pasteur',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '3B82F6']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // En-tete principal
                $sheet->mergeCells('A1:L3');
                $sheet->setCellValue('A1', 'RAPPORT CONSOLIDE DES CULTES');
                $sheet->getStyle('A1:L3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1F2937']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Informations de periode
                $periodeDebut = $this->cultes->first()->date_culte->format('d/m/Y');
                $periodeFin = $this->cultes->last()->date_culte->format('d/m/Y');
                $totalCultes = $this->cultes->count();

                $sheet->mergeCells('A4:L4');
                $sheet->setCellValue('A4', "Periode: {$periodeDebut} au {$periodeFin} | {$totalCultes} culte(s)");
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['size' => 12, 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Date de generation
                $sheet->setCellValue('A5', 'Genere le : ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle('A5')->applyFromArray([
                    'font' => ['size' => 10, 'color' => ['rgb' => '9CA3AF'], 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Calcul de la derniere ligne avec donnees
                $lastRow = $this->cultes->count() + 6;

                // Bordures pour toutes les cellules de donnees
                $sheet->getStyle("A6:L{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                    ],
                ]);

                // Alternance de couleurs pour les lignes
                for ($row = 7; $row <= $lastRow; $row++) {
                    if (($row - 6) % 2 == 0) {
                        $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F9FAFB']],
                        ]);
                    }
                }

                // Alignement des colonnes numeriques
                $sheet->getStyle("G7:K{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Largeur des colonnes
                $columnWidths = [
                    'A' => 25, 'B' => 15, 'C' => 12, 'D' => 8, 'E' => 20,
                    'F' => 12, 'G' => 12, 'H' => 12, 'I' => 10, 'J' => 15,
                    'K' => 8, 'L' => 20
                ];

                foreach ($columnWidths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                // Statistiques de synthese en bas


                /** @var int $synthRow */
                $synthRow = $lastRow + 2;

                $sheet->mergeCells("A{$synthRow}:L{$synthRow}");
                $sheet->setCellValue("A{$synthRow}", 'STATISTIQUES DE SYNTHESE');
                $sheet->getStyle("A{$synthRow}:L{$synthRow}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Calculs de synthese
                $totalParticipants = $this->cultes->sum('nombre_participants');
                $totalConversions = $this->cultes->sum('nombre_conversions');
                $totalBaptemes = $this->cultes->sum('nombre_baptemes');
                $totalOffrandes = $this->cultes->sum('offrande_totale');
                $moyenneNote = $this->cultes->where('note_globale', '!=', null)->avg('note_globale');

                $synthRow++;
                $sheet->setCellValue("A{$synthRow}", 'Total Participants:');
                $sheet->setCellValue("B{$synthRow}", number_format($totalParticipants));
                $sheet->setCellValue("D{$synthRow}", 'Total Conversions:');
                $sheet->setCellValue("E{$synthRow}", number_format($totalConversions));
                $sheet->setCellValue("G{$synthRow}", 'Total Baptemes:');
                $sheet->setCellValue("H{$synthRow}", number_format($totalBaptemes));

                $synthRow++;
                $sheet->setCellValue("A{$synthRow}", 'Total Offrandes:');
                $sheet->setCellValue("B{$synthRow}", number_format($totalOffrandes, 0) . ' FCFA');
                $sheet->setCellValue("D{$synthRow}", 'Moyenne Note:');
                $sheet->setCellValue("E{$synthRow}", $moyenneNote ? round($moyenneNote, 1) . '/10' : 'N/A');
                $sheet->setCellValue("G{$synthRow}", 'Moyenne Participants:');
                $sheet->setCellValue("H{$synthRow}", $totalCultes > 0 ? round($totalParticipants / $totalCultes, 1) : 0);

                // Style pour les statistiques
                $statsStartRow = $synthRow - 1;
                $sheet->getStyle("A{$statsStartRow}:L{$synthRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'EFF6FF']],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BFDBFE']],
                    ],
                ]);
            },
        ];
    }
}

// Feuille des details complets
class CultesDetailSheet implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithTitle
{
    protected $cultes;

    public function __construct($cultes)
    {
        $this->cultes = $cultes;
    }

    public function title(): string
    {
        return 'Details Complets';
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function collection()
    {
        return $this->cultes->map(function ($culte) {
            return [
                $culte->titre,
                $culte->type_culte_libelle,
                $culte->categorie_libelle,
                $culte->date_culte->format('d/m/Y'),
                $culte->lieu,
                $culte->statut_libelle,
                $culte->pasteurPrincipal?->nom_complet ?? '',
                $culte->predicateur?->nom_complet ?? '',
                $culte->titre_message ?? '',
                $culte->passage_biblique ?? '',
                $culte->nombre_participants ?? '',
                $culte->nombre_adultes ?? '',
                $culte->nombre_jeunes ?? '',
                $culte->nombre_enfants ?? '',
                $culte->nombre_nouveaux ?? '',
                $culte->nombre_conversions ?? '',
                $culte->nombre_baptemes ?? '',
                $culte->note_globale ?? '',
                $culte->note_louange ?? '',
                $culte->note_message ?? '',
                $culte->note_organisation ?? '',
                strip_tags($culte->notes_pasteur ?? ''),
                strip_tags($culte->points_forts ?? ''),
                strip_tags($culte->points_amelioration ?? ''),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Titre', 'Type', 'Categorie', 'Date', 'Lieu', 'Statut',
            'Pasteur Principal', 'Predicateur', 'Titre Message', 'Passage Biblique',
            'Total Participants', 'Adultes', 'Jeunes', 'Enfants', 'Nouveaux',
            'Conversions', 'Baptemes', 'Note Globale', 'Note Louange',
            'Note Message', 'Note Organisation', 'Notes Pasteur',
            'Points Forts', 'Points Amelioration'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            4 => [
                'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '7C3AED']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // En-tete
                $sheet->mergeCells('A1:X3');
                $sheet->setCellValue('A1', 'DETAILS COMPLETS DES CULTES');
                $sheet->getStyle('A1:X3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '7C3AED']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                $lastRow = $this->cultes->count() + 4;

                // Bordures
                $sheet->getStyle("A4:X{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                    ],
                ]);

                // Largeur des colonnes
                for ($col = 'A'; $col <= 'X'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(12);
                }

                // Colonnes plus larges pour certains champs
                $sheet->getColumnDimension('A')->setWidth(25); // Titre
                $sheet->getColumnDimension('G')->setWidth(20); // Pasteur
                $sheet->getColumnDimension('H')->setWidth(20); // Predicateur
                $sheet->getColumnDimension('I')->setWidth(25); // Titre message
                $sheet->getColumnDimension('J')->setWidth(25); // Passage
                $sheet->getColumnDimension('V')->setWidth(30); // Notes pasteur
                $sheet->getColumnDimension('W')->setWidth(25); // Points forts
                $sheet->getColumnDimension('X')->setWidth(25); // Points amelioration
            },
        ];
    }
}

// Feuille financiere consolidee
class CultesFinancierSheet implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithTitle
{
    protected $cultes;

    public function __construct($cultes)
    {
        $this->cultes = $cultes;
    }

    public function title(): string
    {
        return 'Donnees Financieres';
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function collection()
    {
        return $this->cultes->map(function ($culte) {
            $offrande = $culte->offrande_totale ?? 0;
            $dime = $culte->dime_totale ?? 0;
            $participants = $culte->nombre_participants ?? 0;

            return [
                $culte->titre,
                $culte->date_culte->format('d/m/Y'),
                $culte->type_culte_libelle,
                number_format($participants),
                number_format($offrande, 0) . ' FCFA',
                number_format($dime, 0) . ' FCFA',
                number_format($offrande + $dime, 0) . ' FCFA',
                $participants > 0 ? number_format(($offrande + $dime) / $participants, 0) . ' FCFA' : '0 FCFA',
                $culte->nombre_conversions ?? 0,
                $culte->nombre_baptemes ?? 0,
                $culte->note_globale ? $culte->note_globale . '/10' : '',
                $culte->pasteurPrincipal?->nom_complet ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Titre du Culte',
            'Date',
            'Type',
            'Participants',
            'Offrandes',
            'Dimes',
            'Total Financier',
            'Par Participant',
            'Conversions',
            'Baptemes',
            'Note',
            'Pasteur',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            4 => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // En-tete principal
                $sheet->mergeCells('A1:L3');
                $sheet->setCellValue('A1', 'RAPPORT FINANCIER CONSOLIDE');
                $sheet->getStyle('A1:L3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                $lastRow = $this->cultes->count() + 4;

                // Bordures pour toutes les cellules de donnees
                $sheet->getStyle("A4:L{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                    ],
                ]);

                // Alternance de couleurs pour les lignes
                for ($row = 5; $row <= $lastRow; $row++) {
                    if (($row - 4) % 2 == 0) {
                        $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F0FDF4']],
                        ]);
                    }
                }

                // Alignement des colonnes numeriques
                $sheet->getStyle("D5:K{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Largeur des colonnes
                $columnWidths = [
                    'A' => 25, 'B' => 12, 'C' => 15, 'D' => 12, 'E' => 15,
                    'F' => 15, 'G' => 18, 'H' => 15, 'I' => 12, 'J' => 12,
                    'K' => 10, 'L' => 20
                ];

                foreach ($columnWidths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                // Statistiques financieres en bas
                /** @var int $synthRow */
                $synthRow = $lastRow + 2;

                $sheet->mergeCells("A{$synthRow}:L{$synthRow}");
                $sheet->setCellValue("A{$synthRow}", 'SYNTHESE FINANCIERE');
                $sheet->getStyle("A{$synthRow}:L{$synthRow}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'DC2626']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Calculs financiers
                $totalOffrandes = $this->cultes->sum('offrande_totale');
                $totalDimes = $this->cultes->sum('dime_totale');
                $totalFinancier = $totalOffrandes + $totalDimes;
                $totalParticipants = $this->cultes->sum('nombre_participants');
                $moyenneParParticipant = $totalParticipants > 0 ? $totalFinancier / $totalParticipants : 0;
                $moyenneParCulte = $this->cultes->count() > 0 ? $totalFinancier / $this->cultes->count() : 0;

                $synthRow++;
                $sheet->setCellValue("A{$synthRow}", 'Total Offrandes:');
                $sheet->setCellValue("B{$synthRow}", number_format($totalOffrandes, 0) . ' FCFA');
                $sheet->setCellValue("D{$synthRow}", 'Total Dimes:');
                $sheet->setCellValue("E{$synthRow}", number_format($totalDimes, 0) . ' FCFA');
                $sheet->setCellValue("G{$synthRow}", 'Total General:');
                $sheet->setCellValue("H{$synthRow}", number_format($totalFinancier, 0) . ' FCFA');

                $synthRow++;
                $sheet->setCellValue("A{$synthRow}", 'Moyenne par Culte:');
                $sheet->setCellValue("B{$synthRow}", number_format($moyenneParCulte, 0) . ' FCFA');
                $sheet->setCellValue("D{$synthRow}", 'Moyenne par Participant:');
                $sheet->setCellValue("E{$synthRow}", number_format($moyenneParParticipant, 0) . ' FCFA');
                $sheet->setCellValue("G{$synthRow}", 'Cultes Analyses:');
                $sheet->setCellValue("H{$synthRow}", $this->cultes->count());

                // Style pour les statistiques
                $statsStartRow = $synthRow - 1;
                $sheet->getStyle("A{$statsStartRow}:L{$synthRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FEF3C7']],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'F59E0B']],
                    ],
                ]);
            },
        ];
    }
}
