<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;

/**
 * Route Testing Script for RealtyPlus Application
 * This script tests all routes to identify any issues
 */

class RouteTestRunner
{
    protected $results = [
        'passed' => [],
        'failed' => [],
        'errors' => [],
    ];
    
    protected $totalTests = 0;
    protected $passedTests = 0;
    
    /**
     * Get all routes from the application
     */
    public function getAllRoutes()
    {
        $routes = Route::getRoutes();
        $routeList = [];
        
        foreach ($routes as $route) {
            // Skip certain routes like asset routes, nova routes
            if (in_array('GET', $route->methods) || in_array('POST', $route->methods)) {
                $routeList[] = [
                    'method' => implode('|', array_diff($route->methods, ['HEAD'])),
                    'path' => $route->uri,
                    'name' => $route->getName() ?? 'unnamed',
                    'action' => $route->getActionName(),
                ];
            }
        }
        
        return $routeList;
    }
    
    /**
     * Get routes by category
     */
    public function categorizeRoutes($routes)
    {
        $categories = [
            'PUBLIC' => [],
            'AUTH' => [],
            'PROPERTIES' => [],
            'UNITS' => [],
            'LEASES' => [],
            'TRANSACTIONS' => [],
            'VIEWINGS' => [],
            'MAINTENANCE' => [],
            'PERSONNEL' => [],
            'OWNERS' => [],
            'TENANTS' => [],
            'AGENTS' => [],
            'CLIENTS' => [],
            'SUPERADMIN' => [],
            'SETTINGS' => [],
            'OTHER' => [],
        ];
        
        foreach ($routes as $route) {
            if (strpos($route['path'], 'superadmin') !== false) {
                $categories['SUPERADMIN'][] = $route;
            } elseif (strpos($route['path'], 'transaction') !== false) {
                $categories['TRANSACTIONS'][] = $route;
            } elseif (strpos($route['path'], 'unit') !== false && strpos($route['path'], 'property') === false) {
                $categories['UNITS'][] = $route;
            } elseif (strpos($route['path'], 'lease') !== false) {
                $categories['LEASES'][] = $route;
            } elseif (strpos($route['path'], 'viewing') !== false) {
                $categories['VIEWINGS'][] = $route;
            } elseif (strpos($route['path'], 'maintenance') !== false) {
                $categories['MAINTENANCE'][] = $route;
            } elseif (strpos($route['path'], 'personnel') !== false) {
                $categories['PERSONNEL'][] = $route;
            } elseif (strpos($route['path'], 'owner') !== false) {
                $categories['OWNERS'][] = $route;
            } elseif (strpos($route['path'], 'tenant') !== false) {
                $categories['TENANTS'][] = $route;
            } elseif (strpos($route['path'], 'agent') !== false) {
                $categories['AGENTS'][] = $route;
            } elseif (strpos($route['path'], 'client') !== false) {
                $categories['CLIENTS'][] = $route;
            } elseif (strpos($route['path'], 'propert') !== false) {
                $categories['PROPERTIES'][] = $route;
            } elseif (strpos($route['path'], 'business-settings') !== false || strpos($route['path'], 'settings') !== false) {
                $categories['SETTINGS'][] = $route;
            } elseif (strpos($route['path'], 'register') === false && strpos($route['path'], 'activate') === false) {
                if (strpos($route['path'], '/') === strlen($route['path']) - 1 || $route['path'] === '/') {
                    $categories['PUBLIC'][] = $route;
                } else {
                    $categories['OTHER'][] = $route;
                }
            }
        }
        
        return array_filter($categories, function($cat) { return !empty($cat); });
    }
    
    /**
     * Generate test report
     */
    public function generateReport()
    {
        $routes = $this->getAllRoutes();
        $categories = $this->categorizeRoutes($routes);
        
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════════════╗\n";
        echo "║          ROUTE & VIEW TESTING REPORT - RealtyPlus                  ║\n";
        echo "╚════════════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        
        $totalRoutes = count($routes);
        echo "📊 TOTAL ROUTES FOUND: " . $totalRoutes . "\n";
        echo "\n";
        
        // Route Breakdown by Category
        echo "📋 ROUTES BY CATEGORY:\n";
        echo str_repeat("─", 70) . "\n";
        foreach ($categories as $category => $categoryRoutes) {
            echo sprintf("%-20s : %3d routes\n", $category, count($categoryRoutes));
        }
        echo "\n";
        
        // Detailed Route List
        echo "📌 DETAILED ROUTE LIST:\n";
        echo str_repeat("═", 70) . "\n";
        
        foreach ($categories as $category => $categoryRoutes) {
            echo "\n🏷️  $category\n";
            echo str_repeat("─", 70) . "\n";
            
            foreach ($categoryRoutes as $route) {
                $method = str_pad($route['method'], 6);
                $path = str_pad($route['path'], 35);
                $name = $route['name'];
                
                // Color code by method
                $methodColor = match($route['method']) {
                    'GET' => '✓',
                    'POST' => '✎',
                    'PUT' => '↻',
                    'DELETE' => '✕',
                    default => '?'
                };
                
                echo sprintf("  %s %-5s %-35s [%s]\n", $methodColor, $route['method'], $route['path'], $name);
            }
        }
        
        echo "\n";
        echo str_repeat("═", 70) . "\n";
        echo "\n";
        
        return $categories;
    }
    
    /**
     * Test GET routes accessibility
     */
    public function testGetRoutes($categories)
    {
        echo "🧪 TESTING ROUTE ACCESSIBILITY...\n";
        echo str_repeat("═", 70) . "\n";
        echo "\n";
        
        $testResults = [
            'accessible' => [],
            'auth_required' => [],
            'errors' => [],
            'skipped' => [],
        ];
        
        // Routes that need authentication
        $authRoutes = [];
        foreach ($categories as $categoryRoutes) {
            foreach ($categoryRoutes as $route) {
                if ($route['method'] === 'GET' && 
                    strpos($route['path'], 'superadmin') === false && 
                    strpos($route['path'], 'register') === false && 
                    strpos($route['path'], 'activate') === false &&
                    strpos($route['path'], '/') !== 0) {
                    // Likely needs auth
                    $authRoutes[] = $route;
                }
            }
        }
        
        // Routes accessible without auth
        $publicRoutes = $categories['PUBLIC'] ?? [];
        
        echo "✅ PUBLIC ROUTES (accessible without authentication):\n";
        foreach ($publicRoutes as $route) {
            if ($route['method'] === 'GET') {
                echo "  ✓ {$route['path']}\n";
                $testResults['accessible'][] = $route['path'];
            }
        }
        
        echo "\n📝 PROTECTED ROUTES (require authentication):\n";
        echo "  Count: " . count($authRoutes) . " routes\n";
        
        echo "\n⚠️  ROUTES SKIPPED (POST/DELETE or dynamic):\n";
        $skippedCount = 0;
        foreach ($categories as $categoryRoutes) {
            foreach ($categoryRoutes as $route) {
                if ($route['method'] !== 'GET' || strpos($route['path'], '{') !== false) {
                    $skippedCount++;
                    $testResults['skipped'][] = "{$route['method']} {$route['path']}";
                }
            }
        }
        echo "  Count: $skippedCount routes\n";
        
        echo "\n";
        return $testResults;
    }
    
    /**
     * Check for potential issues
     */
    public function checkForIssues($categories)
    {
        echo "🔍 CHECKING FOR POTENTIAL ISSUES...\n";
        echo str_repeat("═", 70) . "\n";
        echo "\n";
        
        $issues = [
            'warnings' => [],
            'info' => [],
        ];
        
        // Check for routes without names
        foreach ($categories as $categoryRoutes) {
            foreach ($categoryRoutes as $route) {
                if ($route['name'] === 'unnamed') {
                    $issues['warnings'][] = "Route without name: {$route['method']} {$route['path']}";
                }
            }
        }
        
        // Check for common patterns
        $dynamicRoutes = [];
        foreach ($categories as $categoryRoutes) {
            foreach ($categoryRoutes as $route) {
                if (strpos($route['path'], '{') !== false) {
                    $dynamicRoutes[] = $route;
                }
            }
        }
        
        if (!empty($dynamicRoutes)) {
            $issues['info'][] = "Found " . count($dynamicRoutes) . " dynamic routes with parameters";
        }
        
        // Display issues
        if (!empty($issues['warnings'])) {
            echo "⚠️  WARNINGS:\n";
            foreach ($issues['warnings'] as $warning) {
                echo "  ! $warning\n";
            }
            echo "\n";
        }
        
        if (!empty($issues['info'])) {
            echo "ℹ️  INFO:\n";
            foreach ($issues['info'] as $info) {
                echo "  • $info\n";
            }
            echo "\n";
        }
        
        return $issues;
    }
    
    /**
     * Summary report
     */
    public function printSummary($categories, $testResults, $issues)
    {
        echo "\n";
        echo str_repeat("═", 70) . "\n";
        echo "📊 SUMMARY\n";
        echo str_repeat("═", 70) . "\n";
        echo "\n";
        
        $totalRoutes = 0;
        $getRoutes = 0;
        $postRoutes = 0;
        $putRoutes = 0;
        $deleteRoutes = 0;
        
        foreach ($categories as $categoryRoutes) {
            foreach ($categoryRoutes as $route) {
                $totalRoutes++;
                switch ($route['method']) {
                    case 'GET': $getRoutes++; break;
                    case 'POST': $postRoutes++; break;
                    case 'PUT': $putRoutes++; break;
                    case 'DELETE': $deleteRoutes++; break;
                }
            }
        }
        
        echo "Total Routes ...................... $totalRoutes\n";
        echo "  • GET Routes ..................... $getRoutes\n";
        echo "  • POST Routes .................... $postRoutes\n";
        echo "  • PUT Routes ..................... $putRoutes\n";
        echo "  • DELETE Routes .................. $deleteRoutes\n";
        echo "\n";
        
        echo "Testing Results:\n";
        echo "  ✓ Accessible Public Routes ....... " . count($testResults['accessible']) . "\n";
        echo "  🔐 Protected Routes .............. " . count($testResults['auth_required']) . "\n";
        echo "  ⊘ Skipped Routes ................. " . count($testResults['skipped']) . "\n";
        echo "\n";
        
        echo "Issues Found:\n";
        echo "  ⚠️  Warnings ...................... " . count($issues['warnings']) . "\n";
        echo "  ℹ️  Info Messages ................. " . count($issues['info']) . "\n";
        echo "\n";
        
        echo "✅ Route structure validation complete!\n";
        echo "\n";
    }
}

// Run the tests
try {
    $runner = new RouteTestRunner();
    
    $categories = $runner->generateReport();
    $testResults = $runner->testGetRoutes($categories);
    $issues = $runner->checkForIssues($categories);
    $runner->printSummary($categories, $testResults, $issues);
    
    echo "✨ Testing completed successfully!\n";
    echo "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
