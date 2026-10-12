<?php

namespace App\Console\Commands;

use App\Mail\DeliveryTestMail;
use App\Support\MailConfiguration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailDelivery extends Command
{
    protected $signature = 'mail:test {recipient : An inbox you own or have permission to send a test to}';
    protected $description = 'Send one non-sensitive delivery test through the configured real mailer';

    public function handle(): int
    {
        $recipient = (string) $this->argument('recipient');
        $domain = strtolower((string) substr(strrchr($recipient, '@') ?: '', 1));
        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL) || in_array($domain, ['example.com', 'example.net', 'example.org'])
            || preg_match('/\.(test|invalid|example)$/', $domain)) {
            $this->error('Provide an actual recipient inbox you control.');
            return self::FAILURE;
        }
        if ($problems = MailConfiguration::problems()) {
            foreach ($problems as $problem) $this->error($problem);
            return self::FAILURE;
        }
        try { Mail::to($recipient)->send(new DeliveryTestMail); }
        catch (\Throwable $exception) {
            $this->error('The delivery service rejected the test or could not be reached. Check SMTP/API credentials and network access.');
            $this->warn(\App\Support\MailFailure::hint($exception));
            return self::FAILURE;
        }
        $this->info('The mail transport accepted the test. Confirm receipt in the inbox or spam folder.');
        return self::SUCCESS;
    }
}
