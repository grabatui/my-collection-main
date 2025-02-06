<?php

declare(strict_types=1);

namespace App\Domain\Service\Mail;

use App\Domain\Service\Mail\Dto\HtmlTemplate;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailSender
{
    public function __construct(
        private readonly string $defaultFrom,
        private readonly string $currentDomain,
        private readonly MailerInterface $mailer,
        private readonly Environment $environment,
    ) {
    }

    /**
     * @param string|string[] $to
     */
    public function send(
        string|array $to,
        string $subject,
        ?string $textBody = null,
        ?string $htmlBody = null,
        ?HtmlTemplate $htmlTemplate = null,
    ): void {
        $email = new Email();
        $email->from($this->defaultFrom);
        $email->to(...(array) $to);
        $email->subject($subject);

        if ($textBody) {
            $email->text($textBody);
        } elseif ($htmlBody) {
            $email->html($htmlBody);
        } elseif ($htmlTemplate) {
            $email->html(
                $this->environment->render(
                    name: $htmlTemplate->template,
                    context: array_merge($this->getDefaultHtmlVariables(), $htmlTemplate->variables),
                ),
            );
        } else {
            throw new \RuntimeException('Text of email is required');
        }

        $this->mailer->send($email);
    }

    /**
     * @return array<string, mixed>
     */
    private function getDefaultHtmlVariables(): array
    {
        return [
            'currentDomain' => $this->currentDomain,
        ];
    }
}
