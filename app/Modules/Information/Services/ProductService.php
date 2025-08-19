<?php

namespace App\Modules\Information\Services;

use App\Modules\Information\Interfaces\CategoryInterface;
use App\Modules\Information\Interfaces\ProductInterface;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductService
{
    public function __construct(
        protected ProductInterface $productRepository,
        protected CategoryInterface $categoryRepository,
    ) {}


    public function getAll(array $data)
    {
        return $this->productRepository->getAll($data);
    }

    public function store(array $data)
    {
        $product = $this->productRepository->store($data);

        if (!$product) {
            return [
                'status' => 'error',
                'message' => 'Tovar qo\'shishda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Tovar muvaffaqiyatli qo\'shildi',
            'data' => $product
        ];
    }

    public function update(int $id, array $data)
    {
        $product = $this->productRepository->getById($id);
        if (!$product) {
            return [
                'status' => 'error',
                'message' => 'Tovar topilmadi',
                'status_code' => 404
            ];
        }

        $product = $this->productRepository->update($product, $data);
        if (!$product) {
            return [
                'status' => 'error',
                'message' => 'Tovar ma\'lumotlarini yangilashda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Tovar ma\'lumotlari muvaffaqiyatli yangilandi',
            'data' => $product
        ];
    }

    public function invertActive(int $id)
    {
        $product = $this->productRepository->invertActive($id);

        return [
            'status' => 'success',
            'message' => 'Tovar faolligi muvaffaqiyatli o\'zgartirildi',
            'data' => $product
        ];
    }

    public function downloadTemplate()
    {
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tovar nomi');
        $sheet->setCellValue('B1', 'Kategoriya nomi');
        $sheet->setCellValue('C1', 'O\'lchov birligi');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadSheet);
        $writer->save($tempFile);

        return response()->download($tempFile, 'product_template.xlsx')->deleteFileAfterSend(true);
    }

    public function import(UploadedFile $file)
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $expectedHeaders = ['Tovar nomi', 'Kategoriya nomi', 'O\'lchov birligi'];
        $highestRow = $sheet->getHighestRow();

        if ($highestRow < 2) {
            return [
                'status' => 'error',
                'message' => 'Faylga tovar kiritish majburiy'
            ];
        }

        if ($sheet->rangeToArray('A1:C1')[0] !== $expectedHeaders) {
            return [
                'status' => 'error',
                'message' => 'Excel fayl ustun sarlavhalari noto\'g\'ri.'
            ];
        }

        $rows = $sheet->getRowIterator(2);

        $productsToInsert = [];
        $productsToUpdate = [];
        $productNamesInFile = [];
        foreach ($rows as $row) {
            $rowIndex = $row->getRowIndex();
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            $isEmptyRow = true;

            foreach ($cellIterator as $cell) {
                $value = trim($cell->getValue());
                $rowData[] = $value;
                if ($value !== '') $isEmptyRow = false;
            }

            if ($isEmptyRow) continue;

            if (count($rowData) < 3) {
                return [
                    'status' => 'error',
                    'message' => "Qator {$rowIndex} da yetarli ustunlar mavjud emas",
                    'status_code' => 422
                ];
            }

            [$name, $categoryName,  $unit] = $rowData;

            if (!$name || !$categoryName ||  !$unit ) {
                return [
                    'status' => 'error',
                    'message' => "Qator {$rowIndex} da to'ldirilmagan maydon mavjud",
                    'status_code' => 422
                ];
            }
            if (in_array(mb_strtolower($name), $productNamesInFile)) {
                continue;
            }

            $productNamesInFile[] = mb_strtolower($name);



            $category = $this->categoryRepository->getByNameOrCreate($categoryName);


            if (!$category) {
                return [
                    'status' => 'error',
                    'message' => "Qator {$rowIndex} da kategoriya aniqlanmadi",
                    'status_code' => 422
                ];
            }

            $productData = [
                'name' => $name,
                'category_id' => $category->id,
                'unit' => $unit,
            ];

            $existingProduct = $this->productRepository->findByName($name);


            if ($existingProduct) {
                $productData['id'] = $existingProduct->id;
                $productsToUpdate[] = $productData;
            } else {
                $productsToInsert[] = $productData;
            }
        }

        if (empty($productsToInsert) && empty($productsToUpdate)) {
            return [
                'status' => 'error',
                'message' => 'Import qilish uchun yangi yoki yangilanadigan tovar topilmadi.',
                'status_code' => 422
            ];
        }

        try {
            $this->productRepository->import($productsToInsert, $productsToUpdate);

            return [
                'status' => 'success',
                'message' => 'Tovarlar muvaffaqiyatli import qilindi.'
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'message' => 'Tovarlarni import qilishda xatolik yuz berdi',
                'error' => $e->getMessage()
            ];
        }
    }
}
