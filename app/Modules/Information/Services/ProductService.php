<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
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
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    public function getAll(array $data)
    {
        try {
            return $this->productRepository->getAll($data, ['id', 'name', 'unit', 'is_active', 'category_id', 'residue', 'markup', 'wholesale_price', 'wholesale_markup']);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Tovarlarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getForResidues(array $data)
    {
        try {
            return $this->productRepository->getAll($data, ['id', 'name', 'unit', 'category_id', 'residue', 'price', 'input_price', 'markup', 'wholesale_price', 'wholesale_markup']);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Tovar qoldiqlarini olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data)
    {
        try {
            $product = $this->productRepository->store($data);

            if (! $product) {
                return [
                    'status' => 'error',
                    'message' => 'Tovar qo\'shishda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Tovar muvaffaqiyatli qo\'shildi',
                'data' => $product,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Tovar qo\'shishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $product = $this->productRepository->getById($id);
            if (! $product) {
                return [
                    'status' => 'error',
                    'message' => 'Tovar topilmadi',
                    'status_code' => 404,
                ];
            }

            $product = $this->productRepository->update($product, $data);
            if (! $product) {
                return [
                    'status' => 'error',
                    'message' => 'Tovar ma\'lumotlarini yangilashda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Tovar ma\'lumotlari muvaffaqiyatli yangilandi',
                'data' => $product,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Tovar ma\'lumotlarini yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id)
    {
        try {
            $product = $this->productRepository->invertActive($id);

            return [
                'status' => 'success',
                'message' => 'Tovar faolligi muvaffaqiyatli o\'zgartirildi',
                'data' => $product,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Tovar faolligini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }

    public function downloadTemplate()
    {
        try {
            $spreadSheet = new Spreadsheet;
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
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return response()->json([
                'status' => 'error',
                'message' => 'Template yuklab olishda xatolik yuz berdi',
            ], 500);
        }
    }

    public function downloadUpdatePriceTemplate()
    {
        try {
            $spreadSheet = new Spreadsheet;
            $sheet = $spreadSheet->getActiveSheet();

            // Set headers
            $sheet->setCellValue('A1', 'Tovar ID');
            $sheet->setCellValue('B1', 'Tovar nomi');
            $sheet->setCellValue('C1', 'Kategoriya nomi');
            $sheet->setCellValue('D1', 'Kirim narxi');
            $sheet->setCellValue('E1', 'Ustama');
            $sheet->setCellValue('F1', 'Sotish narxi');

            $sheet->getStyle('A1:H1')->getFont()->setBold(true);

            // Fetch products from repository
            $products = $this->productRepository->getAll([], ['id', 'name', 'price', 'category_id', 'input_price', 'markup', 'wholesale_price', 'wholesale_markup'], false);

            // Populate data starting from row 2
            $row = 2;
            foreach ($products as $product) {
                $sheet->setCellValue('A'.$row, $product->id);
                $sheet->setCellValue('B'.$row, $product->name);
                $sheet->setCellValue('C'.$row, $product->category->name ?? '');
                $sheet->setCellValue('D'.$row, $product->input_price ?? '');
                $sheet->setCellValue('E'.$row, $product->markup ?? '');
                $sheet->setCellValue('F'.$row, $product->price ?? '');
                $sheet->setCellValue('G'.$row, $product->wholesale_price ?? '');
                $sheet->setCellValue('H'.$row, $product->wholesale_markup ?? '');
                $row++;
            }

            // Auto-size all columns
            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
            $writer = new Xlsx($spreadSheet);
            $writer->save($tempFile);

            return response()->download($tempFile, 'product_price_template.xlsx')->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return response()->json([
                'status' => 'error',
                'message' => 'Narx template yuklab olishda xatolik yuz berdi',
            ], 500);
        }
    }

    public function import(UploadedFile $file)
    {
        try {
            $reader = IOFactory::createReaderForFile($file->getRealPath());
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = 'C';

            if ($highestRow < 2) {
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);

                return [
                    'status' => 'error',
                    'message' => 'Faylga tovar kiritish majburiy',
                ];
            }

            $dataArray = $sheet->rangeToArray(
                'A2:'.$highestColumn.$highestRow,
                null,        // nullValue
                true,        // calculateFormulas
                false,       // formatData
                false        // returnCellRef
            );

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet, $sheet, $reader);
            gc_collect_cycles();

            $productsToInsert = [];
            $productsToUpdate = [];
            $productNamesInFile = [];

            foreach ($dataArray as $index => $rowData) {
                $rowIndex = $index + 2;
                $rowData = array_map('trim', $rowData);

                $isEmptyRow = true;
                foreach ($rowData as $value) {
                    if ($value !== '') {
                        $isEmptyRow = false;
                        break;
                    }
                }

                if ($isEmptyRow) {
                    continue;
                }

                if (count($rowData) < 3) {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da yetarli ustunlar mavjud emas",
                        'status_code' => 422,
                    ];
                }

                [$name, $categoryName, $unit] = $rowData;

                if (! $name || ! $categoryName || ! $unit) {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da to'ldirilmagan maydon mavjud",
                        'status_code' => 422,
                    ];
                }

                if (in_array(mb_strtolower($name), $productNamesInFile)) {
                    continue;
                }

                $productNamesInFile[] = mb_strtolower($name);

                $category = $this->categoryRepository->getByNameOrCreate($categoryName);
                if (! $category) {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da kategoriya aniqlanmadi",
                        'status_code' => 422,
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

            unset($dataArray);

            if (empty($productsToInsert) && empty($productsToUpdate)) {
                return [
                    'status' => 'error',
                    'message' => 'Import qilish uchun yangi yoki yangilanadigan tovar topilmadi.',
                    'status_code' => 422,
                ];
            }

            $this->productRepository->import($productsToInsert, $productsToUpdate);

            return [
                'status' => 'success',
                'message' => 'Tovarlar muvaffaqiyatli import qilindi.',
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Tovarlarni import qilishda xatolik yuz berdi',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function updatePricesFromTemplate(UploadedFile $file)
    {
        try {
            $reader = IOFactory::createReaderForFile($file->getRealPath());
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = 'H';

            if ($highestRow < 2) {
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);

                return [
                    'status' => 'error',
                    'message' => 'Faylga mahsulot ma\'lumotlari kiritish majburiy',
                ];
            }

            $dataArray = $sheet->rangeToArray(
                'A2:'.$highestColumn.$highestRow,
                null,        // nullValue
                true,        // calculateFormulas
                false,       // formatData
                false        // returnCellRef
            );

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet, $sheet, $reader);
            gc_collect_cycles();

            $priceData = [];
            $processedIds = [];

            foreach ($dataArray as $index => $rowData) {
                $rowIndex = $index + 2;
                $rowData = array_map('trim', $rowData);

                $isEmptyRow = true;
                foreach ($rowData as $value) {
                    if ($value !== '') {
                        $isEmptyRow = false;
                        break;
                    }
                }

                if ($isEmptyRow) {
                    continue;
                }

                if (count($rowData) < 5) {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da yetarli ustunlar mavjud emas",
                        'status_code' => 422,
                    ];
                }

                [$id, $name, $categoryName, $inputPrice, $markup, $price, $wholesalePrice, $wholesaleMarkup] = $rowData;

                if (! $id || $inputPrice === '' || $price === '' || $wholesalePrice === '' || $wholesaleMarkup === '') {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da ID yoki narx to'ldirilmagan",
                        'status_code' => 422,
                    ];
                }

                if (! is_numeric($id)) {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da ID raqam bo'lishi kerak",
                        'status_code' => 422,
                    ];
                }

                if (! is_numeric($inputPrice) || $inputPrice < 0) {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da kirim narxi raqam bo'lishi kerak",
                        'status_code' => 422,
                    ];
                }

                if (! is_numeric($price) || $price < 0) {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da narx musbat raqam bo'lishi kerak",
                        'status_code' => 422,
                    ];
                }

                if ((float) $price < (float) $inputPrice) {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da sotish narxi kirim narxidan kam bo'lishi mumkin emas",
                        'status_code' => 422,
                    ];
                }

                if (in_array($id, $processedIds)) {
                    return [
                        'status' => 'error',
                        'message' => "Qator {$rowIndex} da takroriy ID mavjud",
                        'status_code' => 422,
                    ];
                }

                $processedIds[] = $id;

                $markup = 0;
                if ((float) $inputPrice > 0) {
                    $markup = ((float) $price - (float) $inputPrice) / (float) $inputPrice * 100;
                }
                $wholesaleMarkup = 0;
                if ((float) $inputPrice > 0) {
                    $wholesaleMarkup = ((float) $wholesalePrice - (float) $inputPrice) / (float) $inputPrice * 100;
                }

                $priceData[] = [
                    'id' => (int) $id,
                    'price' => (float) $price,
                    'markup' => $markup,
                    'wholesale_price' => (float) $wholesalePrice,
                    'wholesale_markup' => $wholesaleMarkup,
                    // avoid not null violation
                    'name' => '',
                    'category_id' => 0,
                    'unit' => '',
                    'input_price' => 0,
                    'is_active' => true,
                    'residue' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            unset($dataArray);

            if (empty($priceData)) {
                return [
                    'status' => 'error',
                    'message' => 'Yangilanish uchun ma\'lumot topilmadi',
                    'status_code' => 422,
                ];
            }

            $this->productRepository->upsert($priceData, ['id'], ['price', 'markup', 'wholesale_price', 'wholesale_markup']);

            return [
                'status' => 'success',
                'message' => count($priceData).' ta mahsulotning narxi muvaffaqiyatli yangilandi',
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Narxlarni yangilashda xatolik yuz berdi',
                'error' => $e->getMessage(),
            ];
        }
    }
}
