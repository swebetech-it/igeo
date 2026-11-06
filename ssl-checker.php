<?php

/**
 * iGEO SSL Configuration Checker
 *
 * This script checks the SSL configuration and security headers
 * for the iGEO website in production.
 */

class SSLChecker
{
    private $domain;
    private $timeout;

    public function __construct($domain = 'dssengineercoltd.co.tz', $timeout = 30)
    {
        $this->domain = $domain;
        $this->timeout = $timeout;
    }

    /**
     * Check SSL certificate details
     */
    public function checkSSLCertificate()
    {
        echo "🔒 Checking SSL Certificate for {$this->domain}...\n";
        echo str_repeat("=", 50) . "\n";

        $context = stream_context_create([
            "ssl" => [
                "capture_peer_cert" => true,
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);

        $socket = @stream_socket_client(
            "ssl://{$this->domain}:443",
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$socket) {
            echo "❌ Failed to connect: $errstr ($errno)\n";
            return false;
        }

        $cert = stream_context_get_params($socket)["options"]["ssl"]["peer_certificate"];
        $certinfo = openssl_x509_parse($cert);

        // Certificate details
        echo "✅ SSL Certificate Information:\n";
        echo "   Issuer: " . $certinfo['issuer']['CN'] . "\n";
        echo "   Subject: " . $certinfo['subject']['CN'] . "\n";
        echo "   Valid From: " . date('Y-m-d H:i:s', $certinfo['validFrom_time_t']) . "\n";
        echo "   Valid Until: " . date('Y-m-d H:i:s', $certinfo['validTo_time_t']) . "\n";

        // Check expiration
        $daysUntilExpiry = ($certinfo['validTo_time_t'] - time()) / (60 * 60 * 24);
        echo "   Days Until Expiry: " . round($daysUntilExpiry) . "\n";

        if ($daysUntilExpiry < 30) {
            echo "   ⚠️  Certificate expires in less than 30 days!\n";
        } else {
            echo "   ✅ Certificate is valid\n";
        }

        // Check subject alternative names
        if (isset($certinfo['extensions']['subjectAltName'])) {
            echo "   Alternative Names: " . $certinfo['extensions']['subjectAltName'] . "\n";
        }

        fclose($socket);
        echo "\n";
        return true;
    }

    /**
     * Check security headers
     */
    public function checkSecurityHeaders()
    {
        echo "🛡️  Checking Security Headers...\n";
        echo str_repeat("=", 50) . "\n";

        $url = "https://{$this->domain}";
        $headers = get_headers($url, 1);

        if (!$headers) {
            echo "❌ Failed to fetch headers\n";
            return false;
        }

        $securityHeaders = [
            'Strict-Transport-Security' => 'HSTS (HTTP Strict Transport Security)',
            'X-Content-Type-Options' => 'Content Type Options',
            'X-Frame-Options' => 'Frame Options (Clickjacking Protection)',
            'X-XSS-Protection' => 'XSS Protection',
            'Content-Security-Policy' => 'Content Security Policy',
            'Referrer-Policy' => 'Referrer Policy',
            'Permissions-Policy' => 'Permissions Policy',
        ];

        foreach ($securityHeaders as $header => $description) {
            if (isset($headers[$header])) {
                echo "   ✅ $description: " . $headers[$header] . "\n";
            } else {
                echo "   ❌ Missing: $description\n";
            }
        }

        echo "\n";
        return true;
    }

    /**
     * Check HTTP to HTTPS redirect
     */
    public function checkHTTPSRedirect()
    {
        echo "🔄 Checking HTTP to HTTPS Redirect...\n";
        echo str_repeat("=", 50) . "\n";

        $httpUrl = "http://{$this->domain}";

        $context = stream_context_create([
            'http' => [
                'method' => 'HEAD',
                'follow_location' => 0,
                'timeout' => $this->timeout,
            ]
        ]);

        $headers = @get_headers($httpUrl, 1, $context);

        if ($headers && strpos($headers[0], '301') !== false) {
            echo "   ✅ HTTP redirects to HTTPS (301 Permanent Redirect)\n";
            if (isset($headers['Location']) && strpos($headers['Location'], 'https://') === 0) {
                echo "   ✅ Redirect location: " . $headers['Location'] . "\n";
            }
        } elseif ($headers && strpos($headers[0], '302') !== false) {
            echo "   ⚠️  HTTP redirects to HTTPS (302 Temporary Redirect)\n";
            echo "   💡 Consider using 301 for better SEO\n";
        } else {
            echo "   ❌ No HTTPS redirect detected\n";
        }

        echo "\n";
        return true;
    }

    /**
     * Check Laravel application status
     */
    public function checkApplicationStatus()
    {
        echo "🚀 Checking Laravel Application...\n";
        echo str_repeat("=", 50) . "\n";

        $url = "https://{$this->domain}";

        $context = stream_context_create([
            'http' => [
                'timeout' => $this->timeout,
                'user_agent' => 'DSS Engineering SSL Checker'
            ]
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            echo "   ❌ Failed to load application\n";
            return false;
        }

        // Check for Laravel indicators
        if (strpos($response, 'Laravel') !== false ||
            strpos($response, 'livewire') !== false ||
            strpos($response, 'DSS Engineering') !== false) {
            echo "   ✅ Laravel application is responding\n";
        } else {
            echo "   ⚠️  Application response unclear\n";
        }

        // Check response size
        $responseSize = strlen($response);
        echo "   📊 Response size: " . number_format($responseSize) . " bytes\n";

        // Check for common issues
        if (strpos($response, 'Mixed Content') !== false) {
            echo "   ⚠️  Mixed content warnings detected\n";
        }

        if (strpos($response, 'error') !== false || strpos($response, 'Error') !== false) {
            echo "   ⚠️  Potential errors detected in response\n";
        }

        echo "\n";
        return true;
    }

    /**
     * Run comprehensive SSL check
     */
    public function runFullCheck()
    {
        echo "🏗️  DSS Engineering Co. Ltd - SSL Configuration Check\n";
        echo "Domain: {$this->domain}\n";
        echo "Timestamp: " . date('Y-m-d H:i:s T') . "\n";
        echo str_repeat("=", 60) . "\n\n";

        $this->checkSSLCertificate();
        $this->checkSecurityHeaders();
        $this->checkHTTPSRedirect();
        $this->checkApplicationStatus();

        echo "🎉 SSL Configuration Check Complete!\n";
        echo str_repeat("=", 60) . "\n";
        echo "💡 Recommendations:\n";
        echo "   - Monitor certificate expiration\n";
        echo "   - Regularly update security headers\n";
        echo "   - Test SSL configuration monthly\n";
        echo "   - Use SSL monitoring services\n\n";

        echo "🔗 Additional Testing:\n";
        echo "   - SSL Labs: https://www.ssllabs.com/ssltest/analyze.html?d={$this->domain}\n";
        echo "   - Security Headers: https://securityheaders.com/?q=https://{$this->domain}\n";
        echo "   - Observatory: https://observatory.mozilla.org/analyze/{$this->domain}\n";
    }
}

// Command line usage
if (php_sapi_name() === 'cli') {
    $domain = $argv[1] ?? 'dssengineercoltd.co.tz';
    $checker = new SSLChecker($domain);
    $checker->runFullCheck();
} else {
    // Web usage
    header('Content-Type: text/plain');
    $domain = $_GET['domain'] ?? 'dssengineercoltd.co.tz';
    $checker = new SSLChecker($domain);
    $checker->runFullCheck();
}
