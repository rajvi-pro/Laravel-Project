<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Billing;
use Illuminate\Support\Facades\DB;

class TestBillings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-billings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test that billings table and queries work';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Billing Table...');
        
        try {
            // Test 1: Check if table exists
            $tableExists = DB::connection()->getSchemaBuilder()->hasTable('billings');
            $this->info('✓ Billings table exists: ' . ($tableExists ? 'YES' : 'NO'));
            
            // Test 2: Count records
            $count = Billing::count();
            $this->info('✓ Total billing records: ' . $count);
            
            // Test 3: Calculate revenue
            $totalRevenue = Billing::where('payment_status', 'paid')->sum('amount');
            $this->info('✓ Total paid revenue: ₹' . $totalRevenue);
            
            // Test 4: Check pending
            $pending = Billing::where('payment_status', 'pending')->sum('amount');
            $this->info('✓ Pending amount: ₹' . $pending);
            
            // Test 5: Dashboard query
            $dashboardRevenue = DB::table('billings')
                ->selectRaw('DATE_FORMAT(billing_date, "%b") as month, DATE_FORMAT(billing_date, "%m") as month_num, SUM(amount) as revenue')
                ->where('payment_status', 'paid')
                ->whereYear('billing_date', now()->year)
                ->groupBy(DB::raw('DATE_FORMAT(billing_date, "%m"), DATE_FORMAT(billing_date, "%b")'))
                ->orderBy('month_num')
                ->get();
            
            $this->info('✓ Monthly revenue records: ' . count($dashboardRevenue));
            
            $this->info('✅ All tests PASSED! Billing table is working correctly.');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Test FAILED: ' . $e->getMessage());
            return 1;
        }
    }
}
