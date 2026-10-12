<?php

namespace App\Support;

class MailFailure
{
    public static function hint(\Throwable $exception): string
    {
        // Inspect locally, but never print SMTP exception text/debug transcripts (they can contain secrets).
        $message = strtolower($exception->getMessage());
        if (str_contains($message, '535') || str_contains($message, '534') || str_contains($message, 'authenticate')) {
            return 'The SMTP service rejected authentication. Verify the username and SMTP/app password with your provider.';
        }
        if (str_contains($message, 'certificate') || str_contains($message, 'crypto') || str_contains($message, 'starttls') || str_contains($message, 'tls')) {
            return 'TLS negotiation failed. Check the SMTP scheme/port and the PHP certificate bundle; keep certificate verification enabled.';
        }
        if (str_contains($message, 'connect') || str_contains($message, 'timed out') || str_contains($message, 'getaddrinfo')) {
            return 'The SMTP service could not be reached. Check host, port, firewall and network access.';
        }
        return 'Check the provider account and mail configuration; no exception transcript is displayed.';
    }
}
