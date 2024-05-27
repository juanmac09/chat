<?php

namespace App\Exports\User;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UserActivityGeneralExport implements WithEvents
{
    protected $activeUsers;
    protected $inactiveUsers;

    public function __construct(Collection $activeUsers, Collection $inactiveUsers)
    {
        $this->activeUsers = $activeUsers;
        $this->inactiveUsers = $inactiveUsers;
    }

    /**
     * Registers the events to be triggered after the sheet is created.
     *
     * @return array An array of event classes to be triggered after the sheet is created.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Usuarios Activos
                $sheet->setCellValue('A1', 'Usuarios Activos');
                $sheet->mergeCells('A1:C1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A2', 'ID');
                $sheet->setCellValue('B2', 'Name');
                $sheet->setCellValue('C2', 'Fecha último mensaje');

                $row = 3;
                foreach ($this->activeUsers as $user) {
                    $lastMessageTime = $user->last_message_time ? $user->last_message_time : '';
                    $sheet->setCellValue('A' . $row, $user->id);
                    $sheet->setCellValue('B' . $row, $user->name);
                    $sheet->setCellValue('C' . $row, $lastMessageTime);
                    $row++;
                }

                $activeRange = 'A2:C' . ($row - 1);
                $sheet->getStyle($activeRange)->applyFromArray($this->getBorderStyle());

                // Usuarios Inactivos
                $startRow = $row + 2;
                $sheet->setCellValue('A' . $startRow, 'Usuarios Inactivos');
                $sheet->mergeCells('A' . $startRow . ':C' . $startRow);
                $sheet->getStyle('A' . $startRow)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A' . ($startRow + 1), 'ID');
                $sheet->setCellValue('B' . ($startRow + 1), 'Name');
                $sheet->setCellValue('C' . ($startRow + 1), 'Fecha último mensaje');

                $row = $startRow + 2;
                foreach ($this->inactiveUsers as $user) {
                    $lastMessageTime = $user->last_message_time ? $user->last_message_time : '';
                    $sheet->setCellValue('A' . $row, $user->id);
                    $sheet->setCellValue('B' . $row, $user->name);
                    $sheet->setCellValue('C' . $row, $lastMessageTime);
                    $row++;
                }

                $inactiveRange = 'A' . ($startRow + 1) . ':C' . ($row - 1);
                $sheet->getStyle($inactiveRange)->applyFromArray($this->getBorderStyle());

                foreach (range('A', 'C') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }


    /**
     * Get the border style for the Excel sheet.
     *
     * @return array An array of style properties for the borders.
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
}
