<?php

namespace App\Controller\Market;

use App\Entity\Trade;
use App\Form\Market\TradeType;
use App\Repository\TradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MarketController
 * @package App\Controller\Market
 *
 * @Route("/market", name="market_")
 */
class MarketController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('market/index.html.twig', [
            'controller_name' => 'MarketController',
        ]);
    }

    /**
     * @Route("/creation-trade", name="create_trade")
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
            return $this->redirectToRoute('market_index');
        }

        return $this->render('market/createTrade.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trade-liste", name="trade_list")
     */
    public function tradeList(Request $request, TradeRepository $tradeRepository)
    {
        $trades = $tradeRepository->findBy(['status' => 1]);

        return $this->render('market/tradeList.html.twig', [
            'trades' => $trades
        ]);
    }

    /**
     * @Route("/trade-liste/{id}", name="trade_list_info")
     */
    public function tradeListInfo($id, Request $request, TradeRepository $tradeRepository)
    {
        $trade = $tradeRepository->findOneById($id);

        return $this->render('market/tradeListInfo.html.twig', [
            'trade' => $trade
        ]);
    }
}
