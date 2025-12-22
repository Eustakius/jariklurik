<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ExcelController extends BaseController
{
    public function export()
    {
        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Isi header
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'Kategori');

        // Daftar pilihan dropdown
        $kategoriList = ['Elektronik', 'Pakaian', 'Makanan', 'Minuman'];

        // Buat string untuk data validation
        $listString = '"' . implode(',', $kategoriList) . '"';

        // Buat dropdown di kolom B2 sampai B20
        for ($row = 2; $row <= 20; $row++) {
            $validation = $sheet->getCell('B' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input salah');
            $validation->setError('Pilih salah satu kategori dari dropdown.');
            $validation->setPromptTitle('Kategori');
            $validation->setPrompt('Pilih salah satu kategori yang tersedia.');
            $validation->setFormula1($listString);
        }

        // Simpan ke output
        $fileName = 'produk-dropdown.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Set header supaya browser langsung download
        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename="' . $fileName . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($this->saveToString($writer));
    }

    // Helper untuk menyimpan spreadsheet ke string
    private function saveToString(Xlsx $writer): string
    {
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }
}
