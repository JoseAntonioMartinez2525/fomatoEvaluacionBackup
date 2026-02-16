<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExternalFormProvider;

class CheckProviderNetwork extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'providers:check-network {provider : provider id or name} {--timeout=10 : timeout seconds}';

    /**
     * The console command description.
     */
    protected $description = 'Diagnose DNS/TCP/HTTPS connectivity to an ExternalFormProvider endpoint';

    public function handle()
    {
        $providerArg = $this->argument('provider');
        $timeout = (int) $this->option('timeout');

        $provider = ExternalFormProvider::where('id', $providerArg)->orWhere('name', $providerArg)->first();
        if (!$provider) {
            $this->error('Provider not found: ' . $providerArg);
            return 1;
        }

        if (empty($provider->endpoint)) {
            $this->error('Provider has no endpoint set.');
            return 1;
        }

        $this->info('Endpoint: ' . $provider->endpoint);

        $url = $provider->endpoint;
        $parts = parse_url($url);
        if (!$parts || empty($parts['host'])) {
            $this->error('Cannot parse host from endpoint URL.');
            return 1;
        }

        $host = $parts['host'];
        $port = $parts['port'] ?? (($parts['scheme'] ?? 'https') === 'https' ? 443 : 80);

        $this->line('Host: ' . $host);
        $this->line('Port: ' . $port);

        // DNS resolution
        $this->line('Resolving DNS...');
        $ips = @gethostbynamel($host);
        if ($ips === false) {
            $this->error('DNS resolution failed (gethostbynamel returned false).');
        } else {
            $this->info('Resolved IPs: ' . implode(', ', $ips));
        }

        // dns_get_record if available
        if (function_exists('dns_get_record')) {
            $this->line('Checking DNS records...');
            $records = @dns_get_record($host, DNS_A + DNS_AAAA + DNS_CNAME);
            if ($records === false || count($records) === 0) {
                $this->error('No DNS A/AAAA/CNAME records returned.');
            } else {
                $this->info('DNS records count: ' . count($records));
            }
        }

        // TCP connect using stream_socket_client
        $this->line('Testing TCP connect (stream_socket_client)...');
        $scheme = ($parts['scheme'] ?? 'https');
        $target = ($scheme === 'https' ? 'ssl://' : '') . $host . ':' . $port;
        $errno = 0; $errstr = '';
        $ctx = stream_context_create([]);
        $conn = @stream_socket_client($target, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $ctx);
        if ($conn === false) {
            $this->error('TCP connect failed: ' . trim($errstr));
        } else {
            $this->info('TCP connect succeeded.');
            fclose($conn);
        }

        // fsockopen fallback
        $this->line('Testing fsockopen...');
        $fs = @fsockopen(($scheme === 'https' ? 'ssl://' : '') . $host, $port, $errno, $errstr, $timeout);
        if (!$fs) {
            $this->error('fsockopen failed: ' . trim($errstr));
        } else {
            $this->info('fsockopen succeeded.');
            fclose($fs);
        }

        // Try PHP cURL if available
        if (function_exists('curl_init')) {
            $this->line('Performing HTTP HEAD request via cURL...');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $res = curl_exec($ch);
            $errno = curl_errno($ch);
            $err = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($res === false) {
                $this->error('cURL request failed: [' . $errno . '] ' . $err);
            } else {
                $this->info('cURL request ok, HTTP code: ' . $http_code);
            }
        } else {
            $this->line('cURL extension not available in PHP.');
        }

        $this->info('Network checks complete.');
        return 0;
    }
}
