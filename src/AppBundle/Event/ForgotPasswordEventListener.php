<?php

namespace AppBundle\Event;
use CoopTilleuls\ForgotPasswordBundle\Event\ForgotPasswordEvent;

class ForgotPasswordEventListener
{
    // ...

    /**
     * @param ForgotPasswordEvent $event
     */
    public function onCreateToken(ForgotPasswordEvent $event)
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();

        $swiftMessage = new \Swift_Message(
            'Reset of your password',
            $this->templating->render(
                'AppBundle:ResetPassword:mail.html.twig',
                [
                    'reset_password_url' => sprintf('http://www.example.com/forgot-password/%s', $passwordToken->getToken()),
                ]
            )
        );

        $swiftMessage->setFrom('no-reply@example.com');
        $swiftMessage->setTo($user->getEmail());
        $swiftMessage->setContentType('text/html');
        if (0 === $this->mailer->send($swiftMessage)) {
            throw new \RuntimeException('Unable to send email');
        }
    }

    /**
     * @param ForgotPasswordEvent $event
     */

    public function onUpdatePassword(ForgotPasswordEvent $event)
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();
        $user->setPlainPassword($event->getPassword());
        $this->userManager->updateUser($user);
    }
}