<?php

namespace App\Modules\Warehouse\Services;

use App\Modules\Information\Interfaces\ProductInterface;
use App\Modules\Warehouse\Enums\InvoiceTypesEnum;
use App\Modules\Warehouse\Interfaces\InvoiceInterface;
use App\Modules\Warehouse\Repositories\InvoiceProductRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        protected InvoiceInterface $invoiceRepository,
        protected InvoiceProductRepository $invoiceProductRepository,
        protected ProductInterface $productRepository
    ) {}

    public function getByType(string $type, array $data)
    {
        return $this->invoiceRepository->getByType($type, $data);
    }

    public function getByIdWithProducts(int $id)
    {
        return $this->invoiceRepository->getByIdWithProducts($id);
    }

    public function store(array $data)
    {
        $type = $data['type'];
        switch ($type) {
            case InvoiceTypesEnum::SUPPLIER_INPUT->value:
            case InvoiceTypesEnum::SUPPLIER_OUTPUT->value:
                return $this->storeSupplierInvoice($data);
            default:
                return [
                    'status' => 'error',
                    'message' => 'Mavjud bo\'lmagan turni yubordingiz'
                ];
        }
    }

    public function update(int $id, array $data)
    {
        $type = $data['type'];
        switch ($type) {
            case InvoiceTypesEnum::SUPPLIER_INPUT->value:
            case InvoiceTypesEnum::SUPPLIER_OUTPUT->value:
                return $this->updateSupplierInvoice($id, $data);
            default:
                return [
                    'status' => 'error',
                    'message' => 'Mavjud bo\'lmagan turni yubordingiz'
                ];
        }
    }

    public function delete(int $id)
    {
        $invoice = $this->invoiceRepository->findById($id);

        $result = $this->invoiceRepository->delete($invoice);

        if (!$result) {
            return [
                'status' => 'error',
                'message' => 'Fakturani o\'chirishda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Faktura muvaffaqiyatli o\'chirildi'
        ];
    }

    private function storeSupplierInvoice(array $data)
    {
        try {
            DB::beginTransaction();

            $type = $data['type'];
            $products = $data['products'];

            if ($type === InvoiceTypesEnum::SUPPLIER_OUTPUT->value) {
                $residueNotEnough = $this->checkProductResidues($products);
                if ($residueNotEnough) {
                    return [
                        'status' => 'error',
                        'message' => $residueNotEnough
                    ];
                }

                $products = $this->makeProductCountsNegative($products);;
            }

            $data['products_count'] = array_sum(array_column($products, 'count'));
            $data['total_price'] =  $this->getTotalPrice($products);

            $invoice = $this->invoiceRepository->store($data);

            $this->invoiceProductRepository->store($this->cleanAndPrepareTradeProducts($products, $invoice->id));
            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Faktura muvaffaqiyatli qo\'shildi',
                'data' => $invoice
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Faktura yaratishda xatolik yuz berdi'
            ];
        }
    }

    private function updateSupplierInvoice(int $id, array $data)
    {

        $type = $data['type'];
        $products = $data['products'];

        $invoice = $this->invoiceRepository->findById($id);

        if (!$invoice) {
            return [
                'status' => 'error',
                'message' => 'Ushbu faktura topilmadi',
                'status_code' => 404
            ];
        }

        $normalProducts = array_filter($products, fn($product) => $product['action'] === 'normal');
        $productsForInsert = array_filter($products, fn($product) => $product['action'] === 'add');
        $productsForUpdate = array_filter($products, fn($product) => $product['action'] === 'edit');
        $productIdsForDelete = array_map(fn($product) => $product['id'], array_filter($products, fn($product) => $product['action'] === 'delete'));

        $savableProducts = array_merge($normalProducts, $productsForInsert, $productsForUpdate);

        if ($type === InvoiceTypesEnum::SUPPLIER_OUTPUT->value) {
            $residueNotEnough = $this->checkProductResidues($savableProducts);

            if ($residueNotEnough) {
                return [
                    'status' => 'error',
                    'message' => $residueNotEnough
                ];
            }

            $productsForInsert = $this->makeProductCountsNegative($productsForInsert);
            $productsForUpdate = $this->makeProductCountsNegative($productsForUpdate);
        }

        $allHaveAction = collect($products)->every(function ($product) {
            return array_key_exists('action', $product);
        });

        if (!$allHaveAction) {
            return [
                'status' => 'error',
                'message' => 'Barcha tovarlarda action bo\'lishi kerak'
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
        $data['total_price']  =  $this->getTotalPrice($savableProducts);


        DB::beginTransaction();

        try {
            $updatedInvoice = $this->invoiceRepository->update($invoice, [
                'date' => Carbon::now()->format('Y-m-d'),
                'supplier_id' => $data['supplier_id'],
                'products_count' =>  abs($data['products_count']),
                'total_price' => abs($data['total_price']),
                'user_id' => Auth::id(),
                'type' => $data['type'],
                'commentary' => $data['commentary'] ?? null
            ]);

            if (!$updatedInvoice) {
                DB::rollBack();
                return [
                    'status' => 'error',
                    'message' => 'Fakturani tahrirlashda muammo yuz berdi'
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

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Faktura muvaffaqiyatli tahrirlandi',
                'data' => $updatedInvoice
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Fakturani tahrirlashda xatolik yuz berdi',
            ];
        }
    }

    private function checkProductResidues(array $products): string|null
    {
        $productIds = collect($products)->pluck('product_id')->toArray();
        $residues = $this->productRepository->getForCheckResidue($productIds);

        foreach ($products as $product) {
            $residue = $residues[$product['product_id']] ?? null;
            if (!$residue || $product['count'] > $residue->residue) {
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
                'message' => 'Mavjud bo\'lmagan tovarni tahrirlash yoki o\'chirish mumkin emas'
            ];
        }

        return null;
    }

    private function makeProductCountsNegative(array $products): array
    {
        return array_map(function ($product) {
            $product['count'] = -abs((float)$product['count']);
            return $product;
        }, $products);
    }

    private function getTotalPrice(array $products)
    {
        return array_reduce($products, function ($carry, $item) {
            return $carry + ($item['price'] * $item['count']);
        }, 0);
    }

    public function cleanAndPrepareTradeProducts(array $products, int $invoiceId, bool $withId = false): array
    {
        return array_map(function ($product) use ($invoiceId, $withId) {
            $data = [
                'invoice_id'  => $invoiceId,
                'product_id'  => $product['product_id'],
                'count'    => $product['count'],
                'price'       => $product['price'],
                'total_price' => abs($product['price'] * $product['count']),
                'date' => Carbon::now()->format('Y-m-d'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
            if (!empty($withId)) {
                $data['id'] = $product['id'];
            }
            return $data;
        }, $products);
    }
}
