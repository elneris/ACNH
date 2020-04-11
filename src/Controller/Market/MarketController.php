<?php

namespace App\Controller\Market;

use App\Entity\Trade;
use App\Entity\TradeMemberParticipation;
use App\Form\Market\TradeType;
use App\Repository\TradeMemberParticipationRepository;
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
    public function tradeListInfo($id, Request $request, TradeRepository $tradeRepository, TradeMemberParticipationRepository $participationRepository)
    {
        $trade = $tradeRepository->findOneById($id);

        return $this->render('market/tradeListInfo.html.twig', [
            'trade' => $trade
        ]);
    }

    /**
     * @Route("/trade-edit/{id}", name="trade_edit")
     */
    public function tradeEdit($id, Request $request, TradeRepository $tradeRepository)
    {
        $trade = $tradeRepository->findOneById($id);

        $form = $this->createForm(TradeType::class, $trade, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                'l\'opération a bien été modifié'
            );
            return $this->redirectToRoute('market_trade_list_info', ['id' => $id]);
        }

        return $this->render('market/editTrade.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trade-sub/{id}", name="trade_sub")
     */
    public function tradeSub($id, TradeRepository $tradeRepository, TradeMemberParticipationRepository $participationRepository)
    {
        $trade = $tradeRepository->findOneById($id);
        $user = $this->getUser();
        $alreadyFollow = $participationRepository->findOneBy(['member' => $user, 'trade' => $trade]);
        $em = $this->getDoctrine()->getManager();

        if (!$alreadyFollow) {
            if ($user !== $trade->getMember()) {
                $memberParticipation = new TradeMemberParticipation();
                $memberParticipation->setMember($user);
                $memberParticipation->setTrade($trade);
                $memberParticipation->setCreatedAt(new \DateTime());
                $memberParticipation->setStatus(0);

                $em->persist($memberParticipation);

                $trade->addTradeMemberParticipation($memberParticipation);
                $em->flush();
                $this->addFlash('success', 'Inscription bien prise en compte');
            } else {
                $this->addFlash('danger', 'Vous ne pouvez pas participer ! Vous êtes le créateur ...');
            }
        } else {
            $this->addFlash('danger', 'Vous ne pouvez pas participer ! Vous êtes déjà inscrit ...');
        }

        return $this->redirectToRoute('market_trade_list_info', ['id' => $id]);
    }

    /**
     * @Route("/trade-unsub/{id}", name="trade_unsub")
     */
    public function tradeUnsub($id, TradeRepository $tradeRepository, TradeMemberParticipationRepository $participationRepository)
    {
        $trade = $tradeRepository->findOneById($id);
        $user = $this->getUser();
        $alreadyFollow = $participationRepository->findOneBy(['member' => $user, 'trade' => $trade]);
        $em = $this->getDoctrine()->getManager();

        if ($alreadyFollow) {
            $em->remove($alreadyFollow);
            $em->flush();
            $this->addFlash('success', 'Désinscription bien prise en compte');
        } else {
            $this->addFlash('danger', 'Une erreur est intervenue, veuillez nous excusez du désagrément');
        }

        return $this->redirectToRoute('market_trade_list_info', ['id' => $id]);
    }
}
