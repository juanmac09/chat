<?php

namespace App\Exports\Group;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class GroupInfoExport  implements WithMultipleSheets
{
    protected $groups;
    protected $amountParticipants;

    public function __construct(Collection $groups, Collection $amountParticipants) {
        $this->groups = $groups;
        $this ->amountParticipants = $amountParticipants;
    }
    /**
     * Returns the sheets that will be included in the export.
     *
     * @return array An array of sheet instances that will be included in the export.
     */
    public function sheets(): array
    {
        return [
            new GroupInfoSheet($this -> groups,$this ->amountParticipants),
            new GroupParticipnatsSheet($this -> groups),
        ];
    }
}


class  GroupInfoSheet implements WithEvents, WithTitle
{
    protected $title = 'Información de grupos';
    protected $groups;
    protected $amountParticipants;

    public function __construct(Collection $groups, Collection $amountParticipants) {
        $this->groups = $groups;
        $this ->amountParticipants = $amountParticipants;
    }
    /**
     * Registers the events to be triggered after the sheet is created.
     *
     * @return array The array of event classes and their respective callbacks.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet -> setCellValue('A1', 'Información Grupos');
                $sheet -> mergeCells('A1:H1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet -> setCellValue('A2', 'ID');
                $sheet -> setCellValue('B2','Nombre Grupo');
                $sheet -> setCellValue('C2','Nombre Dueño');
                $sheet -> setCellValue('D2','RRHH_ID Dueño');
                $sheet -> setCellValue('E2','Cantidad de Participantes');
                $sheet -> setCellValue('F2','Archivado');
                $sheet -> setCellValue('G2','Estado');
                $sheet -> setCellValue('H2','Fecha de Creación');
                $row = 3;
                            
                foreach ($this->groups as $group) {

                    $amountParticipants = $this -> amountParticipants -> first(function ($participant) use($group){
                        return $participant-> id == $group['id'];
                    });            
                    $sheet->setCellValue('A'. $row, $group['id']);
                    $sheet->setCellValue('B'. $row,$group['name']);
                    $sheet->setCellValue('C'. $row, $group['owner']['name']);
                    $sheet->setCellValue('D'. $row, $group['owner']['rrhh_id']);
                    $sheet->setCellValue('E'. $row, $amountParticipants  -> users_quantity);
                    $sheet->setCellValue('F'. $row, ($group['archived'] == 1) ? 'Si' : 'No');
                    $sheet->setCellValue('G'. $row,($group['status'] == 1) ? 'Activo' : 'Inactivo');
                    $sheet->setCellValue('H'. $row, $group['created_date']);
                    $row++;
                }
                
                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                    $sheet->getStyle($column)->getAlignment()->setHorizontal('center');
                }

                $activeRange = 'A2:H' . ($row - 1);
                $sheet->getStyle($activeRange)->applyFromArray($this->getBorderStyle());
            }
        ];
    }
    /**
     * Get the style for the border of the sheet.
     *
     * @return array The array of styles for the border.
     */
    private function getBorderStyle(): array
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
    }
    /**
     * Get the title of the sheet.
     *
     * @return string The title of the sheet.
     */
    public function title(): string
    {
        return $this->title;
    }
}


class  GroupParticipnatsSheet implements WithEvents, WithTitle
{
    protected $title = 'Información Participantes';
    protected $groups;

    public function __construct(Collection $groups) {
        $this->groups = $groups;
    }
    /**
     * Registers the events to be triggered after the sheet is created.
     *
     * @return array The array of event classes and their respective callbacks.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet -> setCellValue('A1', 'Información Participantes de los Grupos');
                $sheet -> mergeCells('A1:D1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet -> setCellValue('A2', 'ID Grupo');
                $sheet -> setCellValue('B2','Nombre Grupo');
                $sheet -> setCellValue('C2','Nombre usuario');
                $sheet -> setCellValue('D2','RRHH_ID Usuario');
                $row = 3;
                foreach ($this -> groups as $group) {
                    $start_row = $row;
                    $sheet->setCellValue('A'. $row, $group['id']);
                    $sheet->setCellValue('B'. $row, $group['name']);
                    // dd(count($group['participants']));
                    foreach ($group['participants'] as $participant) {
                        $sheet->setCellValue('C'. $row, $participant['name']);
                        $sheet->setCellValue('D'. $row, $participant['rrhh_id']);
                        $row++;
                    }
                    $end_row = $row -1;
                    $sheet -> mergeCells('A'.$start_row.':A'.$end_row);
                    $sheet -> mergeCells('B'.$start_row.':B'.$end_row);
                }

                foreach (range('A', 'D') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                    $sheet->getStyle($column)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle($column)->getAlignment()->setVertical('center');
                }
                $activeRange = 'A2:D' . ($row - 1);
                $sheet->getStyle($activeRange)->applyFromArray($this->getBorderStyle());
            }
        ];
    }
    /**
     * Get the style for the border of the sheet.
     *
     * @return array The array of styles for the border.
     */
    private function getBorderStyle(): array
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
    }
    /**
     * Get the title of the sheet.
     *
     * @return string The title of the sheet.
     */
    public function title(): string
    {
        return $this->title;
    }
}
