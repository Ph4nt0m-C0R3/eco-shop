<?php

namespace App\Support;

class ProductionReadiness
{
    public static function problems(array $settings): array
    {
        $problems = [];
        if (($settings['environment'] ?? null) !== 'production') $problems[] = 'APP_ENV must be production.';
        if ($settings['debug'] ?? true) $problems[] = 'APP_DEBUG must be false.';
        $url = (string) ($settings['url'] ?? '');
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        if (parse_url($url, PHP_URL_SCHEME) !== 'https' || !$host
            || in_array($host, ['localhost', '127.0.0.1', '::1', 'example.com', 'example.org', 'example.net'])
            || preg_match('/\.(invalid|test|example)$/', $host)
            || !in_array(parse_url($url, PHP_URL_PATH), [null, '', '/'], true)
            || parse_url($url, PHP_URL_USER) || parse_url($url, PHP_URL_QUERY) || parse_url($url, PHP_URL_FRAGMENT)) {
            $problems[] = 'APP_URL must identify your real HTTPS application domain.';
        }
        if (empty($settings['key'])) $problems[] = 'A persistent APP_KEY is required; preserve existing keys.';
        if (!($settings['secure_cookie'] ?? false)) $problems[] = 'SESSION_SECURE_COOKIE must be true.';
        if (!($settings['http_only'] ?? false)) $problems[] = 'SESSION_HTTP_ONLY must be true.';
        if (($settings['same_site'] ?? null) !== 'lax') $problems[] = 'SESSION_SAME_SITE must be lax for the supported sign-in/payment flows.';
        if (!in_array($settings['sessions'] ?? '', ['database', 'redis', 'file'])) $problems[] = 'Use a persistent session driver.';
        if (!in_array($settings['cache'] ?? '', ['database', 'redis', 'file', 'memcached'])) $problems[] = 'Use a persistent production cache store.';
        if (!in_array($settings['queue'] ?? '', ['database', 'redis', 'sqs'])) $problems[] = 'Configure a durable queue and a supervised worker.';
        if (($settings['database'] ?? '') !== 'mysql') $problems[] = 'The supported production database profile is MySQL/InnoDB.';
        if (empty($settings['database_user']) || in_array(strtolower($settings['database_user']), ['root', 'admin'])) $problems[] = 'Use a dedicated least-privilege application database user.';
        $password = (string) ($settings['database_password'] ?? '');
        if (!$password || str_contains($password, '__REPLACE_')) $problems[] = 'Provision the database password through private environment configuration.';
        if (in_array($settings['mailer'] ?? 'log', ['log', 'array'])) $problems[] = 'Configure a real mail transport before launch.';
        foreach ($settings['trusted_proxies'] ?? [] as $proxy) {
            $parts = explode('/', $proxy, 2);
            $validIp = filter_var($parts[0], FILTER_VALIDATE_IP);
            $maxMask = str_contains($parts[0], ':') ? 128 : 32;
            if (!$validIp || isset($parts[1]) && (!ctype_digit($parts[1]) || (int) $parts[1] < 1 || (int) $parts[1] > $maxMask)) {
                $problems[] = 'TRUSTED_PROXIES must contain explicit proxy IP addresses/CIDRs; wildcards are prohibited.';
                break;
            }
        }
        if ($settings['private_storage_public'] ?? false) $problems[] = 'Private storage must be outside the public document root.';
        return $problems;
    }

    public static function applicationSettings(): array
    {
        $connection = config('database.connections.'.config('database.default'), []);
        $privateRoot = (string) config('filesystems.disks.local.root');
        $private = str_replace('\\', '/', realpath($privateRoot) ?: $privateRoot);
        $public = str_replace('\\', '/', realpath(public_path()) ?: public_path());
        return [
            'environment' => config('app.env'), 'debug' => config('app.debug'),
            'url' => config('app.url'), 'key' => config('app.key'),
            'secure_cookie' => config('session.secure'), 'http_only' => config('session.http_only'),
            'same_site' => config('session.same_site'), 'sessions' => config('session.driver'),
            'cache' => config('cache.default'), 'queue' => config('queue.default'),
            'database' => config('database.default'), 'database_user' => $connection['username'] ?? '',
            'database_password' => $connection['password'] ?? '', 'mailer' => config('mail.default'),
            'trusted_proxies' => config('deployment.trusted_proxies'),
            'private_storage_public' => str_starts_with(strtolower(rtrim($private, '/').'/'), strtolower(rtrim($public, '/').'/')),
        ];
    }
}
