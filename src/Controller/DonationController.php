<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Form\DonationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DonationController extends AbstractController
{
    /**
     * @Route("/donation", name="donation")
     */
    public function index(Request $request): Response
    {
        $donations = $this->getDoctrine()->getRepository(Donation::class)->findAll();
        $don=new Donation();
        $form = $this->createForm(DonationType::class, $don);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $don = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $don->setDate(date("Y-m-d"));
            $manager->persist($don);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre article a bien été créé'
            );
            return $this->redirectToRoute("donation");
        }
        return $this->render('donation/index.html.twig', [
            'controller_name' => 'DonationController',
            'donations' => $donations,
            'form' => $form->createView()
        ]);
    }
}
