<?php

namespace App\Exports\User;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class UserActivityGeneralExport implements WithMultipleSheets
{
    protected $activeUsers;
    protected $inactiveUsers;
    protected $time;
    protected $type;

    public function __construct(Collection $activeUsers, Collection $inactiveUsers, string $time, int $type = null)
    {
        $this->activeUsers = $activeUsers;
        $this->inactiveUsers = $inactiveUsers;
        $this->time = $time;
        $this->type = $type;
    }
    
    /**
     * Returns an array of sheets to be exported.
     *
     * @return array The array of sheets to be exported.
     */
    public function sheets(): array
    {
        return [
            new ActiveUsersSheet($this->activeUsers, $this->time, $this->type),
            new InactiveUsersSheet($this->inactiveUsers, $this->time, $this->type),
        ];
    }
}

class ActiveUsersSheet implements WithEvents, WithTitle
{
    protected $users;
    protected $time;
    protected $type;
    protected $title = 'Usuarios activos';
    public function __construct(Collection $users, string $time, int $type = null)
    {
        $this->users = $users;
        $this->time = $time;
        $this->type = $type;
    }
    /**
     * Registers events for the sheet.
     *
     * @return array The array of events to be registered.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Usuarios Activos
                $sheet->setCellValue('A1', 'Usuarios Activos');
                $sheet->mergeCells('A1:D1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A2', 'ID');
                $sheet->setCellValue('B2', 'Name');
                $sheet->setCellValue('C2', 'RRHH_ID');
                $sheet->setCellValue('D2', 'Fecha último mensaje');

                $row = 3;
                foreach ($this->users as $user) {
                    $lastMessageTime = $user->last_message_time ? new Carbon($user->last_message_time) : '';
                    $formattedTime = $lastMessageTime ? $lastMessageTime->format('d-m-Y H:i:s') : '';
                    $sheet->setCellValue('A' . $row, $user->id);
                    $sheet->setCellValue('B' . $row, $user->name);
                    $sheet->setCellValue('C' . $row, $user->rrhh_id);
                    $sheet->setCellValue('D' . $row, $formattedTime);
                    $row++;
                }

                $activeRange = 'A2:D' . ($row - 1);
                $sheet->getStyle($activeRange)->applyFromArray($this->getBorderStyle());

                // Detalles adicionales
                $sheet->setCellValue('F2', 'Usuarios activos desde hace:');
                $sheet->setCellValue('G2', $this->time);
                if ($this->type !== null) {
                    $sheet->setCellValue('F4', 'Tipo de Actividad:');
                    $recipientType = ($this->type == 1) ? 'Usuarios' : 'Grupos';
                    $sheet->setCellValue('G4', 'Mensajes enviados a ' . $recipientType);
                }
                $sheet->setCellValue('F6', 'Total usuarios activos:');
                $sheet->setCellValue('G6', count($this->users));
                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
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

class InactiveUsersSheet implements WithEvents, WithTitle
{
    protected $users;
    protected $time;
    protected $type;
    protected $title = 'Usuarios inactivos';
    public function __construct(Collection $users, string $time, int $type = null)
    {
        $this->users = $users;
        $this->time = $time;
        $this->type = $type;
    }
    /**
     * Registers events for the sheet.
     *
     * @return array The array of events to be registered.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Usuarios Inactivos
                $sheet->setCellValue('A1', 'Usuarios Inactivos');
                $sheet->mergeCells('A1:D1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A2', 'ID');
                $sheet->setCellValue('B2', 'Name');
                $sheet->setCellValue('C2', 'RRHH_ID');
                $sheet->setCellValue('D2', 'Fecha último mensaje');

                $row = 3;
                foreach ($this->users as $user) {
                    $lastMessageTime = $user->last_message_time ? new Carbon($user->last_message_time) : '';
                    $formattedTime = $lastMessageTime ? $lastMessageTime->format('d-m-Y H:i:s') : '';
                    $sheet->setCellValue('A' . $row, $user->id);
                    $sheet->setCellValue('B' . $row, $user->name);
                    $sheet->setCellValue('C' . $row, $user->rrhh_id);
                    $sheet->setCellValue('D' . $row, $formattedTime);
                    $row++;
                }

                $inactiveRange = 'A2:D' . ($row - 1);
                $sheet->getStyle($inactiveRange)->applyFromArray($this->getBorderStyle());

                // Detalles adicionales
                $sheet->setCellValue('F2', 'Usuarios Inactivos desde hace:');
                $sheet->setCellValue('G2', $this->time);
                if ($this->type !== null) {
                    $sheet->setCellValue('F4', 'Tipo de Actividad:');
                    $recipientType = ($this->type == 1) ? 'Usuarios' : 'Grupos';
                    $sheet->setCellValue('G4', 'Mensajes enviados a ' . $recipientType);
                }
                $sheet->setCellValue('F6', 'Total usuarios inactivos:');
                $sheet->setCellValue('G6', count($this->users));
                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
    /**
     * Get the title of the sheet.
     *
     * @return string The title of the sheet.
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
