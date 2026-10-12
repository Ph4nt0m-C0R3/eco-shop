<?php

namespace App\Support;

class MailConfiguration
{
    public static function problems(): array
    {
        $mailer = config('mail.default');
        if (!in_array($mailer, ['smtp', 'ses', 'ses-v2', 'postmark', 'resend', 'sendmail'], true)) {
            return ['Configure a real mail transport; array/log or a fallback to log cannot prove delivery.'];
        }
        $problems = [];
        $sender = (string) config('mail.from.address');
        $domain = strtolower((string) substr(strrchr($sender, '@') ?: '', 1));
        if (!filter_var($sender, FILTER_VALIDATE_EMAIL) || str_contains($sender, '__REPLACE_')
            || in_array($domain, ['example.com', 'example.net', 'example.org']) || preg_match('/\.(test|invalid|example)$/', $domain)) {
            $problems[] = 'MAIL_FROM_ADDRESS must be an actual sender allowed by your mail service.';
        }
        if ($mailer === 'smtp') {
            $smtp = config('mail.mailers.smtp');
            // MAIL_URL is parsed by Laravel; diagnostics use the same resolved values.
            try { $smtp = (new \Illuminate\Support\ConfigurationUrlParser)->parseConfiguration($smtp); }
            catch (\Throwable) { return ['SMTP configuration URL is invalid.']; }
            $host = (string) ($smtp['host'] ?? '');
            if (!$host || in_array($host, ['localhost', '127.0.0.1', '::1']) || str_contains($host, '__REPLACE_') || preg_match('/\.(test|invalid|example)$/', $host)) $problems[] = 'MAIL_HOST must identify your real SMTP service.';
            foreach (['username', 'password'] as $key) {
                if (empty($smtp[$key]) || str_contains($smtp[$key], '__REPLACE_')) $problems[] = 'SMTP '.str_replace('_', ' ', $key).' is missing.';
            }
            if ((int) ($smtp['port'] ?? 0) < 1 || (int) ($smtp['port'] ?? 0) > 65535) $problems[] = 'MAIL_PORT must be a valid SMTP port.';
            if (!($smtp['require_tls'] ?? false)) $problems[] = 'MAIL_REQUIRE_TLS must be true for real SMTP delivery.';
        }
        return $problems;
    }
}
