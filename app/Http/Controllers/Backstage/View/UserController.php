<?php

namespace App\Http\Controllers\Backstage\View;

use App\Domain\Bot;
use App\Domain\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Response;

class UserController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function export()
    {
        $query = $this->entityManager->createQuery(sprintf("SELECT u FROM %s u WHERE u NOT INSTANCE OF %s", User::class, Bot::class));
        $users = $query->getResult();


        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => sprintf("attachment; filename=user-export-%s.csv", (new \DateTime())->format('Y-m-d@h:i:s')),
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('UserId', 'Username', 'Email', 'Current Balance');

        $callback = function() use ($users, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($users as $user) {
                /** @var User $user */
                $data = [
                    $user->getId(),
                    $user->getName(),
                    $user->getEmail(),
                    $user->getBalance() / 100
                ];
                fputcsv($file, $data);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
}
