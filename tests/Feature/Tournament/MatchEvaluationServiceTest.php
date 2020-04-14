<?php
namespace Tests\Feature\Tournament;

use App\Betting\TimeStatus;
use App\Models\ApiEvent;
use App\Models\PendingOdd;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Models\TournamentPlayer;
use App\Models\User;
use App\Services\TournamentPlayerService;
use App\Tournament\BetStatus;
use App\Tournament\MatchEvaluationService;
use App\Tournament\ParlayBetService;
use App\Tournament\PendingOddType;
use App\Tournament\StraightBetService;
use Tests\Utils\TestCase;

class MatchEvaluationServiceTest extends TestCase
{
    private MatchEvaluationService $matchEvaluationService;
    private StraightBetService $straightBetService;
    private ParlayBetService $parlayBetService;
    private Tournament $tournament;
    private TournamentEvent $tournamentEvent;
    private ApiEvent $apiEvent;
    private TournamentPlayer $player;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matchEvaluationService = $this->app->make(MatchEvaluationService::class);
        $this->straightBetService = $this->app->make(StraightBetService::class);
        $this->parlayBetService = $this->app->make(ParlayBetService::class);
        $tournamentPlayerService = $this->app->make(TournamentPlayerService::class);

        $this->apiEvent = factory(ApiEvent::class)->create([
            "time_status" => TimeStatus::NOT_STARTED(),
        ]);
        $this->tournament = factory(Tournament::class)->create([
            "buy_in" => 100,
            "commission" => 20,
            "chips" => 500,
        ]);
        $this->tournamentEvent = factory(TournamentEvent::class)->create([
            "api_event_id" => $this->apiEvent->getKey(),
            "tournament_id" => $this->tournament->getKey(),
        ]);
        $this->user = factory(User::class)->create([
            "balance" => 1000,
        ]);
        $this->player = $tournamentPlayerService->register($this->tournament, $this->user);
    }

    /** @test */
    public function assign_chips_for_winning_a_straight_bet()
    {
        // given
        $tournamentBets = $this->straightBetService->bet($this->tournament, $this->user, [
            new PendingOdd(PendingOddType::MONEY_LINE_AWAY(), $this->tournamentEvent, 400, 140),
        ]);

        $this->apiEvent->score_away = 10;
        $this->apiEvent->score_home = 9;
        $this->apiEvent->time_status = TimeStatus::ENDED();
        $this->apiEvent->save();

        // when
        $this->matchEvaluationService->evaluateBets($this->apiEvent);

        // then
        $this->player->refresh();
        $tournamentBet = $tournamentBets[0]->fresh();

        $this->assertSameEnum(BetStatus::WIN(), $tournamentBet->status);
        $this->assertSame(1060, $this->player->chips);
    }

    /** @test */
    public function assign_chips_for_winning_a_parlay_bet()
    {
        // given
        /** @var ApiEvent $anotherApiEvent */
        $anotherApiEvent = factory(ApiEvent::class)->create([
            "time_status" => TimeStatus::NOT_STARTED(),
        ]);
        $anotherTournamentEvent = factory(TournamentEvent::class)->create([
            "api_event_id" => $anotherApiEvent->getKey(),
            "tournament_id" => $this->tournament->getKey(),
        ]);

        $tournamentBet = $this->parlayBetService->bet(
            $this->tournament,
            $this->user,
            [
                new PendingOdd(
                    PendingOddType::MONEY_LINE_AWAY(),
                    $this->tournamentEvent,
                    null,
                    140,
                ),
                new PendingOdd(
                    PendingOddType::MONEY_LINE_HOME(),
                    $anotherTournamentEvent,
                    null,
                    -120,
                ),
            ],
            500,
        );

        $this->apiEvent->score_away = 10;
        $this->apiEvent->score_home = 9;
        $this->apiEvent->time_status = TimeStatus::ENDED();
        $this->apiEvent->save();

        $anotherApiEvent->score_away = 2;
        $anotherApiEvent->score_home = 4;
        $anotherApiEvent->time_status = TimeStatus::ENDED();
        $anotherApiEvent->save();

        // when
        $this->matchEvaluationService->evaluateBets($this->apiEvent);
        $this->matchEvaluationService->evaluateBets($anotherApiEvent);

        // then
        $this->player->refresh();
        $tournamentBet->refresh();

        $this->assertSameEnum(BetStatus::WIN(), $tournamentBet->status);
        $this->assertSame(2200, $this->player->chips);
    }

    /** @test */
    public function revert_bet_chips_in_case_of_moneyline_draw()
    {
        // given
        $tournamentBets = $this->straightBetService->bet($this->tournament, $this->user, [
            new PendingOdd(PendingOddType::MONEY_LINE_AWAY(), $this->tournamentEvent, 400, 140),
        ]);

        $this->apiEvent->score_away = 10;
        $this->apiEvent->score_home = 10;
        $this->apiEvent->time_status = TimeStatus::ENDED();
        $this->apiEvent->save();

        // when
        $this->matchEvaluationService->evaluateBets($this->apiEvent);

        // then
        $this->player->refresh();
        $tournamentBet = $tournamentBets[0]->fresh();

        $this->assertSameEnum(BetStatus::PUSH(), $tournamentBet->status);
        $this->assertSame(500, $this->player->chips);
    }

    /** @test */
    public function reduce_parlay_bet_in_case_of_push()
    {
        // given
        /** @var ApiEvent $anotherApiEvent */
        $anotherApiEvent = factory(ApiEvent::class)->create([
            "time_status" => TimeStatus::NOT_STARTED(),
        ]);
        $anotherTournamentEvent = factory(TournamentEvent::class)->create([
            "api_event_id" => $anotherApiEvent->getKey(),
            "tournament_id" => $this->tournament->getKey(),
        ]);

        $tournamentBet = $this->parlayBetService->bet(
            $this->tournament,
            $this->user,
            [
                new PendingOdd(
                    PendingOddType::MONEY_LINE_AWAY(),
                    $this->tournamentEvent,
                    null,
                    140,
                ),
                new PendingOdd(
                    PendingOddType::MONEY_LINE_HOME(),
                    $anotherTournamentEvent,
                    null,
                    -180,
                ),
            ],
            500,
        );

        $this->apiEvent->score_away = 10;
        $this->apiEvent->score_home = 8;
        $this->apiEvent->time_status = TimeStatus::ENDED();
        $this->apiEvent->save();

        $anotherApiEvent->score_away = 4;
        $anotherApiEvent->score_home = 4;
        $anotherApiEvent->time_status = TimeStatus::ENDED();
        $anotherApiEvent->save();

        // when
        [$betEvent] = $this->matchEvaluationService->evaluateBets($this->apiEvent);
        [$anotherBetEvent] = $this->matchEvaluationService->evaluateBets($anotherApiEvent);

        // then
        $this->player->refresh();
        $tournamentBet->refresh();

        $this->assertSameEnum(BetStatus::WIN(), $betEvent->status);
        $this->assertSameEnum(BetStatus::PUSH(), $anotherBetEvent->status);
        $this->assertSameEnum(BetStatus::WIN(), $tournamentBet->status);
        $this->assertSame(1200, $this->player->chips);
    }
}
