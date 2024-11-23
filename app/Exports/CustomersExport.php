<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomersExport implements FromCollection, WithMapping, WithHeadings, WithEvents, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Customer::all();
    }

    public function headings(): array
    {
        return [
            'S.no',
            'Name',
            'Email',
            'Phone',
            'Addresses',
            'Created At',
            'Updated At'
        ];
    }

    public function map($customer): array
    {
        static $serialNumber = 1;
        $addresses = $customer->addresses;
        $rows = [];

        if ($addresses->isEmpty()) {
            $rows[] = [
                'S.no' => $serialNumber++,
                'Name' => $customer->name,
                'Email' => $customer->email,
                'Phone' => $customer->phone ?? 'N/A',
                'Addresses' => 'N/A',
                'Created At' => $customer->created_at->toDateTimeString(),
                'Updated At' => $customer->updated_at->toDateTimeString(),
            ];
        } else {
            foreach ($addresses as $index => $address) {
                $addressText = $address->address_line1 . ', ' .
                    ($address->address_line2 ? $address->address_line2 . ', ' : '') .
                    $address->city . ', ' .
                    $address->state . ', ' .
                    $address->postal_code;

                $row = [
                    'S.no' => $index === 0 ? $serialNumber : '',
                    'Name' => $index === 0 ? $customer->name : '',
                    'Email' => $index === 0 ? $customer->email : '',
                    'Phone' => $index === 0 ? $customer->phone ?? 'N/A' : '',
                    'Addresses' => $addressText,
                    'Created At' => $index === 0 ? $customer->created_at->toDateTimeString() : '',
                    'Updated At' => $index === 0 ? $customer->updated_at->toDateTimeString() : '',
                ];

                $rows[] = $row;
            }
            $serialNumber++;
        }

        return $rows;
    }

    // Style Headers and Autofit
    public function styles($sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4CAF50' // Green color for header
                ]
            ],
            'borders' => [
                'outline' => [
                    // 'borderStyle' => Borders::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Autofit all columns (Use the correct method without getDelegate)
        for ($col = 'A'; $col !== 'H'; $col++) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            // Style other cells (data rows)
            1 => [
                'font' => [
                    'size' => 11
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Autofit all columns directly using getColumnDimension
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
            }
        ];
    }
}
