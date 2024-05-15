<?php

namespace App\Service;
 
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EmailSender
{
    private $mailer;
    private $requestStack;
    private $urlHelper;
    private $twig; // Property to hold Twig\Environment service

    public function __construct(MailerInterface $mailer, RequestStack $requestStack, UrlGeneratorInterface $urlGenerator, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->requestStack = $requestStack;
        $this->urlHelper = $urlGenerator;
        $this->twig = $twig; // Inject Twig\Environment service
    }

    public function sendEmail(string $recipientEmail, string $subscriptionUrl): void
    {
        // Create a Transport object
        $request = $this->requestStack->getCurrentRequest();
        $transport = Transport::fromDsn('smtp://nadazaghdoud.5@gmail.com:kczd%20vyvt%20glul%20hzkg@smtp.gmail.com:587');
 
        // Create a Mailer object
        $mailer = new Mailer($transport);
 
        // Create an Email object
        $email = (new Email())
            ->from('nadazaghdoud.5@gmail.com')
            ->to($recipientEmail)
            ->subject('Votre Abonnement à E-Flex Gym')
            ->html(
                $this->renderEmailTemplate('abonnement/email.html.twig', [
                    'subscriptionUrl' => $subscriptionUrl,// You can pass additional context here if needed
                ])
            );
 
        // Send the email
        $mailer->send($email);
    }

    private function renderEmailTemplate(string $template, array $context): string
    {
        return $this->twig->render($template, $context);
    }

    private function getAbsoluteLogoUrl($request, $logoPath)
    {
        return $request->getSchemeAndHttpHost() . $this->urlHelper->getAbsoluteUrl($logoPath);
    }
}

