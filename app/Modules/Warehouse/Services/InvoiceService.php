<?php

namespace App\Modules\Warehouse\Services;

use App\Helpers\TelegramBugNotifier;
use App\Modules\Cashbox\Enums\OtherCalculationTypesEnum;
use App\Modules\Cashbox\Interfaces\OtherCalculationInterface;
use App\Modules\Information\Interfaces\OtherSourceInterface;
use App\Modules\Information\Interfaces\ProductInterface;
use App\Modules\Information\Interfaces\SupplierInterface;
use App\Modules\Warehouse\Enums\InvoiceTypesEnum;
use App\Modules\Warehouse\Interfaces\InvoiceInterface;
use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;
use App\Modules\Warehouse\Repositories\InvoiceProductRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        protected InvoiceInterface $invoiceRepository,
        protected InvoiceProductRepository $invoiceProductRepository,
        protected ProductInterface $productRepository,
        protected SupplierCalculationInterface $supplierCalculationRepository,
        protected SupplierInterface $supplierRepository,
        protected OtherSourceInterface $otherSourceRepository,
        protected OtherCalculationInterface $otherCalculationRepository,
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    public function getByTypes(array $types, array $data)
    {
        try {
            return $this->invoiceRepository->getByTypes($types, $data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Fakturalarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getByIdWithProducts(int $id)
    {
        try {
            return $this->invoiceRepository->getByIdWithProducts($id);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Faktura ma\'lumotlarini olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data)
    {
        try {
            $type = $data['type'];
            switch ($type) {
                case InvoiceTypesEnum::SUPPLIER_INPUT->value:
                case InvoiceTypesEnum::SUPPLIER_OUTPUT->value:
                case InvoiceTypesEnum::OTHER_INPUT->value:
                case InvoiceTypesEnum::OTHER_OUTPUT->value:
                    return $this->storeInvoice($data);
                default:
                    return [
                        'status' => 'error',
                        'message' => 'Mavjud bo\'lmagan turni yubordingiz',
                    ];
            }
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Faktura yaratishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $type = $data['type'];
            switch ($type) {
                case InvoiceTypesEnum::SUPPLIER_INPUT->value:
                case InvoiceTypesEnum::SUPPLIER_OUTPUT->value:
                case InvoiceTypesEnum::OTHER_INPUT->value:
                case InvoiceTypesEnum::OTHER_OUTPUT->value:
                    return $this->updateInvoice($id, $data);
                default:
                    return [
                        'status' => 'error',
                        'message' => 'Mavjud bo\'lmagan turni yubordingiz',
                    ];
            }
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Fakturani yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function delete(int $id)
    {
        try {
            DB::beginTransaction();

            $invoice = $this->invoiceRepository->findById($id);

            $this->deleteSupplierCalculation($id);

            $this->deleteOtherCalculation($id);

            $result = $this->invoiceRepository->delete($invoice);

            if (! $result) {
                DB::rollBack();

                return [
                    'status' => 'error',
                    'message' => 'Fakturani o\'chirishda xatolik yuz berdi',
                ];
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Faktura muvaffaqiyatli o\'chirildi',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Fakturani o\'chirishda xatolik yuz berdi',
            ];
        }
    }

    private function storeInvoice(array $data)
    {
        try {
            DB::beginTransaction();

            $type = $data['type'];
            $products = $data['products'];

            if ($type === InvoiceTypesEnum::SUPPLIER_OUTPUT->value || $type === InvoiceTypesEnum::OTHER_OUTPUT->value) {
                $residueNotEnough = $this->checkProductResidues($products);
                if ($residueNotEnough) {
                    return [
                        'status' => 'error',
                        'message' => $residueNotEnough,
                    ];
                }

                $products = $this->makeProductCountsNegative($products);
            }

            $data['products_count'] = array_sum(array_column($products, 'count'));
            $data['total_price'] = $this->getTotalPrice($products);

            // Date formatini o'zgartirish (dd.mm.yyyy -> Y-m-d)
            $data['date'] = Carbon::parse($data['date'])->format('Y-m-d');

            $invoice = $this->invoiceRepository->store($data);

            $this->invoiceProductRepository->store($this->cleanAndPrepareTradeProducts($products, $invoice->id));

            // Create supplier calculation if supplier_id exists
            if (! empty($data['supplier_id'])) {
                $this->createSupplierCalculation($invoice, $data);
            }

            // Create other calculation if invoice type is OTHER_INPUT or OTHER_OUTPUT
            if ($invoice->type === InvoiceTypesEnum::OTHER_INPUT->value || $invoice->type === InvoiceTypesEnum::OTHER_OUTPUT->value) {
                $this->createOtherCalculation($invoice, $data);
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Faktura muvaffaqiyatli qo\'shildi',
                'data' => $invoice,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Faktura yaratishda xatolik yuz berdi',
            ];
        }
    }

    private function updateInvoice(int $id, array $data)
    {

        $type = $data['type'];
        $products = $data['products'];

        $invoice = $this->invoiceRepository->findById($id);

        if (! $invoice) {
            return [
                'status' => 'error',
                'message' => 'Ushbu faktura topilmadi',
                'status_code' => 404,
            ];
        }

        $normalProducts = array_filter($products, fn ($product) => $product['action'] === 'normal');
        $productsForInsert = array_filter($products, fn ($product) => $product['action'] === 'add');
        $productsForUpdate = array_filter($products, fn ($product) => $product['action'] === 'edit');
        $productIdsForDelete = array_map(fn ($product) => $product['id'], array_filter($products, fn ($product) => $product['action'] === 'delete'));

        $savableProducts = array_merge($normalProducts, $productsForInsert, $productsForUpdate);

        // OUTPUT bo'lsa, count manfiy qilish kerak va qoldiq tekshirish
        if ($type === InvoiceTypesEnum::SUPPLIER_OUTPUT->value || $type === InvoiceTypesEnum::OTHER_OUTPUT->value) {
            // Qoldiq tekshirish (update uchun maxsus hisoblash)
            if (count($productsForUpdate) > 0) {
                $residueNotEnough = $this->checkProductResiduesForUpdate($invoice->id, $productsForUpdate);
                if ($residueNotEnough) {
                    return [
                        'status' => 'error',
                        'message' => $residueNotEnough,
                    ];
                }
            }

            // Yangi qo'shiladigan tovarlar uchun oddiy tekshirish
            if (count($productsForInsert) > 0) {
                $residueNotEnough = $this->checkProductResidues($productsForInsert);
                if ($residueNotEnough) {
                    return [
                        'status' => 'error',
                        'message' => $residueNotEnough,
                    ];
                }
            }

            $productsForInsert = $this->makeProductCountsNegative($productsForInsert);
            $productsForUpdate = $this->makeProductCountsNegative($productsForUpdate);
        }
        $allHaveAction = collect($products)->every(function ($product) {
            return array_key_exists('action', $product);
        });

        if (! $allHaveAction) {
            return [
                'status' => 'error',
                'message' => 'Barcha tovarlarda action bo\'lishi kerak',
            ];
        }

        $productIds = collect($productsForUpdate)
            ->pluck('id')
            ->merge($productIdsForDelete)
            ->unique()
            ->values()
            ->toArray();

        $someIdMissed = $this->validateInvoiceProductsIsExist($invoice->id, $productIds);

        if ($someIdMissed) {
            return $someIdMissed;
        }

        $data['products_count'] = array_sum(array_column($savableProducts, 'count'));
        $data['total_price'] = $this->getTotalPrice($savableProducts);

        // Generate history entry before updating
        $productChanges = $this->getProductChanges($invoice->id, $products);
        $historyEntry = $this->generateHistoryEntry($invoice, $data, $productChanges);

        // Get current history and add new entry only if there are changes
        $currentHistory = $invoice->history ?? [];
        if (! empty($historyEntry['description'])) {
            $currentHistory[] = $historyEntry;
        }

        DB::beginTransaction();

        try {
            $updatedInvoice = $this->invoiceRepository->update($invoice, [
                'date' => \Carbon\Carbon::createFromFormat('d.m.Y', $data['date'])->format('Y-m-d'),
                'supplier_id' => $data['supplier_id'] ?? null,
                'other_source_id' => $data['other_source_id'] ?? null,
                'products_count' => abs($data['products_count']),
                'total_price' => abs($data['total_price']),
                'user_id' => Auth::id(),
                'type' => $data['type'],
                'commentary' => $data['commentary'] ?? null,
                'history' => $currentHistory,
            ]);

            if (! $updatedInvoice) {
                DB::rollBack();

                return [
                    'status' => 'error',
                    'message' => 'Fakturani tahrirlashda muammo yuz berdi',
                ];
            }

            if (count($productsForInsert) > 0) {
                $this->invoiceProductRepository->store($this->cleanAndPrepareTradeProducts($productsForInsert, $invoice->id));
            }

            if (count($productsForUpdate) > 0) {
                $this->invoiceProductRepository->update($this->cleanAndPrepareTradeProducts($productsForUpdate, $invoice->id, true));
            }

            if (count($productIdsForDelete) > 0) {
                $this->invoiceProductRepository->deleteByIds($productIdsForDelete);
            }

            // Update supplier calculation if supplier_id exists
            if (! empty($data['supplier_id'])) {
                $this->updateSupplierCalculation($updatedInvoice, $data);
            } else {
                // Delete calculation if supplier_id is null
                $this->deleteSupplierCalculation($updatedInvoice->id);
            }

            // Update other calculation for OTHER_INPUT or OTHER_OUTPUT types
            if ($updatedInvoice->type === InvoiceTypesEnum::OTHER_INPUT->value || $updatedInvoice->type === InvoiceTypesEnum::OTHER_OUTPUT->value) {
                $this->updateOtherCalculation($updatedInvoice, $data);
            } else {
                // Delete other calculation if type is not OTHER_INPUT or OTHER_OUTPUT
                $this->deleteOtherCalculation($updatedInvoice->id);
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Faktura muvaffaqiyatli tahrirlandi',
                'data' => $updatedInvoice,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => 'Fakturani tahrirlashda xatolik yuz berdi: '.$e->getMessage(),
            ];
        }
    }

    private function checkProductResiduesForUpdate(int $invoiceId, array $products): ?string
    {
        // Eski invoice_products ni olish
        $oldInvoiceProducts = $this->invoiceProductRepository->getByInvoiceId($invoiceId);
        $oldProductsMap = collect($oldInvoiceProducts)->keyBy('product_id');

        // Hozirgi product qoldiqlarini olish
        $productIds = collect($products)->pluck('product_id')->toArray();
        $residues = $this->productRepository->getForCheckResidue($productIds);

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $newCount = abs($product['count']); // Yangi count (musbat)
            $oldCount = abs($oldProductsMap->get($productId)->count ?? 0); // Eski count (musbat)
            $currentResidue = $residues[$productId]->residue ?? 0;

            // Qo'shimcha chiqariladigan miqdor
            $additionalCount = $newCount - $oldCount;

            if ($additionalCount > 0 && $additionalCount > $currentResidue) {
                return "{$productId} - ID li mahsulot uchun yetarli qoldiq mavjud emas. Qo'shimcha {$additionalCount} ta kerak, lekin {$currentResidue} ta mavjud.";
            }
        }

        return null;
    }

    private function checkProductResidues(array $products): ?string
    {
        $productIds = collect($products)->pluck('product_id')->toArray();
        $residues = $this->productRepository->getForCheckResidue($productIds);

        foreach ($products as $product) {
            $residue = $residues[$product['product_id']] ?? null;
            if (! $residue || $product['count'] > $residue->residue) {
                return "{$product['product_id']} - ID li mahsulot uchun yetarli qoldiq mavjud emas.";
            }
        }

        return null;
    }

    private function validateInvoiceProductsIsExist(int $invoiceId, array $productIds): ?array
    {
        $someIdMissed = array_diff($this->invoiceProductRepository->findMissingIds($invoiceId, $productIds), $productIds);
        if ($someIdMissed) {
            return [
                'status' => 'error',
                'message' => 'Mavjud bo\'lmagan tovarni tahrirlash yoki o\'chirish mumkin emas',
            ];
        }

        return null;
    }

    private function makeProductCountsNegative(array $products): array
    {
        return array_map(function ($product) {
            $product['count'] = -abs((float) $product['count']);

            return $product;
        }, $products);
    }

    private function getTotalPrice(array $products)
    {
        return array_reduce($products, function ($carry, $item) {
            return $carry + ($item['input_price'] * $item['count']);
        }, 0);
    }

    public function cleanAndPrepareTradeProducts(array $products, int $invoiceId, bool $withId = false): array
    {
        return array_map(function ($product) use ($invoiceId, $withId) {
            $data = [
                'invoice_id' => $invoiceId,
                'product_id' => $product['product_id'],
                'count' => $product['count'],
                'price' => $product['price'],
                'total_price' => abs($product['input_price'] * $product['count']),
                'date' => Carbon::now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
                'input_price' => $product['input_price'],
                'wholesale_price' => $product['wholesale_price'] ?? 0,
            ];
            if (! empty($withId)) {
                $data['id'] = $product['id'];
            }

            return $data;
        }, $products);
    }

    private function createSupplierCalculation($invoice, $data)
    {
        $calculationValue = $this->getSupplierCalculationValue($invoice->type, $invoice->total_price);
        if ($calculationValue !== null) {
            $this->supplierCalculationRepository->create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => $invoice->user_id,
                'invoice_id' => $invoice->id,
                'type' => $invoice->type,
                'value' => $calculationValue,
                'date' => $invoice->date,
            ]);
        }
    }

    private function updateSupplierCalculation($invoice, $data)
    {
        $existingCalculation = $this->supplierCalculationRepository->getByInvoiceId($invoice->id);
        $calculationValue = $this->getSupplierCalculationValue($invoice->type, $invoice->total_price);

        if ($calculationValue !== null && $existingCalculation) {
            $this->supplierCalculationRepository->update($existingCalculation->id, [
                'supplier_id' => $data['supplier_id'],
                'user_id' => $invoice->user_id,
                'type' => $invoice->type,
                'value' => $calculationValue,
                'date' => $invoice->date,
            ]);
        } elseif ($calculationValue !== null && ! $existingCalculation) {
            $this->createSupplierCalculation($invoice, $data);
        } elseif ($calculationValue === null && $existingCalculation) {
            $this->supplierCalculationRepository->delete($existingCalculation->id);
        }
    }

    private function deleteSupplierCalculation($invoiceId)
    {
        $existingCalculation = $this->supplierCalculationRepository->getByInvoiceId($invoiceId);

        if ($existingCalculation) {
            $this->supplierCalculationRepository->delete($existingCalculation->id);
        }
    }

    private function createOtherCalculation($invoice, $data)
    {
        $calculationValue = $this->getOtherCalculationValue($invoice->type, $invoice->total_price);
        $calculationType = $this->getOtherCalculationType($invoice->type);

        if ($calculationValue !== null && $calculationType !== null) {
            $this->otherCalculationRepository->create([
                'user_id' => $invoice->user_id,
                'payment_id' => null, // Not linked to payment
                'cost_id' => null, // Not linked to cost
                'invoice_id' => $invoice->id, // Link to the invoice
                'type' => $calculationType,
                'value' => $calculationValue,
                'date' => $invoice->date,
            ]);
        }
    }

    private function updateOtherCalculation($invoice, $data)
    {
        $existingCalculation = $this->otherCalculationRepository->getByInvoiceId($invoice->id);
        $calculationValue = $this->getOtherCalculationValue($invoice->type, $invoice->total_price);
        $calculationType = $this->getOtherCalculationType($invoice->type);

        if ($calculationValue !== null && $calculationType !== null && $existingCalculation) {
            $this->otherCalculationRepository->update($existingCalculation->id, [
                'user_id' => $invoice->user_id,
                'type' => $calculationType,
                'value' => $calculationValue,
                'date' => $invoice->date,
            ]);
        } elseif ($calculationValue !== null && $calculationType !== null && ! $existingCalculation) {
            $this->createOtherCalculation($invoice, $data);
        } elseif (($calculationValue === null || $calculationType === null) && $existingCalculation) {
            $this->otherCalculationRepository->delete($existingCalculation->id);
        }
    }

    private function deleteOtherCalculation($invoiceId)
    {
        $existingCalculation = $this->otherCalculationRepository->getByInvoiceId($invoiceId);

        if ($existingCalculation) {
            $this->otherCalculationRepository->delete($existingCalculation->id);
        }
    }

    private function getOtherCalculationValue($type, $totalPrice)
    {
        if ($type === InvoiceTypesEnum::OTHER_INPUT->value) {
            return $totalPrice; // Positive for other input (kirim)
        } elseif ($type === InvoiceTypesEnum::OTHER_OUTPUT->value) {
            return -$totalPrice; // Negative for other output (chiqim)
        }

        return null; // No calculation for other types
    }

    private function getOtherCalculationType($type)
    {
        if ($type === InvoiceTypesEnum::OTHER_INPUT->value) {
            return OtherCalculationTypesEnum::OTHER_PRODUCT_INPUT->value;
        } elseif ($type === InvoiceTypesEnum::OTHER_OUTPUT->value) {
            return OtherCalculationTypesEnum::OTHER_PRODUCT_OUTPUT->value;
        }

        return null;
    }

    private function getSupplierCalculationValue($type, $totalPrice)
    {
        if ($type === InvoiceTypesEnum::SUPPLIER_INPUT->value) {
            return -$totalPrice; // Negative for supplier input
        } elseif ($type === InvoiceTypesEnum::SUPPLIER_OUTPUT->value) {
            return $totalPrice; // Positive for supplier output
        }

        return null; // No calculation for other types
    }

    private function generateHistoryEntry($oldInvoice, $newData, $productChanges)
    {
        $user = Auth::user();
        $description = '';

        // Check supplier/other_source changes
        $sourceChange = $this->getSourceChangeDescription($oldInvoice, $newData);
        if ($sourceChange) {
            $description .= $sourceChange;
        }

        // Check date changes
        $dateChange = $this->getDateChangeDescription($oldInvoice, $newData);
        if ($dateChange) {
            $description .= $dateChange;
        }

        // Check product changes
        $productChangeDesc = $this->getProductChangesDescription($productChanges);
        if ($productChangeDesc) {
            $description .= $productChangeDesc;
        }

        return [
            'user_full_name' => $user->full_name,
            'date' => Carbon::now()->format('d.m.Y, H:i:s'),
            'description' => rtrim($description, ';'),
        ];
    }

    private function getSourceChangeDescription($oldInvoice, $newData)
    {
        $oldSupplierId = $oldInvoice->supplier_id;
        $oldOtherSourceId = $oldInvoice->other_source_id;
        $newSupplierId = $newData['supplier_id'] ?? null;
        $newOtherSourceId = $newData['other_source_id'] ?? null;

        // No changes - check if both sources remain the same
        if ($oldSupplierId == $newSupplierId && $oldOtherSourceId == $newOtherSourceId) {
            return '';
        }

        // Determine the prefix based on source types
        $prefix = $this->getSourceChangePrefix($oldSupplierId, $oldOtherSourceId, $newSupplierId, $newOtherSourceId);

        $oldSourceName = $this->getSourceNameOnly($oldSupplierId, $oldOtherSourceId);
        $newSourceName = $this->getSourceNameOnly($newSupplierId, $newOtherSourceId);

        return $prefix.$oldSourceName.' → '.$newSourceName.';';
    }

    private function getSourceName($supplierId, $otherSourceId)
    {
        if ($supplierId) {
            $supplier = $this->supplierRepository->getById($supplierId, ['name']);

            return 's@'.($supplier?->name ?? 'Unknown');
        } elseif ($otherSourceId) {
            $otherSource = $this->otherSourceRepository->findById($otherSourceId, ['name']);

            return 'o@'.($otherSource?->name ?? 'Unknown');
        }

        // Handle null case (no source selected)
        return 'null';
    }

    private function getSourceChangePrefix($oldSupplierId, $oldOtherSourceId, $newSupplierId, $newOtherSourceId)
    {
        // Determine old source type
        $oldIsSupplier = ! empty($oldSupplierId);
        $oldIsOther = ! empty($oldOtherSourceId);

        // Determine new source type
        $newIsSupplier = ! empty($newSupplierId);
        $newIsOther = ! empty($newOtherSourceId);

        if ($oldIsSupplier && $newIsSupplier) {
            return 's@'; // supplier to supplier
        } elseif ($oldIsSupplier && $newIsOther) {
            return 'so@'; // supplier to other
        } elseif ($oldIsOther && $newIsOther) {
            return 'o@'; // other to other
        } elseif ($oldIsOther && $newIsSupplier) {
            return 'os@'; // other to supplier
        }

        return ''; // fallback
    }

    private function getSourceNameOnly($supplierId, $otherSourceId)
    {
        if ($supplierId) {
            $supplier = $this->supplierRepository->getById($supplierId, ['name']);

            return $supplier?->name ?? 'Unknown';
        } elseif ($otherSourceId) {
            $otherSource = $this->otherSourceRepository->findById($otherSourceId, ['name']);

            return $otherSource?->name ?? 'Unknown';
        }

        // Handle null case (no source selected)
        return 'null';
    }

    private function getDateChangeDescription($oldInvoice, $newData)
    {
        $oldDate = Carbon::parse($oldInvoice->date)->format('d.m.Y');
        $newDate = Carbon::createFromFormat('d.m.Y', $newData['date'])->format('d.m.Y');

        if ($oldDate !== $newDate) {
            return 'd@'.$oldDate.' → d@'.$newDate.';';
        }

        return '';
    }

    private function getProductChangesDescription($productChanges)
    {
        $description = '';

        // Added products
        foreach ($productChanges['added'] as $product) {
            $productModel = $this->productRepository->getById($product['product_id'], ['name']);
            $productName = $productModel?->name ?? 'Unknown';
            $description .= 'a@#'.$product['product_id'].'- '.$productName.': '.number_format(abs($product['count']), 0, '.', ' ').', '.number_format($product['price'], 0, '.', ' ').';';
        }

        // Updated products
        foreach ($productChanges['updated'] as $change) {
            $productModel = $this->productRepository->getById($change['product_id'], ['name']);
            $productName = $productModel?->name ?? 'Unknown';
            $description .= 'u@#'.$change['product_id'].'- '.$productName.': '.
                number_format(abs($change['old_count']), 0, '.', ' ').' → '.number_format(abs($change['new_count']), 0, '.', ' ').', '.
                number_format($change['old_price'], 0, '.', ' ').' → '.number_format($change['new_price'], 0, '.', ' ').';';
        }

        // Removed products
        foreach ($productChanges['removed'] as $product) {
            $productModel = $this->productRepository->getById($product['product_id'], ['name']);
            $productName = $productModel?->name ?? 'Unknown';
            $description .= 'r@#'.$product['product_id'].'- '.$productName.': '.number_format(abs($product['count']), 0, '.', ' ').', '.number_format($product['price'], 0, '.', ' ').';';
        }

        return $description;
    }

    private function getProductChanges($invoiceId, $products)
    {
        $oldProducts = $this->invoiceProductRepository->getByInvoiceId($invoiceId);
        $oldProductsMap = collect($oldProducts)->keyBy('product_id');
        $oldProductsMapById = collect($oldProducts)->keyBy('id');

        $added = [];
        $updated = [];
        $removed = [];

        // Process current products
        foreach ($products as $product) {
            if ($product['action'] === 'add') {
                $added[] = $product;
            } elseif ($product['action'] === 'edit') {
                $oldProduct = $oldProductsMap->get($product['product_id']);
                if ($oldProduct) {
                    $updated[] = [
                        'product_id' => $product['product_id'],
                        'old_count' => $oldProduct->count,
                        'new_count' => $product['count'],
                        'old_price' => $oldProduct->price,
                        'new_price' => $product['price'],
                    ];
                }
            } elseif ($product['action'] === 'delete') {
                // For delete action, we have invoice_product ID, not product_id
                $oldProduct = $oldProductsMapById->get($product['id']);
                if ($oldProduct) {
                    $removed[] = [
                        'product_id' => $oldProduct->product_id,
                        'count' => $oldProduct->count,
                        'price' => $oldProduct->price,
                    ];
                }
            }
        }

        return [
            'added' => $added,
            'updated' => $updated,
            'removed' => $removed,
        ];
    }
}
