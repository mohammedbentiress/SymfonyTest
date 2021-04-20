<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use SendGrid\Mail\Mail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Twig\Environment;

class EmailVerifier
{
    private $verifyEmailHelper;
    private $mailer;
    private $entityManager;
    private $twig;
    private $session;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager, Environment $twig, SessionInterface $session)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
        $this->twig = $twig;
        $this->session = $session;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, Mail $email): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail()
        );

//        $context = $email->getContext();
//        dump($context);
//        $context['signedUrl'] = $signatureComponents->getSignedUrl();
//        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
//        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();
//
//        $email->context($context);

        $sendgrid = new \SendGrid('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');
        $email->addContent('text/html', $this->twig->render('registration/confirmation_email.html.twig', [
            'signedUrl' => $signatureComponents->getSignedUrl(),
            'expiresAtMessageKey' => $signatureComponents->getExpirationMessageKey(),
            'expiresAtMessageData' => $signatureComponents->getExpirationMessageData(),
        ]));
        if ($sendgrid->send($email)) {
            $this->session->getFlashBag()->add('info', 'A verivication mail was sent to you, please check your email');
        }

        //$this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
