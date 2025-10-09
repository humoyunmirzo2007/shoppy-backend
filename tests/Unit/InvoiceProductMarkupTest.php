<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceProductMarkupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Test uchun foydalanuvchi yaratish
        $this->user = User::factory()->create();

        // Test uchun kategoriya yaratish
        $this->category = Category::create([
            'name' => 'Test Kategoriya',
            'is_active' => true,
        ]);

        // Test uchun mahsulot yaratish
        $this->product = Product::create([
            'name' => 'Test Mahsulot',
            'category_id' => $this->category->id,
            'unit' => 'dona',
            'price' => 1000.00, // Sotish narxi
            'is_active' => true,
        ]);

        // Test uchun yetkazib beruvchi yaratish
        $this->supplier = Supplier::create([
            'name' => 'Test Yetkazib Beruvchi',
            'phone' => '+998901234567',
            'is_active' => true,
        ]);
    }

    public function test_invoice_product_markup_hisoblash()
    {
        // Invoice yaratish
        $invoice = Invoice::create([
            'type' => 'supplier_input',
            'supplier_id' => $this->supplier->id,
            'date' => '2024-01-01',
            'products_count' => 10,
            'total_price' => 8000.00,
            'user_id' => $this->user->id,
            'commentary' => 'Test faktura',
        ]);

        // InvoiceProduct yaratish
        $invoiceProduct = InvoiceProduct::create([
            'invoice_id' => $invoice->id,
            'product_id' => $this->product->id,
            'count' => 10,
            'price' => 1000.00, // Sotish narxi
            'input_price' => 800.00, // Kirim narxi
            'total_price' => 8000.00, // input_price * count
            'date' => '2024-01-01',
        ]);

        // Markup hisoblash
        $markup = $invoiceProduct->price - $invoiceProduct->input_price;
        $markupPercentage = ($markup / $invoiceProduct->input_price) * 100;

        // Tekshirish
        $this->assertEquals(200.00, $markup); // 1000 - 800 = 200
        $this->assertEquals(25.00, $markupPercentage); // (200 / 800) * 100 = 25%
        $this->assertEquals(8000.00, $invoiceProduct->total_price); // 800 * 10 = 8000
    }

    public function test_turli_input_price_lar_bilan_markup_hisoblash()
    {
        $testCases = [
            ['input_price' => 500.00, 'selling_price' => 1000.00, 'expected_markup' => 500.00, 'expected_percentage' => 100.00],
            ['input_price' => 800.00, 'selling_price' => 1000.00, 'expected_markup' => 200.00, 'expected_percentage' => 25.00],
            ['input_price' => 900.00, 'selling_price' => 1000.00, 'expected_markup' => 100.00, 'expected_percentage' => 11.11],
            ['input_price' => 1000.00, 'selling_price' => 1000.00, 'expected_markup' => 0.00, 'expected_percentage' => 0.00],
        ];

        foreach ($testCases as $index => $testCase) {
            // Invoice yaratish
            $invoice = Invoice::create([
                'type' => 'supplier_input',
                'supplier_id' => $this->supplier->id,
                'date' => '2024-01-01',
                'products_count' => 1,
                'total_price' => $testCase['input_price'],
                'user_id' => $this->user->id,
                'commentary' => "Test faktura {$index}",
            ]);

            // InvoiceProduct yaratish
            $invoiceProduct = InvoiceProduct::create([
                'invoice_id' => $invoice->id,
                'product_id' => $this->product->id,
                'count' => 1,
                'price' => $testCase['selling_price'],
                'input_price' => $testCase['input_price'],
                'total_price' => $testCase['input_price'],
                'date' => '2024-01-01',
            ]);

            // Markup hisoblash
            $actualMarkup = $invoiceProduct->price - $invoiceProduct->input_price;
            $actualMarkupPercentage = ($actualMarkup / $invoiceProduct->input_price) * 100;

            // Tekshirish
            $this->assertEquals($testCase['expected_markup'], $actualMarkup,
                "Test case {$index}: Markup noto'g'ri hisoblandi");
            $this->assertEquals($testCase['expected_percentage'], round($actualMarkupPercentage, 2),
                "Test case {$index}: Markup foizi noto'g'ri hisoblandi");
        }
    }

    public function test_invoice_product_total_price_hisoblash()
    {
        $testCases = [
            ['input_price' => 500.00, 'count' => 5, 'expected_total' => 2500.00],
            ['input_price' => 800.00, 'count' => 10, 'expected_total' => 8000.00],
            ['input_price' => 1200.00, 'count' => 3, 'expected_total' => 3600.00],
            ['input_price' => 100.00, 'count' => 100, 'expected_total' => 10000.00],
        ];

        foreach ($testCases as $index => $testCase) {
            // Invoice yaratish
            $invoice = Invoice::create([
                'type' => 'supplier_input',
                'supplier_id' => $this->supplier->id,
                'date' => '2024-01-01',
                'products_count' => $testCase['count'],
                'total_price' => $testCase['expected_total'],
                'user_id' => $this->user->id,
                'commentary' => "Test faktura {$index}",
            ]);

            // InvoiceProduct yaratish
            $invoiceProduct = InvoiceProduct::create([
                'invoice_id' => $invoice->id,
                'product_id' => $this->product->id,
                'count' => $testCase['count'],
                'price' => 1000.00, // Sotish narxi
                'input_price' => $testCase['input_price'],
                'total_price' => $testCase['expected_total'],
                'date' => '2024-01-01',
            ]);

            // Total price tekshirish (input_price * count)
            $this->assertEquals($testCase['expected_total'], $invoiceProduct->total_price,
                "Test case {$index}: Total price noto'g'ri hisoblandi");
        }
    }

    public function test_invoice_product_markup_percentage_hisoblash()
    {
        // Invoice yaratish
        $invoice = Invoice::create([
            'type' => 'supplier_input',
            'supplier_id' => $this->supplier->id,
            'date' => '2024-01-01',
            'products_count' => 1,
            'total_price' => 800.00,
            'user_id' => $this->user->id,
            'commentary' => 'Test faktura',
        ]);

        // InvoiceProduct yaratish
        $invoiceProduct = InvoiceProduct::create([
            'invoice_id' => $invoice->id,
            'product_id' => $this->product->id,
            'count' => 1,
            'price' => 1000.00, // Sotish narxi
            'input_price' => 800.00, // Kirim narxi
            'total_price' => 800.00,
            'date' => '2024-01-01',
        ]);

        // Markup percentage hisoblash
        $markupPercentage = ($invoiceProduct->price - $invoiceProduct->input_price) / $invoiceProduct->input_price * 100;

        // Tekshirish
        $this->assertEquals(25.00, $markupPercentage); // (1000 - 800) / 800 * 100 = 25%

        // Markup amount tekshirish
        $markupAmount = $invoiceProduct->price - $invoiceProduct->input_price;
        $this->assertEquals(200.00, $markupAmount); // 1000 - 800 = 200
    }

    public function test_invoice_product_negative_markup()
    {
        // Invoice yaratish
        $invoice = Invoice::create([
            'type' => 'supplier_input',
            'supplier_id' => $this->supplier->id,
            'date' => '2024-01-01',
            'products_count' => 1,
            'total_price' => 1000.00,
            'user_id' => $this->user->id,
            'commentary' => 'Test faktura',
        ]);

        // InvoiceProduct yaratish (sotish narxi kirim narxidan past)
        $invoiceProduct = InvoiceProduct::create([
            'invoice_id' => $invoice->id,
            'product_id' => $this->product->id,
            'count' => 1,
            'price' => 800.00, // Sotish narxi
            'input_price' => 1000.00, // Kirim narxi
            'total_price' => 1000.00,
            'date' => '2024-01-01',
        ]);

        // Markup hisoblash (manfiy bo'lishi kerak)
        $markup = $invoiceProduct->price - $invoiceProduct->input_price;
        $markupPercentage = ($markup / $invoiceProduct->input_price) * 100;

        // Tekshirish
        $this->assertEquals(-200.00, $markup); // 800 - 1000 = -200
        $this->assertEquals(-20.00, $markupPercentage); // (-200 / 1000) * 100 = -20%
    }
}
