<?php

namespace App\Libraries;

class SecurityEngine
{
    /**
     * Common SQL Injection Patterns
     */
    protected array $sqliPatterns = [
        'union\s+select',
        'union\s+all\s+select',
        'information_schema',
        'select\s+.*\s+from',
        'update\s+.*\s+set',
        'delete\s+from',
        'drop\s+table',
        'waitfor\s+delay',
        'exec\(',
        'execute\(',
        '--\s',
        '#\s',
        'or\s+1=1',
        '\'\s+or\s+\'',
        '\"\s+or\s+\"',
        'union.*select', 
        'select.*from',
        'and\s+1=1',
        'having\s+1=1',
    ];

    /**
     * Common XSS Patterns
     */
    protected array $xssPatterns = [
        '<script>',
        'javascript:',
        'vbscript:',
        'onload=',
        'onerror=',
        'onclick=',
        'onmouseover=',
        'eval\(',
        'alert\(',
        'prompt\(',
        'document\.cookie',
        'document\.location',
    ];

    /**
     * Blocked User Agents (Crawlers, Scanners)
     */
    protected array $blockedUserAgents = [
        'sqlmap',
        'nikto',
        'nessus',
        'openvas',
        'nmap',
        'w3af',
        'netsparker',
        'acunetix',
        'havij',
    ];

    /**
     * Analyze a single input string for malicious patterns.
     *
     * @param string $input
     * @param string $context (sql, xss, etc)
     * @return array|null Returns array of detection details or null if safe
     */
    public function analyzeInput(string $input, string $context = 'all'): ?array
    {
        $input = strtolower(urldecode($input)); // Basic normalization

        if ($context === 'all' || $context === 'sql') {
            foreach ($this->sqliPatterns as $pattern) {
                if (preg_match('/' . $pattern . '/i', $input)) {
                    return ['type' => 'SQL Injection', 'pattern' => $pattern];
                }
            }
        }

        if ($context === 'all' || $context === 'xss') {
            foreach ($this->xssPatterns as $pattern) {
                // Remove backslashes from the pattern definition if they were added for escaping
                $cleanPattern = str_replace('\\', '', $pattern);
                
                if (preg_match('/' . preg_quote($cleanPattern, '/') . '/i', $input)) {
                    return ['type' => 'XSS', 'pattern' => $pattern];
                }
            }
        }

        return null; // Safe
    }

    /**
     * Check if a User Agent is blacklisted
     */
    public function checkUserAgent(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return false; // Or true if we want to block empty UA
        }

        $userAgent = strtolower($userAgent);
        foreach ($this->blockedUserAgents as $blocked) {
            if (str_contains($userAgent, $blocked)) {
                return true; // Blocked
            }
        }

        return false;
    }

    /**
     * Rate Limiting Logic
     * Avoids banning users for simply refreshing pages too fast.
     */
    public function checkRateLimit(\CodeIgniter\HTTP\RequestInterface $request)
    {
        // Use service helper which is more robust
        $throttle = service('throttler');
        
        if (!$throttle) {
            // If throttler service is unavailable, skip rate limiting instead of crashing
            return null;
        }

        $ip = $request->getIPAddress();
        // Hash the IP to avoid invalid characters in cache keys (e.g., IPv6 colons on Windows)
        $cacheKey = md5($ip);
        
        // Allow 60 requests per minute per IP
        if ($throttle->check($cacheKey, 60, 60) === false) {
            return [
                'type' => 'Rate Limit Exceeded',
                'details' => 'Too many requests from this IP in 1 minute.',
                'severity' => 'low' // Low severity = temporary block (429), not permanent ban
            ];
        }
        return null;
    }

    /**
     * Analyze the entire Request object (Headers, GET, POST)
     * 
     * @param \CodeIgniter\HTTP\RequestInterface $request
     * @return array|null Returns detection result
     */
    public function analyzeRequest($request): ?array
    {
        // 0. Rate Limit Check
        $rateLimit = $this->checkRateLimit($request);
        if ($rateLimit) {
            return $rateLimit;
        }

        // 1. Check User Agent
        $ua = $request->getUserAgent()->getAgentString();
        if ($this->checkUserAgent($ua)) {
            return ['type' => 'Bad Bot', 'details' => 'Blocked User Agent: ' . $ua, 'severity' => 'medium'];
        }

        // 2. Header Analysis
        $headerThreat = $this->analyzeHeaders($request);
        if ($headerThreat) {
            return $headerThreat;
        }

        // 3. recursive check of GET/POST/JSON
        $json = $request->getJSON(true) ?? [];
        $inputs = array_merge(
            $request->getGet() ?? [], 
            $request->getPost() ?? [],
            is_array($json) ? $json : [] 
        );
        
        // Flatten array for analysis
        $flatInputs = $this->flatten($inputs);
        
        foreach ($flatInputs as $key => $value) {
            if (is_string($value)) {
                // SQLi Check
                $sqli = $this->analyzeInput($value, 'sql');
                if ($sqli) {
                    return array_merge($sqli, ['severity' => 'critical', 'details' => "SQLi in $key"]);
                }

                // XSS Check
                $xss = $this->analyzeInput($value, 'xss');
                if ($xss) {
                    return array_merge($xss, ['severity' => 'high', 'details' => "XSS in $key"]);
                }

                // LFI Check
                $lfi = $this->detectLFI($value);
                if ($lfi) {
                    return array_merge($lfi, ['severity' => 'critical', 'details' => "LFI in $key"]);
                }
            }
        }

        return null;
    }

    /**
     * Detect Local File Inclusion (LFI) / Path Traversal
     */
    public function detectLFI(string $input): ?array
    {
        $input = urldecode($input);
        
        $patterns = [
            '/\.\.[\/\\\\]/', // Matches ../ or ..\
            '/etc\/passwd/i',
            '/etc\/shadow/i',
            '/boot\.ini/i',
            '/windows\/system32/i',
            '/php:\/\/filter/i',
            '/php:\/\/input/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return ['type' => 'Path Traversal (LFI)', 'pattern' => $pattern];
            }
        }
        return null;
    }

    /**
     * Analyze HTTP Headers for Injection
     */
    public function analyzeHeaders(\CodeIgniter\HTTP\RequestInterface $request): ?array
    {
        $headersToCheck = ['User-Agent', 'Referer', 'X-Forwarded-For', 'Host'];
        
        foreach ($headersToCheck as $header) {
            $value = $request->header($header)?->getValue();
            if ($value) {
                // Check for Log4Shell or specific header exploits
                if (stripos($value, '${jndi:') !== false) {
                    return ['type' => 'RCE Attempt (Log4Shell)', 'severity' => 'critical', 'details' => "Found in $header"];
                }
                
                // Reuse generic injection checks on headers
                $sqli = $this->analyzeInput($value, 'sql');
                if ($sqli) {
                    return ['type' => 'SQL Injection in Header', 'severity' => 'critical', 'details' => "Found in $header"];
                }
                
                $xss = $this->analyzeInput($value, 'xss');
                if ($xss) {
                    return ['type' => 'XSS in Header', 'severity' => 'high', 'details' => "Found in $header"];
                }
            }
        }
        return null;
    }

    private function flatten(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->flatten($value, $prefix . $key . '.'));
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }
}
