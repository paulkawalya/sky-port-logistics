<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicePayment;
use App\Models\PaymentAccount;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use App\Models\QuotationPayment;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use App\Models\RequisitionPayment;
use App\Models\Shippment;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Transit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::create([
            'name' => 'Admin Sifco',
            'email' => 'sifco.admin@gmail.com',
            'user_type' => 'Admin',
            'phone_number' => '+256700000000',
            'password' => bcrypt('user123@'),
        ]);

        $adminId = $admin->id;
        $now = Carbon::now();

        // 1. Payment Accounts
        $accStanbicUsd = PaymentAccount::create([
            'name' => 'Stanbic Bank USD Collection',
            'account_number' => '9030012345678',
            'account_type' => 'Bank',
            'transaction_currency' => 'USD',
            'others' => 'Main Freight USD Collection Account - Forest Mall Branch',
        ]);

        $accStanbicUgx = PaymentAccount::create([
            'name' => 'Stanbic Bank UGX Operations',
            'account_number' => '9030098765432',
            'account_type' => 'Bank',
            'transaction_currency' => 'UGX',
            'others' => 'Operational Expenses & Local Payouts',
        ]);

        $accCentenary = PaymentAccount::create([
            'name' => 'Centenary Bank Collection',
            'account_number' => '3100054321',
            'account_type' => 'Bank',
            'transaction_currency' => 'UGX',
            'others' => 'Corporate Clients Collection Account',
        ]);

        $accCashSafeUsd = PaymentAccount::create([
            'name' => 'Main Office Vault (USD)',
            'account_number' => 'SAFE-USD-01',
            'account_type' => 'Cash',
            'transaction_currency' => 'USD',
            'others' => 'Physical USD Cash Safe - Head Office',
        ]);

        $accPettyCash = PaymentAccount::create([
            'name' => 'Kampala Warehouse Petty Cash',
            'account_number' => 'PETTY-01',
            'account_type' => 'Cash',
            'transaction_currency' => 'UGX',
            'others' => 'Daily Warehouse Operations & Handling Petty Cash',
        ]);

        $accMtnMomo = PaymentAccount::create([
            'name' => 'MTN MoMo Merchant',
            'account_number' => '654321',
            'account_type' => 'Mobile Money',
            'transaction_currency' => 'UGX',
            'others' => 'Sifcargo Freight Merchant Code',
        ]);

        // 2. Expense Categories
        $catFreight = ExpenseCategory::create(['name' => 'Ocean & Air Freight Clearing', 'particular' => 'Shipping line charges and terminal handling']);
        $catCustoms = ExpenseCategory::create(['name' => 'Customs Duties & Inspection', 'particular' => 'URA taxes, verification and customs agency fees']);
        $catHaulage = ExpenseCategory::create(['name' => 'Trucking, Haulage & Fuel', 'particular' => 'Inland transit haulage from Mombasa to Kampala']);
        $catDemurrage = ExpenseCategory::create(['name' => 'Port Demurrage & Detention', 'particular' => 'Container holding and port demurrage settlements']);
        $catWarehouse = ExpenseCategory::create(['name' => 'Warehouse Storage & Utilities', 'particular' => 'Rent, electricity, packaging and security']);
        $catAdmin = ExpenseCategory::create(['name' => 'Administration & Operations', 'particular' => 'Office consumables, licenses and software']);

        // 3. Suppliers
        $supMaersk = Supplier::create(['name' => 'Maersk Line Uganda Ltd', 'phone_number' => '+256312345000', 'address' => 'Nakawa Business Park, Kampala']);
        $supMsc = Supplier::create(['name' => 'Mediterranean Shipping Company (MSC)', 'phone_number' => '+256414234567', 'address' => 'Jinja Road, Kampala']);
        $supKpa = Supplier::create(['name' => 'Kenya Ports Authority (KPA)', 'phone_number' => '+254412112999', 'address' => 'Kilindini Port, Mombasa, Kenya']);
        $supHaulers = Supplier::create(['name' => 'Kampala Trans-Haulers Logistics Ltd', 'phone_number' => '+256772889900', 'address' => 'Banda Industrial Zone, Kampala']);
        $supTotal = Supplier::create(['name' => 'TotalEnergies Marketing Uganda Ltd', 'phone_number' => '+256414343800', 'address' => '8th Street Industrial Area, Kampala']);
        $supCfs = Supplier::create(['name' => 'Kyambogo Inland Container Depot (CFS)', 'phone_number' => '+256701556677', 'address' => 'Kyambogo Industrial Area, Kampala']);

        // 4. Agents
        $agentGz = Agent::create(['name' => 'Sifcargo Guangzhou Hub (China)', 'phone_number' => '+862081234567']);
        $agentYw = Agent::create(['name' => 'Sifcargo Yiwu Logistics Center (China)', 'phone_number' => '+865798512345']);
        $agentDx = Agent::create(['name' => 'Dubai Cargo Consolidation Express', 'phone_number' => '+97142234567']);
        $agentMsa = Agent::create(['name' => 'Mombasa Port Clearing Agents Ltd', 'phone_number' => '+254722112233']);

        // 5. Customers
        $c1 = Customer::create(['name' => 'Victoria Electrical Supplies Ltd', 'phone_number' => '+256772123401']);
        $c2 = Customer::create(['name' => 'Kikuubo Wholesale General Traders Ltd', 'phone_number' => '+256701234502']);
        $c3 = Customer::create(['name' => 'Prime Auto Parts & Spares Ltd', 'phone_number' => '+256752345603']);
        $c4 = Customer::create(['name' => 'Nakasero Solar & Hardware Solutions', 'phone_number' => '+256782456704']);
        $c5 = Customer::create(['name' => 'Crown Textiles & Fabrics Importers', 'phone_number' => '+256702567805']);
        $c6 = Customer::create(['name' => 'Nile Agro Machinery & Equipment Ltd', 'phone_number' => '+256774678906']);
        $c7 = Customer::create(['name' => 'Equator Smart Gadgets & Mobile Tech', 'phone_number' => '+256753789007']);
        $c8 = Customer::create(['name' => 'Rwenzori Leather & Shoes Enterprise', 'phone_number' => '+256783890108']);

        // 6. Transits & Containers
        $t1 = Transit::create([
            'number' => 'MSKU-9041235',
            'user_id' => $adminId,
            'category' => 'FCL',
            'size' => '40HQ',
            'payment_status' => 'paid',
            'departure_place' => 'Guangzhou Port, China',
            'destination_place' => 'Kampala Inland Depot via Mombasa',
            'expected_arrival_time' => $now->copy()->subDays(4)->toDateString(),
            'entry_number' => 'C-892110',
            'clearing_agent' => 'Mombasa Port Clearing Agents Ltd',
            'contact_information' => 'Vessel: Maersk Seletar V.2411. Cleared Mombasa port, arrived Kampala store.',
            'created_at' => $now->copy()->subMonths(2),
        ]);

        $t2 = Transit::create([
            'number' => 'TGHU-7721904',
            'user_id' => $adminId,
            'category' => 'Mixed',
            'size' => '40HQ',
            'payment_status' => 'partialy paid',
            'departure_place' => 'Yiwu Railway / Ningbo Port',
            'destination_place' => 'Kampala Warehouse',
            'expected_arrival_time' => $now->copy()->addDays(5)->toDateString(),
            'entry_number' => 'C-901452',
            'clearing_agent' => 'Sifcargo Clearing Desk',
            'contact_information' => 'In transit on SGR railway from Mombasa to Malaba border.',
            'created_at' => $now->copy()->subMonth(),
        ]);

        $t3 = Transit::create([
            'number' => 'CMAU-5520198',
            'user_id' => $adminId,
            'category' => 'Normal',
            'size' => '20ft',
            'payment_status' => 'paid',
            'departure_place' => 'Jebel Ali Port, Dubai UAE',
            'destination_place' => 'Kampala Warehouse',
            'expected_arrival_time' => $now->copy()->subDays(10)->toDateString(),
            'entry_number' => 'C-881472',
            'clearing_agent' => 'Dubai Cargo Express Logistics',
            'contact_information' => 'Auto spares and hardware. Currently in warehouse undergoing sorting.',
            'created_at' => $now->copy()->subMonths(3),
        ]);

        $t4 = Transit::create([
            'number' => 'MEDU-3310872',
            'user_id' => $adminId,
            'category' => 'Kitagululo',
            'size' => '40HQ',
            'payment_status' => 'not paid',
            'departure_place' => 'Ningbo Port, China',
            'destination_place' => 'Kampala CFS',
            'expected_arrival_time' => $now->copy()->addDays(12)->toDateString(),
            'entry_number' => 'C-914023',
            'clearing_agent' => 'Sifcargo Operations Desk',
            'contact_information' => 'High seas transit between Singapore and Mombasa.',
            'created_at' => $now->copy()->subDays(20),
        ]);

        $t5 = Transit::create([
            'number' => 'AIR-ET-40911',
            'user_id' => $adminId,
            'category' => 'Normal',
            'size' => 'Air Freight 777F',
            'payment_status' => 'paid',
            'departure_place' => 'Guangzhou Baiyun Airport (CAN)',
            'destination_place' => 'Entebbe International Airport (EBB)',
            'expected_arrival_time' => $now->copy()->subDays(2)->toDateString(),
            'entry_number' => 'AIR-77401',
            'clearing_agent' => 'Sifcargo Express Air Team',
            'contact_information' => 'Urgent electronics and smart gadgets. Customs cleared at Entebbe cargo village.',
            'created_at' => $now->copy()->subDays(10),
        ]);

        // 7. Consignments / Shipments
        $shipmentsData = [
            // Container 1 (MSKU-9041235) - Arrived in warehouse
            [
                'transit_id' => $t1->id,
                'customer_id' => $c1->id,
                'agent_id' => $agentGz->id,
                'shipper' => 'Foshan Lighting & Electrical Co',
                'mark' => 'VES-KLA-01',
                'bl_packages' => '120 CTNS',
                'number_of_packages' => 120,
                'unit_of_measure' => 'CTN',
                'weight' => 2450.0,
                'volume' => 14.5,
                'cbm' => 14.5,
                'type' => 'FCL Consignment',
                'description' => 'LED floodlights, switches, circuit breakers, and copper cabling',
                'cargo_status' => 'Ready for Pickup',
                'warehouse_location' => 'Bay 1 - Shelf A2',
                'received_in_store_at' => $now->copy()->subDays(3),
                'freight_usd' => 2610.0,
                'bl_usd' => 50.0,
                'others_usd' => 80.0,
                'paid_usd' => 2740.0, // Fully Paid
                'created_at' => $now->copy()->subDays(25),
            ],
            [
                'transit_id' => $t1->id,
                'customer_id' => $c2->id,
                'agent_id' => $agentGz->id,
                'shipper' => 'Zhejiang Homeware Exports',
                'mark' => 'KW-KAMPALA-99',
                'bl_packages' => '250 CTNS',
                'number_of_packages' => 250,
                'unit_of_measure' => 'CTN',
                'weight' => 3800.0,
                'volume' => 22.0,
                'cbm' => 22.0,
                'type' => 'FCL Consignment',
                'description' => 'Kitchen plasticware, thermal flasks, dinner sets, and cutlery',
                'cargo_status' => 'Received in Store',
                'warehouse_location' => 'Bay 2 - Pallet Zone C',
                'received_in_store_at' => $now->copy()->subDays(2),
                'freight_usd' => 3960.0,
                'bl_usd' => 50.0,
                'others_usd' => 120.0,
                'paid_usd' => 2000.0, // Partially Paid
                'created_at' => $now->copy()->subDays(24),
            ],
            [
                'transit_id' => $t1->id,
                'customer_id' => $c4->id,
                'agent_id' => $agentGz->id,
                'shipper' => 'Shenzhen Jinko Solar Power Ltd',
                'mark' => 'SOLAR-NAK-77',
                'bl_packages' => '85 CRATES',
                'number_of_packages' => 85,
                'unit_of_measure' => 'CRT',
                'weight' => 4100.0,
                'volume' => 18.0,
                'cbm' => 18.0,
                'type' => 'FCL Consignment',
                'description' => 'Monocrystalline solar panels (550W) and hybrid inverters',
                'cargo_status' => 'Delivered',
                'warehouse_location' => 'Yard Bay 4 (Dispatched)',
                'received_in_store_at' => $now->copy()->subDays(4),
                'dispatched_at' => $now->copy()->subDays(1),
                'receiver_name' => 'Muwanga Denis',
                'receiver_phone' => '+256782456704',
                'receiver_id_number' => 'CM88019234AA',
                'receiver_vehicle_no' => 'UBD 811R',
                'gate_pass_number' => 'GP-2026-001',
                'checkout_notes' => 'Complete count verified. 85 crates loaded onto Isuzu Forward UBD 811R.',
                'freight_usd' => 3240.0,
                'bl_usd' => 50.0,
                'others_usd' => 150.0,
                'paid_usd' => 3440.0, // Fully Paid
                'created_at' => $now->copy()->subDays(23),
            ],

            // Container 2 (TGHU-7721904) - In Transit from Yiwu
            [
                'transit_id' => $t2->id,
                'customer_id' => $c5->id,
                'agent_id' => $agentYw->id,
                'shipper' => 'Shaoxing Silk & Cotton Mills',
                'mark' => 'CT-LUWUM-42',
                'bl_packages' => '140 BALES',
                'number_of_packages' => 140,
                'unit_of_measure' => 'BALE',
                'weight' => 5200.0,
                'volume' => 26.5,
                'cbm' => 26.5,
                'type' => 'Sea Freight LCL',
                'description' => 'Polyester suit material, kitenge prints, curtains, and rolls',
                'cargo_status' => 'In Transit',
                'warehouse_location' => 'In Transit',
                'freight_usd' => 4770.0,
                'bl_usd' => 50.0,
                'others_usd' => 180.0,
                'paid_usd' => 0.0, // Unpaid
                'created_at' => $now->copy()->subDays(15),
            ],
            [
                'transit_id' => $t2->id,
                'customer_id' => $c8->id,
                'agent_id' => $agentYw->id,
                'shipper' => 'Wenzhou Footwear Manufacturing',
                'mark' => 'RWZ-SHOES-10',
                'bl_packages' => '320 CTNS',
                'number_of_packages' => 320,
                'unit_of_measure' => 'CTN',
                'weight' => 4200.0,
                'volume' => 24.0,
                'cbm' => 24.0,
                'type' => 'Sea Freight LCL',
                'description' => 'Men leather shoes, women sandals, school shoes and rubber boots',
                'cargo_status' => 'In Transit',
                'warehouse_location' => 'In Transit',
                'freight_usd' => 4320.0,
                'bl_usd' => 50.0,
                'others_usd' => 110.0,
                'paid_usd' => 2000.0, // Partial
                'created_at' => $now->copy()->subDays(14),
            ],

            // Container 3 (CMAU-5520198) - Auto Parts from Dubai (Store sorting)
            [
                'transit_id' => $t3->id,
                'customer_id' => $c3->id,
                'agent_id' => $agentDx->id,
                'shipper' => 'Al Futtaim Auto Parts LLC Dubai',
                'mark' => 'PRIME-NDEEBA-05',
                'bl_packages' => '95 WOODEN CASES',
                'number_of_packages' => 95,
                'unit_of_measure' => 'CASE',
                'weight' => 3100.0,
                'volume' => 15.0,
                'cbm' => 15.0,
                'type' => 'Sea Freight Normal',
                'description' => 'Toyota & Isuzu shock absorbers, brake discs, clutch plates and radiators',
                'cargo_status' => 'Sorted & Inspected',
                'warehouse_location' => 'Bay 3 - Heavy Shelf B1',
                'received_in_store_at' => $now->copy()->subDays(8),
                'freight_usd' => 2700.0,
                'bl_usd' => 50.0,
                'others_usd' => 90.0,
                'paid_usd' => 2840.0, // Fully Paid
                'created_at' => $now->copy()->subMonths(2),
            ],

            // Container 4 (MEDU-3310872) - Kitagululo high seas
            [
                'transit_id' => $t4->id,
                'customer_id' => $c6->id,
                'agent_id' => $agentYw->id,
                'shipper' => 'Taizhou Agricultural Machinery Corp',
                'mark' => 'NILE-AGRO-88',
                'bl_packages' => '45 CRATES',
                'number_of_packages' => 45,
                'unit_of_measure' => 'CRT',
                'weight' => 6400.0,
                'volume' => 28.0,
                'cbm' => 28.0,
                'type' => 'Sea Freight Kitagululo',
                'description' => 'Diesel water pumps, maize shellers, small walking tractors and spares',
                'cargo_status' => 'In Transit',
                'warehouse_location' => 'In Transit',
                'freight_usd' => 5040.0,
                'bl_usd' => 50.0,
                'others_usd' => 250.0,
                'paid_usd' => 0.0, // Unpaid
                'created_at' => $now->copy()->subDays(18),
            ],

            // Container 5 (AIR-ET-40911) - Air cargo express
            [
                'transit_id' => $t5->id,
                'customer_id' => $c7->id,
                'agent_id' => $agentGz->id,
                'shipper' => 'Shenzhen Huafeng Digital Technology',
                'mark' => 'EQUATOR-TECH-01',
                'bl_packages' => '35 CARTONS',
                'number_of_packages' => 35,
                'unit_of_measure' => 'CTN',
                'weight' => 450.0,
                'volume' => 3.2,
                'cbm' => 3.2,
                'type' => 'Express Air Freight',
                'description' => 'Mobile phone screens, smartwatches, power banks and USB-C chargers',
                'cargo_status' => 'Ready for Pickup',
                'warehouse_location' => 'Secured Cage - Vault A',
                'received_in_store_at' => $now->copy()->subDays(1),
                'freight_usd' => 2475.0, // $5.50 / kg
                'bl_usd' => 30.0,
                'others_usd' => 60.0,
                'paid_usd' => 2565.0, // Fully Paid
                'created_at' => $now->copy()->subDays(5),
            ],
        ];

        foreach ($shipmentsData as $sData) {
            $freight = $sData['freight_usd'];
            $bl = $sData['bl_usd'];
            $others = $sData['others_usd'];
            $paid = $sData['paid_usd'];
            $createdAt = $sData['created_at'];

            unset($sData['freight_usd'], $sData['bl_usd'], $sData['others_usd'], $sData['paid_usd'], $sData['created_at']);

            $sData['user_id'] = $adminId;
            $shipment = Shippment::create($sData);
            $shipment->created_at = $createdAt;
            $shipment->save();

            // Auto-generate Freight Invoice
            $invoice = Invoice::create([
                'shippment_id' => $shipment->id,
                'user_id' => $adminId,
                'date_invoiced' => $createdAt->toDateString(),
                'transaction_currency' => 'USD',
                'created_at' => $createdAt,
            ]);

            InvoiceDetail::create(['invoice_id' => $invoice->id, 'particular' => 'Ocean / Air Freight Charge', 'quantity' => 1, 'unit_price' => $freight, 'created_at' => $createdAt]);
            InvoiceDetail::create(['invoice_id' => $invoice->id, 'particular' => 'Bill of Lading / Airway Bill Fee', 'quantity' => 1, 'unit_price' => $bl, 'created_at' => $createdAt]);
            InvoiceDetail::create(['invoice_id' => $invoice->id, 'particular' => 'Terminal Handling & Port Documentation', 'quantity' => 1, 'unit_price' => $others, 'created_at' => $createdAt]);

            if ($paid > 0) {
                $payDate = $createdAt->copy()->addDays(2)->toDateString();
                InvoicePayment::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => $adminId,
                    'payment_date' => $payDate,
                    'amount_paid' => $paid,
                    'mode_of_payment' => 'Bank',
                    'narrative' => 'Customer freight settlement - ' . $shipment->mark,
                    'created_at' => Carbon::parse($payDate),
                ]);

                // Record Ledger Transaction Credit
                Transaction::create([
                    'transaction_date' => $payDate,
                    'user_id' => $adminId,
                    'payment_account_id' => $accStanbicUsd->id,
                    'amount' => $paid,
                    'particular' => 'Freight Payment for INV-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT) . ' - ' . ($shipment->customer?->name ?? 'Customer'),
                    'transaction_type' => 'credit',
                    'transaction_currency' => 'USD',
                    'created_at' => Carbon::parse($payDate),
                ]);
            }
        }

        // 8. Manual Invoices (Quotation with transaction_type = 'invoice')
        $manualInvoices = [
            [
                'customer_id' => $c6->id,
                'date' => $now->copy()->subDays(12),
                'currency' => 'USD',
                'desc' => 'Customs Clearance & Transit Haulage for Heavy Machinery',
                'items' => [
                    ['particular' => 'Customs Verification & Clearance Agency Fee', 'qty' => 1, 'price' => 1800.0],
                    ['particular' => 'Special Flatbed Haulage (Mombasa to Kampala)', 'qty' => 2, 'price' => 1500.0],
                ],
                'paid' => 4800.0,
            ],
            [
                'customer_id' => $c2->id,
                'date' => $now->copy()->subDays(6),
                'currency' => 'USD',
                'desc' => 'Direct CFS Storage & Port Container Shifting Services',
                'items' => [
                    ['particular' => 'Depot Demurrage Extension Fee', 'qty' => 1, 'price' => 950.0],
                    ['particular' => 'Forklift Heavy Lifting & De-stuffing', 'qty' => 1, 'price' => 550.0],
                ],
                'paid' => 0.0, // Unpaid ($1,500 due)
            ],
            [
                'customer_id' => $c3->id,
                'date' => $now->copy()->subDays(2),
                'currency' => 'USD',
                'desc' => 'Courier Express Spares Clearance and Airport Handover',
                'items' => [
                    ['particular' => 'Express Customs Declaration & Clearance', 'qty' => 1, 'price' => 550.0],
                    ['particular' => 'Entebbe Airport Bond Handling & Storage', 'qty' => 1, 'price' => 300.0],
                ],
                'paid' => 850.0, // Fully Paid
            ],
        ];

        foreach ($manualInvoices as $mInv) {
            $q = Quotation::create([
                'user_id' => $adminId,
                'customer_id' => $mInv['customer_id'],
                'date_quoted' => $mInv['date']->toDateString(),
                'transaction_type' => 'invoice',
                'transaction_currency' => $mInv['currency'],
                'description' => $mInv['desc'],
                'created_at' => $mInv['date'],
            ]);

            foreach ($mInv['items'] as $item) {
                QuotationDetail::create([
                    'quotation_id' => $q->id,
                    'particular' => $item['particular'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'created_at' => $mInv['date'],
                ]);
            }

            if ($mInv['paid'] > 0) {
                QuotationPayment::create([
                    'quotation_id' => $q->id,
                    'user_id' => $adminId,
                    'payment_date' => $mInv['date']->toDateString(),
                    'amount_paid' => $mInv['paid'],
                    'mode_of_payment' => 'Bank',
                    'narrative' => 'Manual Invoice settlement - INV-M-' . str_pad($q->id, 4, '0', STR_PAD_LEFT),
                    'created_at' => $mInv['date'],
                ]);

                Transaction::create([
                    'transaction_date' => $mInv['date']->toDateString(),
                    'user_id' => $adminId,
                    'payment_account_id' => $accStanbicUsd->id,
                    'amount' => $mInv['paid'],
                    'particular' => 'Payment for Manual Invoice INV-M-' . str_pad($q->id, 4, '0', STR_PAD_LEFT),
                    'transaction_type' => 'credit',
                    'transaction_currency' => 'USD',
                    'created_at' => $mInv['date'],
                ]);
            }
        }

        // 9. Quotations (Prospective Cargo Inquiries)
        $quotesData = [
            [
                'customer_id' => $c1->id,
                'date' => $now->copy()->subDays(4),
                'currency' => 'USD',
                'container' => 'MSKU-NEW-01',
                'desc' => '40HQ Sea Freight quotation from Guangzhou to Kampala door delivery',
                'weight' => 18500,
                'volume' => 68.0,
                'packages' => 850,
                'items' => [
                    ['particular' => '40HQ Ocean Freight Rate (Guangzhou to Mombasa)', 'qty' => 1, 'price' => 4500.0],
                    ['particular' => 'Mombasa Port Terminal Handling Charge (THC)', 'qty' => 1, 'price' => 450.0],
                    ['particular' => 'Transit Transport to Kampala Inland Bond', 'qty' => 1, 'price' => 1850.0],
                ],
            ],
            [
                'customer_id' => $c7->id,
                'date' => $now->copy()->subDays(7),
                'currency' => 'USD',
                'container' => 'AIR-QUOTE-88',
                'desc' => 'Air Freight Quotation for 450KG Smart Tech Accessories',
                'weight' => 450,
                'volume' => 3.5,
                'packages' => 30,
                'items' => [
                    ['particular' => 'Express Air Freight per KG ($5.20/kg)', 'qty' => 450, 'price' => 5.20],
                    ['particular' => 'Airport Handling & Airwaybill Documentation', 'qty' => 1, 'price' => 120.0],
                ],
            ],
            [
                'customer_id' => $c4->id,
                'date' => $now->copy()->subDays(1),
                'currency' => 'USD',
                'container' => '20FT-SOLAR-09',
                'desc' => '20FT Container Solar Inverters Shipping Estimate from Ningbo',
                'weight' => 9200,
                'volume' => 28.0,
                'packages' => 120,
                'items' => [
                    ['particular' => '20FT Container Ocean Freight Rate', 'qty' => 1, 'price' => 2600.0],
                    ['particular' => 'Customs Clearance & Verification Agency', 'qty' => 1, 'price' => 600.0],
                ],
            ],
        ];

        foreach ($quotesData as $qData) {
            $quote = Quotation::create([
                'user_id' => $adminId,
                'customer_id' => $qData['customer_id'],
                'date_quoted' => $qData['date']->toDateString(),
                'transaction_type' => 'quotation',
                'transaction_currency' => $qData['currency'],
                'container_air_no' => $qData['container'],
                'weight' => $qData['weight'],
                'volume' => $qData['volume'],
                'packages' => $qData['packages'],
                'description' => $qData['desc'],
                'created_at' => $qData['date'],
            ]);

            foreach ($qData['items'] as $item) {
                QuotationDetail::create([
                    'quotation_id' => $quote->id,
                    'particular' => $item['particular'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'created_at' => $qData['date'],
                ]);
            }
        }

        // 10. Requisitions & Operational Expenses
        $reqsData = [
            [
                'status' => 'approved',
                'remarks' => 'Heavy hauler transit fuel settlement for 3 container trucks',
                'currency' => 'UGX',
                'date' => $now->copy()->subDays(8),
                'items' => [
                    ['particular' => 'Diesel Fuel Advance (Trucks UBD 811R, UBD 450T, UBE 122M)', 'reason' => 'Mombasa corridor fuel allocation', 'qty' => 3, 'cost' => 2200000.0, 'cat_id' => $catHaulage->id],
                ],
                'disbursed' => true,
                'supplier_id' => $supTotal->id,
                'account_id' => $accStanbicUgx->id,
            ],
            [
                'status' => 'approved',
                'remarks' => 'Mombasa Port KPA Container Clearance and Terminal Handling',
                'currency' => 'USD',
                'date' => $now->copy()->subDays(14),
                'items' => [
                    ['particular' => 'Terminal Handling Charges Container MSKU-9041235', 'reason' => 'Port vessel discharge and handling', 'qty' => 1, 'cost' => 850.0, 'cat_id' => $catFreight->id],
                    ['particular' => 'Port Storage & Wharfage Fee', 'reason' => 'KPA storage dues', 'qty' => 1, 'cost' => 320.0, 'cat_id' => $catDemurrage->id],
                ],
                'disbursed' => true,
                'supplier_id' => $supKpa->id,
                'account_id' => $accStanbicUsd->id,
            ],
            [
                'status' => 'approved',
                'remarks' => 'Kampala Central Warehouse Forklift Servicing & Hydraulic Repairs',
                'currency' => 'UGX',
                'date' => $now->copy()->subDays(3),
                'items' => [
                    ['particular' => 'Toyota Forklift 3-Ton Routine Maintenance & Oil Filters', 'reason' => 'Preventive warehouse equipment servicing', 'qty' => 1, 'cost' => 1450000.0, 'cat_id' => $catWarehouse->id],
                ],
                'disbursed' => true,
                'supplier_id' => $supHaulers->id,
                'account_id' => $accPettyCash->id,
            ],
            [
                'status' => 'pending',
                'remarks' => 'Warehouse Safety Gear, Strapping Rolls & Digital Weighing Scale Calibration',
                'currency' => 'UGX',
                'date' => $now->copy()->subDays(1),
                'items' => [
                    ['particular' => 'Heavy Duty Pallet Strapping Bands (5 Rolls)', 'reason' => 'Cargo packing and reinforcement', 'qty' => 5, 'cost' => 120000.0, 'cat_id' => $catWarehouse->id],
                    ['particular' => 'Digital Cargo Scale Annual UNBS Calibration', 'reason' => 'Accurate client weight verification', 'qty' => 1, 'cost' => 450000.0, 'cat_id' => $catWarehouse->id],
                ],
                'disbursed' => false,
            ],
        ];

        foreach ($reqsData as $rData) {
            $req = Requisition::create([
                'user_id' => $adminId,
                'date_requisitioned' => $rData['date']->toDateString(),
                'status' => $rData['status'],
                'remarks' => $rData['remarks'],
                'currency' => $rData['currency'],
                'created_at' => $rData['date'],
            ]);

            foreach ($rData['items'] as $item) {
                $detail = RequisitionDetail::create([
                    'requisition_id' => $req->id,
                    'particular' => $item['particular'],
                    'reason' => $item['reason'],
                    'quantity' => $item['qty'],
                    'estimated_cost' => $item['cost'],
                    'expense_category_id' => $item['cat_id'],
                    'created_at' => $rData['date'],
                ]);

                if (!empty($rData['disbursed'])) {
                    $totalItemCost = $item['qty'] * $item['cost'];

                    RequisitionPayment::create([
                        'supplier_id' => $rData['supplier_id'],
                        'user_id' => $adminId,
                        'requisition_detail_id' => $detail->id,
                        'payment_date' => $rData['date']->toDateString(),
                        'amount' => $totalItemCost,
                        'mode_of_payment' => 'Bank',
                        'currency' => $rData['currency'],
                        'particular' => 'Payment for: ' . $item['particular'],
                        'created_at' => $rData['date'],
                    ]);

                    // Operational Expense record
                    Expense::create([
                        'user_id' => $adminId,
                        'requisition_id' => $req->id,
                        'particular' => $item['particular'],
                        'reason' => $item['reason'],
                        'quantity' => $item['qty'],
                        'estimated_cost' => $totalItemCost,
                        'created_at' => $rData['date'],
                    ]);

                    // Ledger Debit
                    Transaction::create([
                        'transaction_date' => $rData['date']->toDateString(),
                        'user_id' => $adminId,
                        'payment_account_id' => $rData['account_id'],
                        'amount' => $totalItemCost,
                        'particular' => 'Requisition Payout REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) . ' - ' . $item['particular'],
                        'transaction_type' => 'debit',
                        'transaction_currency' => $rData['currency'],
                        'created_at' => $rData['date'],
                    ]);
                }
            }
        }
    }
}
