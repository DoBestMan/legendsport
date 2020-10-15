<?php

namespace App\Http\Controllers\Backstage\View;

use App\Domain\Withdrawal;
use App\Http\Controllers\Controller;
use App\Mail\WithdrawalProcessed;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Mail;

class WithdrawalController extends Controller
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function pending()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('processed', false))
            ->orderBy(['id' => 'ASC']);


        $repository = $this->entityManager->getRepository(Withdrawal::class);
        $withdrawals = $repository->matching($criteria);

        return view('backstage.withdrawal.pending')
            ->with('withdrawals', $withdrawals);
    }

    public function process($id)
    {
        /** @var Withdrawal $entity */
        $entity = $this->entityManager->getRepository(Withdrawal::class)->find($id);

        if (!($entity instanceof Withdrawal)) {
            return false;
        }

        if ($entity->isProcessed()) {
            return false;
        }

        $entity->process();
        $this->entityManager->flush();

        $user = $entity->getUser();
        Mail::to($user->getEmail())->send(
            new WithdrawalProcessed('btc', $user->getFullname(), ['btcAddress' => $entity->getBtcAddress(), 'amount' => $entity->getAmount()])
        );

        return true;
    }

    public function processed()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('processed', false))
            ->orderBy(['id' => 'ASC']);


        $repository = $this->entityManager->getRepository(Withdrawal::class);
        $withdrawals = $repository->matching($criteria);

        return view('backstage.withdrawal.processed')
            ->with('withdrawals', $withdrawals);
    }
}
