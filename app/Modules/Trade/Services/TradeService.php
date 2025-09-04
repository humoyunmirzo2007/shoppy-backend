<?php

namespace App\Modules\Trade\Services;

use App\Modules\Information\Interfaces\ProductInterface;
use App\Modules\Trade\Enums\TradeTypesEnum;
use App\Modules\Trade\Interfaces\TradeInterface;
use App\Modules\Trade\Repositories\TradeProductRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TradeService
{
    public function __construct(
        protected TradeInterface $tradeRepository,
        protected TradeProductRepository $tradeProductRepository,
        protected ProductInterface $productRepository
    ) {}

    public function getByType(string $type, array $data)
    {
        return $this->tradeRepository->getByType($type, $data);
    }

    public function getByIdWithProducts(int $id)
    {
        return $this->tradeRepository->getByIdWithProducts($id);
    }

    public function store(array $data)
    {
        $type = $data['type'];
        switch ($type) {
            case TradeTypesEnum::TRADE->value:
            case TradeTypesEnum::RETURN_PRODUCT->value:
                return $this->storeTrade($data);
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
            case TradeTypesEnum::TRADE->value:
            case TradeTypesEnum::RETURN_PRODUCT->value:
                return $this->updateTrade($id, $data);
            default:
                return [
                    'status' => 'error',
                    'message' => 'Mavjud bo\'lmagan turni yubordingiz'
                ];
        }
    }

    public function delete(int $id)
    {
        $trade = $this->tradeRepository->findById($id);

        $result = $this->tradeRepository->delete($trade);

        if (!$result) {
            return [
                'status' => 'error',
                'message' => 'Savdoni o\'chirishda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Savdo muvaffaqiyatli o\'chirildi'
        ];
    }

    private function storeTrade(array $data)
    {
        try {
            DB::beginTransaction();

            $type = $data['type'];
            $products = $data['products'];

            if ($type === TradeTypesEnum::TRADE->value) {
                $residueNotEnough = $this->checkProductResidues($products);
                if ($residueNotEnough) {
                    return [
                        'status' => 'error',
                        'message' => $residueNotEnough
                    ];
                }

                $products = $this->makeProductCountsNegative($products);
            }

            $data['products_count'] = array_sum(array_column($products, 'count'));
            $data['total_price'] = $this->getTotalPrice($products);

            // Date formatini o'zgartirish (dd.mm.yyyy -> Y-m-d)
            $data['date'] = Carbon::createFromFormat('d.m.Y', $data['date'])->format('Y-m-d');

            $trade = $this->tradeRepository->store($data);

            $this->tradeProductRepository->store($this->cleanAndPrepareTradeProducts($products, $trade->id));

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Savdo muvaffaqiyatli qo\'shildi',
                'data' => $trade
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Savdo yaratishda xatolik yuz berdi'
            ];
        }
    }

    private function updateTrade(int $id, array $data)
    {
        $type = $data['type'];
        $products = $data['products'];

        $trade = $this->tradeRepository->findById($id);

        if (!$trade) {
            return [
                'status' => 'error',
                'message' => 'Ushbu savdo topilmadi',
                'status_code' => 404
            ];
        }

        $normalProducts = array_filter($products, fn($product) => $product['action'] === 'normal');
        $productsForInsert = array_filter($products, fn($product) => $product['action'] === 'add');
        $productsForUpdate = array_filter($products, fn($product) => $product['action'] === 'edit');
        $productIdsForDelete = array_map(fn($product) => $product['id'], array_filter($products, fn($product) => $product['action'] === 'delete'));

        $savableProducts = array_merge($normalProducts, $productsForInsert, $productsForUpdate);

        // TRADE bo'lsa, count manfiy qilish kerak
        if ($type === TradeTypesEnum::TRADE->value) {
            // Qoldiq tekshirish (update uchun maxsus hisoblash)
            if (count($productsForUpdate) > 0) {
                $residueNotEnough = $this->checkProductResiduesForUpdate($trade->id, $productsForUpdate);
                if ($residueNotEnough) {
                    return [
                        'status' => 'error',
                        'message' => $residueNotEnough
                    ];
                }
            }

            // Yangi qo'shiladigan tovarlar uchun oddiy tekshirish
            if (count($productsForInsert) > 0) {
                $residueNotEnough = $this->checkProductResidues($productsForInsert);
                if ($residueNotEnough) {
                    return [
                        'status' => 'error',
                        'message' => $residueNotEnough
                    ];
                }
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

        $someIdMissed = $this->validateTradeProductsIsExist($trade->id, $productIds);

        if ($someIdMissed) {
            return $someIdMissed;
        }

        $data['products_count'] = array_sum(array_column($savableProducts, 'count'));
        $data['total_price'] = $this->getTotalPrice($savableProducts);

        DB::beginTransaction();

        try {
            $updatedTrade = $this->tradeRepository->update($trade, [
                'date' => \Carbon\Carbon::createFromFormat('d.m.Y', $data['date'])->format('Y-m-d'),
                'client_id' => $data['client_id'],
                'products_count' => abs($data['products_count']),
                'total_price' => abs($data['total_price']),
                'user_id' => Auth::id(),
                'type' => $data['type'],
                'commentary' => $data['commentary'] ?? null
            ]);

            if (!$updatedTrade) {
                DB::rollBack();
                return [
                    'status' => 'error',
                    'message' => 'Savdoni tahrirlashda muammo yuz berdi'
                ];
            }

            if (count($productsForInsert) > 0) {
                $this->tradeProductRepository->store($this->cleanAndPrepareTradeProducts($productsForInsert, $trade->id));
            }

            if (count($productsForUpdate) > 0) {
                $this->tradeProductRepository->update($this->cleanAndPrepareTradeProducts($productsForUpdate, $trade->id, true));
            }

            if (count($productIdsForDelete) > 0) {
                $this->tradeProductRepository->deleteByIds($productIdsForDelete);
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Savdo muvaffaqiyatli tahrirlandi',
                'data' => $updatedTrade
            ];
        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Savdoni tahrirlashda xatolik yuz berdi',
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

    private function checkProductResiduesForUpdate(int $tradeId, array $products): string|null
    {
        // Eski trade_products ni olish
        $oldTradeProducts = $this->tradeProductRepository->getByTradeId($tradeId);
        $oldProductsMap = collect($oldTradeProducts)->keyBy('product_id');

        // Hozirgi product qoldiqlarini olish
        $productIds = collect($products)->pluck('product_id')->toArray();
        $residues = $this->productRepository->getForCheckResidue($productIds);

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $newCount = abs($product['count']); // Yangi count (musbat)
            $oldCount = abs($oldProductsMap->get($productId)->count ?? 0); // Eski count (musbat)
            $currentResidue = $residues[$productId]->residue ?? 0;

            // Qo'shimcha sotiladigan miqdor
            $additionalCount = $newCount - $oldCount;

            if ($additionalCount > 0 && $additionalCount > $currentResidue) {
                return "{$productId} - ID li mahsulot uchun yetarli qoldiq mavjud emas. Qo'shimcha {$additionalCount} ta kerak, lekin {$currentResidue} ta mavjud.";
            }
        }

        return null;
    }

    private function validateTradeProductsIsExist(int $tradeId, array $productIds): ?array
    {
        $someIdMissed = array_diff($this->tradeProductRepository->findMissingIds($tradeId, $productIds), $productIds);
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

    public function cleanAndPrepareTradeProducts(array $products, int $tradeId, bool $withId = false): array
    {
        return array_map(function ($product) use ($tradeId, $withId) {
            $data = [
                'trade_id' => $tradeId,
                'product_id' => $product['product_id'],
                'count' => $product['count'],
                'price' => $product['price'],
                'total_price' => abs($product['price'] * $product['count']),
                'date' => Carbon::now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (!empty($withId)) {
                $data['id'] = $product['id'];
            }
            return $data;
        }, $products);
    }
}
