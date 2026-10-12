<?php

namespace App\Console\Commands;

use App\Support\MailConfiguration;
use App\Support\OAuthConfiguration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class CheckIntegrations extends Command
{
    protected $signature = 'integrations:check {--smtp : Connect/authenticate to SMTP without sending email}';
    protected $description = 'Check mail and Google/GitHub configuration without displaying secrets';

    public function handle(): int
    {
        $failed = false;
        $mailProblems = MailConfiguration::problems();
        $this->info('Mail: '.($mailProblems ? 'needs configuration' : 'configuration present'));
        foreach ($mailProblems as $problem) $this->warn($problem);
        $failed = $mailProblems !== [];
        foreach (OAuthConfiguration::PROVIDERS as $provider) {
            $problems = OAuthConfiguration::problems($provider);
            $this->info(ucfirst($provider).': '.($problems ? 'needs configuration' : 'configuration present'));
            foreach ($problems as $problem) $this->warn($problem);
            if (!$problems) $this->line('Callback: '.config("services.{$provider}.redirect"));
            $failed = $failed || $problems !== [];
        }
        if ($this->option('smtp')) {
            if ($mailProblems || config('mail.default') !== 'smtp') {
                $this->error('SMTP connection check requires a configured SMTP mailer.');
                return self::FAILURE;
            }
            try {
                $transport = Mail::mailer()->getSymfonyTransport();
                if (!$transport instanceof EsmtpTransport) throw new \RuntimeException;
                $transport->start();
                $transport->stop();
                $this->info('SMTP connection and authentication succeeded; no message was sent.');
            } catch (\Throwable $exception) {
                $this->error('SMTP connection/authentication failed. Check credentials, TLS certificates and network access; secrets are not displayed.');
                $this->warn(\App\Support\MailFailure::hint($exception));
                return self::FAILURE;
            }
        }
        $this->line('Configuration presence does not prove inbox delivery or an authorized OAuth callback. Complete the browser/inbox checks in docs/P0_04_EMAIL_OAUTH.md.');
        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
