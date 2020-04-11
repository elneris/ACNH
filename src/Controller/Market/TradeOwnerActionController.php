<?php


namespace App\Controller\Market;


use App\Repository\TradeMemberParticipationRepository;
use App\Repository\TradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TradeOwnerActionController
 * @package App\Controller\Market
 *
 * @Route("/trade/owner", name="trade_owner_")
 */
class TradeOwnerActionController extends AbstractController
{
    /**
     * @Route("/send-dodocode", name="send_dodocode")
     */
    public function sendDodoCode()
    {

    }

    /**
     * @Route("/active/{trade}/{memberParticipation}", name="status_active")
     */
    public function statutActive($trade, $memberParticipation, TradeRepository $tradeRepository, TradeMemberParticipationRepository $memberParticipationRepository)
    {
        $user = $this->getUser();
        $trade = $tradeRepository->findOneById($trade);
        $memberParticipation = $memberParticipationRepository->findOneById($memberParticipation);

        if ($user === $trade->getMember()) {
            $memberParticipation->setStatus(1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Mis à jour du status passé a active');
        } else {
            $this->addFlash('danger', 'Une erreur est survenu');
        }

        return $this->redirectToRoute('market_trade_list_info', ['id' => $trade->getId()]);
    }

    /**
     * @Route("/inactive/{trade}/{memberParticipation}", name="status_inactive")
     */
    public function statusInactive($trade, $memberParticipation, TradeRepository $tradeRepository, TradeMemberParticipationRepository $memberParticipationRepository)
    {
        $user = $this->getUser();
        $trade = $tradeRepository->findOneById($trade);
        $memberParticipation = $memberParticipationRepository->findOneById($memberParticipation);

        if ($user === $trade->getMember()) {
            $memberParticipation->setStatus(0);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Mis à jour du status passé a inactive');
        } else {
            $this->addFlash('danger', 'Une erreur est survenu');
        }

        return $this->redirectToRoute('market_trade_list_info', ['id' => $trade->getId()]);
    }
}