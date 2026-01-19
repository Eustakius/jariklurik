<?php

/**
 * Enhanced Security Test Runner v3.0 for Jarik Lurik
 * 
 * Features:
 * - CLI argument parsing (--category, --verbose, --output, --help)
 * - Performance metrics (response time, throughput)
 * - Multiple output formats (text, json, html)
 * - Test filtering by category
 * - Detailed request/response logging
 * 
 * Usage:
 *   php security_test_runner_v3.php [options]
 *   
 * Options:
 *   --url=<base_url>        Target URL (default: http://localhost:8081)
 *   --category=<name>       Run only tests from category (SQLi, XSS, LFI, etc.)
 *   --output=<format>       Output format: text, json, html (default: text)
 *   --verbose               Show detailed request/response data
 *   --help                  Display this help message
 */

// Parse CLI arguments
$options = getopt('', ['url:', 'category:', 'output:', 'verbose', 'help']);

if (isset($options['help'])) {
    showHelp();
    exit(0);
}

$baseUrl = $options['url'] ?? 'http://localhost:8081';
$filterCategory = $options['category'] ?? null;
$outputFormat = $options['output'] ?? 'text';
$verbose = isset($options['verbose']);

// Color codes for terminal output
$colors = [
    'GREEN' => "\033[32m",
    'RED' => "\033[31m",
    'YELLOW' => "\033[33m",
    'CYAN' => "\033[36m",
    'RESET' => "\033[0m",
    'BOLD' => "\033[1m"
];

// Test definitions
$tests = [
    // Baseline
    ['category' => 'Baseline', 'name' => 'Normal Homepage Access', 'url' => '/', 'method' => 'GET', 'expect' => [200, 302]],
    
    // SQL Injection
    ['category' => 'SQLi', 'name' => 'SQLi in GET Param', 'url' => '/job-vacancy?q=' . urlencode("' OR '1'='1"), 'method' => 'GET', 'expect' => [403]],
    ['category' => 'SQLi', 'name' => 'SQLi in POST Body', 'url' => '/back-end/login', 'method' => 'POST', 'data' => ['login' => "admin'; DROP TABLE users--"], 'expect' => [403]],
    ['category' => 'SQLi', 'name' => 'SQLi in JSON Body', 'url' => '/api/security/forensics', 'method' => 'JSON', 'data' => ['device_hash' => "' UNION SELECT 1,2,3--"], 'expect' => [403]],
    
    // XSS
    ['category' => 'XSS', 'name' => 'XSS in GET Param', 'url' => '/search?q=' . urlencode("<script>alert(1)</script>"), 'method' => 'GET', 'expect' => [403]],
    ['category' => 'XSS', 'name' => 'XSS in POST Parameter', 'url' => '/back-end/login', 'method' => 'POST', 'data' => ['login' => 'Hello <img src=x onerror=alert(1)>'], 'expect' => [403]],
    ['category' => 'XSS', 'name' => 'XSS in JSON Field', 'url' => '/api/security/quick-scan', 'method' => 'JSON', 'data' => ['target' => 'javascript:alert(1)'], 'expect' => [403]],
    
    // Header Injection
    ['category' => 'Header', 'name' => 'Malicious User-Agent (SQLMap)', 'url' => '/', 'method' => 'GET', 'headers' => ['User-Agent: sqlmap/1.5.2'], 'expect' => [403]],
    ['category' => 'Header', 'name' => 'XSS in Referer Header', 'url' => '/', 'method' => 'GET', 'headers' => ['Referer: <script>alert(1)</script>'], 'expect' => [200, 403]],
    
    // Path Traversal
    ['category' => 'LFI', 'name' => 'LFI via GET Parameter', 'url' => '/job-vacancy?file=' . urlencode('../../../../etc/passwd'), 'method' => 'GET', 'expect' => [403]],
    
    // Forensics
    ['category' => 'Forensics', 'name' => 'Valid Forensics Submission', 'url' => '/api/security/forensics', 'method' => 'JSON', 'data' => ['device_hash' => 'test_hash_' . time(), 'canvas_hash' => '12345', 'screen_res' => '1920x1080', 'platform' => 'TestRunner'], 'expect' => [200]],
];

// Filter tests by category if specified
if ($filterCategory) {
    $tests = array_filter($tests, fn($t) => strcasecmp($t['category'], $filterCategory) === 0);
    if (empty($tests)) {
        echo "No tests found for category: $filterCategory\n";
        exit(1);
    }
}

// Execute tests
$results = [];
$stats = ['PASS' => 0, 'FAIL' => 0, 'total_time' => 0];
$startTime = microtime(true);

if ($outputFormat === 'text') {
    echo $colors['CYAN'] . $colors['BOLD'] . "\n=== Security Test Runner v3.0 ===\n" . $colors['RESET'];
    echo "Target: $baseUrl\n";
    if ($filterCategory) echo "Category Filter: $filterCategory\n";
    echo "\n";
}

foreach ($tests as $test) {
    $result = executeTest($baseUrl, $test, $verbose);
    $results[] = $result;
    
    $stats[$result['status']]++;
    $stats['total_time'] += $result['duration'];
    
    if ($outputFormat === 'text') {
        printResult($result, $verbose, $colors);
    }
}

// Rate limit test
if (!$filterCategory || strcasecmp($filterCategory, 'RateLimit') === 0) {
    $rateLimitResult = testRateLimit($baseUrl, $verbose);
    $results[] = $rateLimitResult;
    $stats[$rateLimitResult['status']]++;
    
    if ($outputFormat === 'text') {
        printResult($rateLimitResult, $verbose, $colors);
    }
}

$stats['total_duration'] = microtime(true) - $startTime;
$stats['avg_response_time'] = count($results) > 0 ? $stats['total_time'] / count($results) : 0;

// Output results
switch ($outputFormat) {
    case 'json':
        echo json_encode(['stats' => $stats, 'results' => $results], JSON_PRETTY_PRINT);
        break;
    case 'html':
        generateHtmlReport($stats, $results, $baseUrl);
        break;
    default:
        printSummary($stats, $colors);
}

exit($stats['FAIL'] > 0 ? 1 : 0);

// ============================================================================
// FUNCTIONS
// ============================================================================

function executeTest($baseUrl, $test, $verbose) {
    $start = microtime(true);
    
    $ch = curl_init();
    $url = $baseUrl . $test['url'];
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    
    $headers = $test['headers'] ?? ['User-Agent: SecurityTestRunner/3.0'];
    
    if ($test['method'] === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($test['data'] ?? []));
    } elseif ($test['method'] === 'JSON') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test['data'] ?? []));
        $headers[] = 'Content-Type: application/json';
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $rawResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $duration = microtime(true) - $start;
    curl_close($ch);
    
    $passed = in_array($httpCode, $test['expect']);
    
    return [
        'category' => $test['category'],
        'name' => $test['name'],
        'status' => $passed ? 'PASS' : 'FAIL',
        'http_code' => $httpCode,
        'expected' => $test['expect'],
        'duration' => $duration,
        'request' => [
            'method' => $test['method'],
            'url' => $url,
            'headers' => $headers,
            'data' => $test['data'] ?? null
        ],
        'response' => $verbose ? substr($rawResponse, 0, 500) : null
    ];
}

function testRateLimit($baseUrl, $verbose) {
    $start = microtime(true);
    $burstCount = 70;
    $limitTriggered = false;
    
    for ($i = 0; $i < $burstCount; $i++) {
        $ch = curl_init($baseUrl . '/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 429) {
            $limitTriggered = true;
            break;
        }
    }
    
    $duration = microtime(true) - $start;
    
    return [
        'category' => 'RateLimit',
        'name' => 'Rate Limit Protection',
        'status' => $limitTriggered ? 'PASS' : 'FAIL',
        'http_code' => $limitTriggered ? 429 : 200,
        'expected' => [429],
        'duration' => $duration,
        'request' => ['method' => 'GET', 'url' => $baseUrl . '/', 'burst' => $burstCount],
        'response' => $limitTriggered ? "Triggered after $i requests" : "Failed to trigger after $burstCount requests"
    ];
}

function printResult($result, $verbose, $colors) {
    $col = $result['status'] === 'PASS' ? $colors['GREEN'] : $colors['RED'];
    $name = str_pad($result['name'], 50);
    $status = "[{$col}{$result['status']}{$colors['RESET']}]";
    $details = "(Got {$result['http_code']}, Expected " . implode(',', $result['expected']) . ")";
    
    echo "$name $status $details\n";
    
    if ($verbose && $result['status'] === 'FAIL') {
        echo "  Request: {$result['request']['method']} {$result['request']['url']}\n";
        if ($result['response']) {
            echo "  Response: " . substr($result['response'], 0, 200) . "...\n";
        }
    }
}

function printSummary($stats, $colors) {
    echo "\n============================================\n";
    echo "SUMMARY:\n";
    echo "  " . $colors['GREEN'] . "{$stats['PASS']} Passed" . $colors['RESET'] . " | ";
    echo ($stats['FAIL'] > 0 ? $colors['RED'] : $colors['GREEN']) . "{$stats['FAIL']} Failed" . $colors['RESET'] . "\n";
    echo "  Total Duration: " . number_format($stats['total_duration'], 3) . "s\n";
    echo "  Avg Response Time: " . number_format($stats['avg_response_time'] * 1000, 2) . "ms\n";
    echo "============================================\n";
}

function generateHtmlReport($stats, $results, $baseUrl) {
    $passRate = count($results) > 0 ? ($stats['PASS'] / count($results)) * 100 : 0;
    $passRateFormatted = number_format($passRate, 1);
    $avgResponseFormatted = number_format($stats['avg_response_time'] * 1000, 0);
    $dateNow = date('Y-m-d H:i:s');
    
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Security Test Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .stats { display: flex; gap: 20px; margin: 20px 0; }
        .stat-card { flex: 1; padding: 15px; border-radius: 4px; text-align: center; }
        .stat-card.pass { background: #d4edda; color: #155724; }
        .stat-card.fail { background: #f8d7da; color: #721c24; }
        .stat-card.info { background: #d1ecf1; color: #0c5460; }
        .stat-card h3 { margin: 0; font-size: 32px; }
        .stat-card p { margin: 5px 0 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        .pass { color: #28a745; font-weight: bold; }
        .fail { color: #dc3545; font-weight: bold; }
        .category { background: #e9ecef; padding: 2px 8px; border-radius: 3px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛡️ Security Test Report</h1>
        <p><strong>Target:</strong> $baseUrl</p>
        <p><strong>Date:</strong> $dateNow</p>
        
        <div class="stats">
            <div class="stat-card pass">
                <h3>{$stats['PASS']}</h3>
                <p>Passed</p>
            </div>
            <div class="stat-card fail">
                <h3>{$stats['FAIL']}</h3>
                <p>Failed</p>
            </div>
            <div class="stat-card info">
                <h3>{$passRateFormatted}%</h3>
                <p>Pass Rate</p>
            </div>
            <div class="stat-card info">
                <h3>{$avgResponseFormatted}ms</h3>
                <p>Avg Response</p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Test Name</th>
                    <th>Status</th>
                    <th>HTTP Code</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
HTML;
    
    foreach ($results as $r) {
        $statusClass = strtolower($r['status']);
        $duration = number_format($r['duration'] * 1000, 2);
        $html .= <<<HTML
                <tr>
                    <td><span class="category">{$r['category']}</span></td>
                    <td>{$r['name']}</td>
                    <td class="$statusClass">{$r['status']}</td>
                    <td>{$r['http_code']}</td>
                    <td>{$duration}ms</td>
                </tr>
HTML;
    }
    
    $html .= <<<HTML
            </tbody>
        </table>
    </div>
</body>
</html>
HTML;
    
    $filename = 'security_test_report_' . date('Y-m-d_His') . '.html';
    file_put_contents($filename, $html);
    echo "HTML report saved to: $filename\n";
}

function showHelp() {
    echo <<<HELP
Security Test Runner v3.0

Usage:
  php security_test_runner_v3.php [options]

Options:
  --url=<base_url>        Target URL (default: http://localhost:8081)
  --category=<name>       Run only tests from category:
                          Baseline, SQLi, XSS, Header, LFI, Forensics, RateLimit
  --output=<format>       Output format: text, json, html (default: text)
  --verbose               Show detailed request/response data
  --help                  Display this help message

Examples:
  # Run all tests
  php security_test_runner_v3.php

  # Run only SQL injection tests
  php security_test_runner_v3.php --category=SQLi

  # Generate HTML report
  php security_test_runner_v3.php --output=html

  # Verbose mode with JSON output
  php security_test_runner_v3.php --verbose --output=json

HELP;
}
