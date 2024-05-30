<?php

namespace App\Exports\Group;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class GroupActivityExport implements WithMultipleSheets
{

    protected $groupsActive;
    protected $groupsInactive;
    protected $date;
    public function __construct(Collection $groupsActive, Collection $groupsInactive, string $date)
    {
        $this->groupsActive = $groupsActive;
        $this->groupsInactive = $groupsInactive;
        $this -> date = $date;
    }

    /**
     * Returns the sheets that will be included in the export.
     *
     * @return array An array of sheet instances that will be included in the export.
     */
    public function sheets(): array
    {
        return [
            new GroupActiveSheet($this->groupsActive,$this -> date),
            new GroupInactiveSheet($this->groupsInactive,$this -> date),
        ];
    }
}



class  GroupActiveSheet implements WithEvents, WithTitle
{
    protected $title = 'Grupos Activos';
    protected $groupsActive;
    protected $date;
    public function __construct(Collection $groupsActive, string $date)
    {
        $this->groupsActive = $groupsActive;
        $this -> date = $date;
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
                $sheet -> setCellValue('A1', 'Grupos Activos');
                $sheet -> mergeCells('A1:D1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet -> setCellValue('A2', 'ID');
                $sheet -> setCellValue('B2','Nombre Grupo');
                $sheet -> setCellValue('C2','Archivado');
                $sheet -> setCellValue('D2','Estado');
                $row = 3;
                foreach ($this->groupsActive as $group) {
                    $sheet->setCellValue('A'. $row, $group -> id);
                    $sheet->setCellValue('B'. $row,$group -> name);
                    $sheet->setCellValue('C'. $row, ($group -> archived == 1) ? 'Si' : 'No');
                    $sheet->setCellValue('D'. $row, ($group -> status == 1)? 'Activo' : 'Inactivo');
                    $row++;
                }
                $sheet -> setCellValue('F2','Grupos activos desde hace:');
                $sheet -> setCellValue('G2',$this -> date);

                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                    $sheet->getStyle($column)->getAlignment()->setHorizontal('center');
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


class  GroupInactiveSheet implements WithEvents, WithTitle
{
    protected $title = 'Grupos Inactivos';
    protected $groupsInactive;
    protected $date;
    public function __construct(Collection $groupsInactive,string $date)
    {
        $this->groupsInactive = $groupsInactive;
        $this -> date = $date;
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
                $sheet -> setCellValue('A1', 'Grupos Activos');
                $sheet -> mergeCells('A1:D1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet -> setCellValue('A2', 'ID');
                $sheet -> setCellValue('B2','Nombre Grupo');
                $sheet -> setCellValue('C2','Archivado');
                $sheet -> setCellValue('D2','Estado');
                $row = 3;
                foreach ($this->groupsInactive as $group) {
                    $sheet->setCellValue('A'. $row, $group -> id);
                    $sheet->setCellValue('B'. $row,$group -> name);
                    $sheet->setCellValue('C'. $row, ($group -> archived == 1) ? 'Si' : 'No');
                    $sheet->setCellValue('D'. $row, ($group -> status == 1)? 'Activo' : 'Inactivo');
                    $row++;
                }
                $sheet -> setCellValue('F2','Grupos inactivos desde hace:');
                $sheet -> setCellValue('G2',$this -> date);

                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                    $sheet->getStyle($column)->getAlignment()->setHorizontal('center');
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
