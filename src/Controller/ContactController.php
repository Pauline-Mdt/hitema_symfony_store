<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\Model\ContactModel;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(private RequestStack $requestStack, private MailerInterface $mailer)
    {}

    #[Route('/contact', name: 'contact.index')]
    public function index(): Response
    {
        $type = ContactType::class;
        $model = new ContactModel();
        $form = $this->createForm($type, $model);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new TemplatedEmail())
                ->from('store@email.com')
                ->to($model->getEmail())
                ->subject($model->getSubject())
                ->textTemplate('emailing/contact.text.twig')
                ->htmlTemplate('emailing/contact.html.twig')
                ->context([
                   'contact_email' => $model->getEmail(),
                   'contact_subject' => $model->getSubject(),
                   'contact_message' => $model->getMessage(),
                ]);
            $this->mailer->send($email);

            $this->addFlash('message', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('homepage.index');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
