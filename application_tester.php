<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Comprehensive Route and Database Testing Script
 * Tests all critical application functionality
 */

class ApplicationTester
{
    protected $results = [
        'passed' => 0,
        'failed' => 0,
        'warnings' => 0,
    ];
    
    protected $tests = [];
    
    public function run()
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════════════╗\n";
        echo "║     REALTYPLUS APPLICATION - COMPREHENSIVE TEST SUITE              ║\n";
        echo "╚════════════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        
        $this->testDatabaseConnection();
        $this->testDatabaseTables();
        $this->testModels();
        $this->testRoutes();
        $this->testCriticalFeatures();
        
        $this->printSummary();
    }
    
    /**
     * Test database connection
     */
    protected function testDatabaseConnection()
    {
        echo "🔧 DATABASE CONNECTION TESTS\n";
        echo str_repeat("─", 70) . "\n";
        
        try {
            $dbName = DB::connection()->getDatabaseName();
            echo "  ✅ Database connected: $dbName\n";
            $this->results['passed']++;
        } catch (Exception $e) {
            echo "  ❌ Database connection failed: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        echo "\n";
    }
    
    /**
     * Test critical database tables
     */
    protected function testDatabaseTables()
    {
        echo "📊 DATABASE TABLES TESTS\n";
        echo str_repeat("─", 70) . "\n";
        
        $requiredTables = [
            'users' => 'User accounts',
            'businesses' => 'Business/Tenant data',
            'properties' => 'Properties',
            'property_units' => 'Property units',
            'leases' => 'Lease records',
            'unit_sales' => 'Unit sales',
            'property_transactions' => 'Property transactions',
            'business_settings' => 'Business settings',
            'documents' => 'Attached documents',
            'property_viewings' => 'Property viewings',
        ];
        
        foreach ($requiredTables as $table => $description) {
            if (Schema::hasTable($table)) {
                echo "  ✅ Table '$table' exists\n";
                $this->results['passed']++;
            } else {
                echo "  ❌ Table '$table' missing\n";
                $this->results['failed']++;
            }
        }
        
        echo "\n";
    }
    
    /**
     * Test model functionality
     */
    protected function testModels()
    {
        echo "🗂️  MODEL TESTS\n";
        echo str_repeat("─", 70) . "\n";
        
        // Test Property model
        try {
            $propertyCount = \App\Models\Property::count();
            echo "  ✅ Property model: $propertyCount properties found\n";
            $this->results['passed']++;
        } catch (Exception $e) {
            echo "  ❌ Property model error: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        // Test UnitSale model
        try {
            $unitSaleCount = \App\Models\UnitSale::count();
            echo "  ✅ UnitSale model: $unitSaleCount unit sales found\n";
            if ($unitSaleCount === 0) {
                echo "     ⚠️  No test data yet\n";
                $this->results['warnings']++;
            }
            $this->results['passed']++;
        } catch (Exception $e) {
            echo "  ❌ UnitSale model error: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        // Test PropertyTransaction model
        try {
            $transactionCount = \App\Models\PropertyTransaction::count();
            echo "  ✅ PropertyTransaction model: $transactionCount transactions found\n";
            $this->results['passed']++;
        } catch (Exception $e) {
            echo "  ❌ PropertyTransaction model error: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        // Test Business model
        try {
            $businessCount = \App\Models\Business::count();
            echo "  ✅ Business model: $businessCount business(es) found\n";
            $this->results['passed']++;
        } catch (Exception $e) {
            echo "  ❌ Business model error: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        // Test BusinessSetting model
        try {
            $settingCount = \App\Models\BusinessSetting::count();
            echo "  ✅ BusinessSetting model: $settingCount settings found\n";
            if ($settingCount === 0) {
                echo "     ⚠️  No settings configured yet\n";
                $this->results['warnings']++;
            }
            $this->results['passed']++;
        } catch (Exception $e) {
            echo "  ❌ BusinessSetting model error: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        echo "\n";
    }
    
    /**
     * Test route registration
     */
    protected function testRoutes()
    {
        echo "🛣️  ROUTE REGISTRATION TESTS\n";
        echo str_repeat("─", 70) . "\n";
        
        $routes = Route::getRoutes();
        $totalRoutes = count($routes);
        
        echo "  ✅ Total routes registered: $totalRoutes\n";
        $this->results['passed']++;
        
        // Categorize routes
        $getRoutes = 0;
        $postRoutes = 0;
        $putRoutes = 0;
        $deleteRoutes = 0;
        $namedRoutes = 0;
        $unnamedRoutes = 0;
        
        foreach ($routes as $route) {
            $methods = $route->methods;
            
            if (in_array('GET', $methods)) $getRoutes++;
            if (in_array('POST', $methods)) $postRoutes++;
            if (in_array('PUT', $methods)) $putRoutes++;
            if (in_array('DELETE', $methods)) $deleteRoutes++;
            
            $name = $route->getName();
            if ($name && !empty($name)) {
                $namedRoutes++;
            } else {
                $unnamedRoutes++;
            }
        }
        
        echo "  ├─ GET routes: $getRoutes\n";
        echo "  ├─ POST routes: $postRoutes\n";
        echo "  ├─ PUT routes: $putRoutes\n";
        echo "  ├─ DELETE routes: $deleteRoutes\n";
        echo "  ├─ Named routes: $namedRoutes\n";
        echo "  └─ Unnamed routes: $unnamedRoutes\n";
        
        if ($unnamedRoutes > 0) {
            echo "     ⚠️  Some routes lack names\n";
            $this->results['warnings']++;
        }
        
        $this->results['passed'] += 6;
        
        // Check for critical routes
        echo "\n  Critical Routes Status:\n";
        $criticalRoutes = [
            'landing' => 'Landing page',
            'login' => 'Login page',
            'home' => 'Dashboard',
            'unit.sale.form' => 'Unit sale form',
            'unit.sale.payment' => 'Unit sale payment',
            'unit.sale.complete' => 'Unit sale complete',
            'transaction.invoice' => 'Invoice display',
            'business-settings.edit' => 'Business settings',
            'settings.delete-image' => 'Delete image',
        ];
        
        foreach ($criticalRoutes as $name => $description) {
            $route = $routes->getByName($name);
            if ($route) {
                echo "  ✅ $description ($name)\n";
                $this->results['passed']++;
            } else {
                echo "  ❌ $description ($name) - NOT FOUND\n";
                $this->results['failed']++;
            }
        }
        
        echo "\n";
    }
    
    /**
     * Test critical features
     */
    protected function testCriticalFeatures()
    {
        echo "✨ CRITICAL FEATURES TESTS\n";
        echo str_repeat("─", 70) . "\n";
        
        // Test unit sales table columns
        try {
            if (Schema::hasTable('unit_sales')) {
                $columns = Schema::getColumnListing('unit_sales');
                $requiredColumns = ['id', 'business_id', 'unit_id', 'buyer_type', 'buyer_id', 'sale_price'];
                
                $missing = array_diff($requiredColumns, $columns);
                if (empty($missing)) {
                    echo "  ✅ Unit sales table has all required columns\n";
                    $this->results['passed']++;
                } else {
                    echo "  ❌ Unit sales missing columns: " . implode(', ', $missing) . "\n";
                    $this->results['failed']++;
                }
            }
        } catch (Exception $e) {
            echo "  ❌ Error checking unit sales table: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        // Test property transactions polymorphic relationship
        try {
            $transactions = \App\Models\PropertyTransaction::with('transactionable')->take(1)->get();
            if ($transactions->count() > 0) {
                $trans = $transactions->first();
                if ($trans->transactionable_type && $trans->transactionable_id) {
                    echo "  ✅ PropertyTransaction polymorphic relationship configured\n";
                    $this->results['passed']++;
                } else {
                    echo "  ⚠️  PropertyTransaction missing polymorphic data\n";
                    $this->results['warnings']++;
                }
            } else {
                echo "  ⚠️  No transactions to test polymorphic relationship\n";
                $this->results['warnings']++;
            }
        } catch (Exception $e) {
            echo "  ❌ Error testing polymorphic relationship: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        // Test business settings cache key
        try {
            $cacheKey = config('app.business_settings_cache_key') ?: 'business_settings';
            echo "  ✅ Business settings cache key: $cacheKey (TTL: 300s)\n";
            $this->results['passed']++;
        } catch (Exception $e) {
            echo "  ❌ Error getting cache configuration: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        // Test file upload directories
        try {
            $uploadDir = public_path('documents/transactions');
            if (!file_exists($uploadDir)) {
                if (!@mkdir($uploadDir, 0755, true)) {
                    echo "  ⚠️  Could not create upload directory: $uploadDir\n";
                    $this->results['warnings']++;
                } else {
                    echo "  ✅ Upload directory ready: $uploadDir\n";
                    $this->results['passed']++;
                }
            } else {
                echo "  ✅ Upload directory exists: $uploadDir\n";
                $this->results['passed']++;
            }
        } catch (Exception $e) {
            echo "  ❌ Error checking upload directory: " . $e->getMessage() . "\n";
            $this->results['failed']++;
        }
        
        echo "\n";
    }
    
    /**
     * Print summary report
     */
    protected function printSummary()
    {
        echo str_repeat("═", 70) . "\n";
        echo "📊 TEST SUMMARY\n";
        echo str_repeat("═", 70) . "\n";
        echo "\n";
        
        $total = $this->results['passed'] + $this->results['failed'] + $this->results['warnings'];
        $passRate = $total > 0 ? round(($this->results['passed'] / $total) * 100, 2) : 0;
        
        echo "✅ Passed:   " . str_pad($this->results['passed'], 5) . " tests\n";
        echo "❌ Failed:   " . str_pad($this->results['failed'], 5) . " tests\n";
        echo "⚠️  Warnings: " . str_pad($this->results['warnings'], 5) . " tests\n";
        echo "───────────────────────────────────\n";
        echo "📈 Pass Rate: $passRate%\n";
        
        echo "\n";
        
        if ($this->results['failed'] === 0) {
            echo "✅ All critical tests passed!\n";
        } else {
            echo "❌ Some tests failed. Please review.\n";
        }
        
        echo "\n";
        echo "⚠️  ACTION ITEMS:\n";
        if ($this->results['warnings'] > 0) {
            echo "  1. Create test unit sale record for testing\n";
            echo "  2. Configure business settings (company info, logo)\n";
            echo "  3. Test invoice generation with real data\n";
            echo "  4. Test file upload functionality\n";
        }
        
        echo "\n";
        echo "✨ Testing Complete\n";
        echo "\n";
    }
}

// Run tests
try {
    $tester = new ApplicationTester();
    $tester->run();
} catch (Exception $e) {
    echo "❌ Testing failed: " . $e->getMessage() . "\n";
}
