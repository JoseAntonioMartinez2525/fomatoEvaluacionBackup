<?php


namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    protected $users;

    // Recibimos los usuarios ya procesados con el nombre de su archivo PDF asignado
    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Email',
            'Enlace al Reporte PDF', // Nueva columna
            'Contraseña PDF',
        ];
    }

    /**
    * @var User $user
    */
    public function map($user): array
    {
        // Asumimos que en el controlador asignamos la propiedad 'pdf_filename' al objeto user
        $pdfFilename = $user->pdf_filename ?? 'reporte.pdf';
        $pdfPassword = $user->pdf_password ?? '';

        return [
            $user->id,
            $user->name,
            $user->email,
            // Fórmula de Excel para abrir el archivo localmente
            "=HYPERLINK(\"{$pdfFilename}\", \"Abrir Reporte\")",
            $pdfPassword
        ];
    }
}
