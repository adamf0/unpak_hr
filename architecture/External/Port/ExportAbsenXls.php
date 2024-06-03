<?php

namespace Architecture\External\Port;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExportAbsenXls implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    public function __construct(private Collection $datas, private $headers = []){}

    function indexToExcelHeader($index) {
        $excelHeader = '';
        $index++;
        while ($index > 0) {
            $remainder = ($index - 1) % 26;
            $excelHeader = chr(65 + $remainder) . $excelHeader;
            $index = intval(($index - 1) / 26);
        }
        return $excelHeader;
    }
    function getNameFromNumber($num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }
    function getUsedCell($cells) {
        return array_fill_keys(array_map(fn($i) => $this->indexToExcelHeader($i), range(0, $cells - 1)), 40);
    }

    public function collection()
    {
        return $this->datas;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function columnWidths(): array
    {
        return $this->getUsedCell(count($this->headers));
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle("A1:".($this->getNameFromNumber($this->datas->count())."1"))->getFont()->setBold(true);

        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Define border styles
        $borderStyleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Apply border styles to each cell
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray($borderStyleArray);

        // Enable text wrap for all cells
        foreach ($sheet->getColumnIterator() as $column) {
            foreach ($column->getCellIterator() as $cell) {
                $cell->getStyle()->getAlignment()->setWrapText(true);
            }
        }

        return [
            // Optionally, you can set specific styles for headers or other rows
        ];
    }
}
