<?php
namespace Tests\Feature\Jobs;

use App\Betting\SportEventResult;
use App\Betting\TimeStatus;
use App\Jobs\SyncMatchesResults;
use App\Models\ApiEvent;
use App\Models\PendingOdd;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Models\TournamentPlayer;
use App\Models\User;
use App\Services\TournamentPlayerService;
use App\Tournament\BetStatus;
use App\Tournament\PendingOddType;
use App\Tournament\StraightBetService;
use App\User\MeUpdate;
use Illuminate\Support\Facades\Event;
use Tests\Utils\Concerns\BettingProviderConcern;
use Tests\Utils\TestCase;

class SyncMatchesResultsTest extends TestCase
{
    use BettingProviderConcern;

    private StraightBetService $straightBetService;
    private Tournament $tournament;
    private TournamentEvent $tournamentEvent;
    private TournamentPlayer $player;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockBettingProvider();

        $this->straightBetService = $this->app->make(StraightBetService::class);

        /** @var TournamentPlayerService $tournamentPlayerService */
        $tournamentPlayerService = $this->app->make(TournamentPlayerService::class);

        $apiEvent = factory(ApiEvent::class)->create([
            "api_id" => "event1",
        ]);
        $this->tournament = factory(Tournament::class)->create([
            "buy_in" => 100,
            "commission" => 20,
            "chips" => 500,
        ]);
        $this->tournamentEvent = factory(TournamentEvent::class)->create([
            "tournament_id" => $this->tournament->getKey(),
            "api_event_id" => $apiEvent->getKey(),
        ]);
        $this->user = factory(User::class)->create([
            "balance" => 1000,
        ]);
        $this->player = $tournamentPlayerService->register($this->tournament, $this->user);
    }

    /** @test */
    public function updates_tournaments_events()
    {
        // given
        $this->bettingProvider
            ->shouldReceive("getResults")
            ->andReturn([new SportEventResult("event1", TimeStatus::ENDED(), 5, 4)]);

        $tournamentBets = $this->straightBetService->bet($this->tournament, $this->user, [
            new PendingOdd(PendingOddType::MONEY_LINE_HOME(), $this->tournamentEvent, 400, 200),
        ]);

        // when
        $this->app->call([new SyncMatchesResults(), "handle"]);

        // then
        $tournamentBet = $tournamentBets[0]->fresh();
        $this->player->refresh();

        $this->assertSameEnum(BetStatus::WIN(), $tournamentBet->status);
        $this->assertSame(1300, $this->player->chips);
        Event::assertDispatched(MeUpdate::class, 1);
    }

    /** @test */
    public function marks_bet_failed()
    {
        // given
        $this->bettingProvider
            ->shouldReceive("getResults")
            ->andReturn([new SportEventResult("event1", TimeStatus::ENDED(), 5, 4)]);

        $tournamentBets = $this->straightBetService->bet($this->tournament, $this->user, [
            new PendingOdd(PendingOddType::MONEY_LINE_AWAY(), $this->tournamentEvent, 400, 200),
        ]);

        // when
        $this->app->call([new SyncMatchesResults(), "handle"]);

        // then
        $tournamentBet = $tournamentBets[0]->fresh();
        $this->player->refresh();

        $this->assertSameEnum(BetStatus::LOSS(), $tournamentBet->status);
        $this->assertSame(100, $this->player->chips);
        Event::assertDispatched(MeUpdate::class, 1);
    }
}
