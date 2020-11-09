<?php

namespace Unit\Http\Transformers\App;

use App\Domain\ApiEvent;
use App\Domain\Tournament;
use App\Domain\TournamentPlayer;
use App\Domain\User;
use App\Http\Transformers\App\DoctrineTournamentPlayerTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\ApiEventFactory;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers App\Http\Transformers\App\DoctrineTournamentPlayerTransformer
 * @uses App\Domain\ApiEvent
 * @uses App\Domain\Tournament
 * @uses App\Domain\TournamentPlayer
 * @uses App\Domain\User
 */
class TournamentPlayerTransformerTest extends TestCase
{
    public function testTransform()
    {
        $apiEvent = ApiEventFactory::create();
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->addEvent($apiEvent);
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament->registerPlayer($user);
        $tournamentPlayer = $user->getTournamentPlayer($tournament);
        FactoryAbstract::setProperty($tournamentPlayer, 'id', 1);

        $expected = [
            'id' => '1',
            'name' => 'test',
            'tournamentId' => 1
        ];
        $tpt = new DoctrineTournamentPlayerTransformer();
        $result = $tpt->transform($tournamentPlayer);
        self::assertEquals($expected, $result);
    }
}
