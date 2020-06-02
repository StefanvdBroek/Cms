<?php

namespace Opifer\FormBundle\Mailer;

use Opifer\FormBundle\Model\FormInterface;
use Opifer\FormBundle\Model\PostInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

class Mailer
{
    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var EngineInterface */
    protected $templating;

    /** @var string */
    protected $sender;

    /** @var TranslatorInterface */
    protected $translator;

    /** @var RequestStack */
    protected $requestStack;

    /**
     * Mailer constructor.
     *
     * @param $sender
     */
    public function __construct(TranslatorInterface $translator, RequestStack $requestStack, EngineInterface $templating, \Swift_Mailer $mailer, $sender)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function sendNotificationMail(FormInterface $form, PostInterface $post)
    {
        $body = $this->templating->render('OpiferFormBundle:Email:notification.html.twig', ['post' => $post]);
        $subject = $form->getName().' (post '.$post->getId().')';

        $message = $this->createMessage($form->getNotificationEmail(), $subject, $body);

        $this->send($message);
    }

    /**
     * @param string $recipient
     */
    public function sendConfirmationMail(FormInterface $form, PostInterface $post, $recipient)
    {
        if ($form->getLocale()) {
            $this->requestStack->getCurrentRequest()->setLocale($form->getLocale()->getLocale());
            $this->translator->setLocale($form->getLocale()->getLocale());
        }

        $body = $this->templating->render('OpiferFormBundle:Email:confirmation.html.twig', ['post' => $post]);

        $message = $this->createMessage($recipient, $form->getName(), $body);

        $this->send($message);
    }

    /**
     * @param string $recipient
     * @param string $subject
     * @param string $body
     *
     * @return \Swift_Message
     */
    public function createMessage($recipient, $subject, $body)
    {
        $recipients = explode(',', str_replace(' ', '', $recipient));

        return (new \Swift_Message($subject))
            ->setSender($this->sender)
            ->setFrom($this->sender)
            ->setTo($recipients)
            ->setBody($body, 'text/html');
    }

    /**
     * @return int
     */
    protected function send(\Swift_Message $message)
    {
        return $this->mailer->send($message);
    }
}
