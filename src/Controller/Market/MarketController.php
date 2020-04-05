<?php

namespace App\Controller\Market;

use App\Entity\Trade;
use App\Form\Market\TradeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MarketController extends AbstractController
{
    /**
     * @Route("/market", name="app_market")
     */
    public function index()
    {
        return $this->render('market/index.html.twig', [
            'controller_name' => 'MarketController',
        ]);
    }

    /**
     * @Route("/market/creation-trade", name="app_market_create_trade")
     */
    public function openIslandForTrade(Request $request)
    {
        $trade = new Trade();
        $user = $this->getUser();
        $form = $this->createForm(TradeType::class, $trade, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trade->setMember($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($trade);
            $em->flush();

            $this->addFlash(
                'success',
                'l\'opération a bien été crée'
            );
            return $this->redirectToRoute('app_market');
        }

        return $this->render('market/createTrade.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
