<?php
namespace App\Http\Controllers\Backstage\View;

use App\Betting\TimeStatus;
use App\Domain\Tournament as TournamentEntity;
use App\Http\Controllers\Controller;
use App\Http\Transformers\App\ApiEventTransformer;
use App\Jobs\Tournaments\CheckForTournamentCompletion;
use App\Models\ApiEvent;
use App\Models\Config;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Tournament\Enums\TournamentState;
use App\Tournament\Events\TournamentUpdate;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as JobDispatcher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;

class TournamentController extends Controller
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index()
    {
        $tournaments = Tournament::paginate(10);

        $numFirstItemPage = $tournaments->firstItem();

        JavaScript::put([
            'apiSports' => '',
            'config' => '',
            'lateRegister' => '',
            'prizePool' => '',
        ]);

        return view('backstage.tournaments.index')
            ->with('tournaments', $tournaments)
            ->with('tournament', null)
            ->with('numFirstItemPage', $numFirstItemPage);
    }

    public function create()
    {
        $config = Config::first();

        JavaScript::put([
            'config' => $config->config,
            'autoEnd' => 1,
            'lateRegister' => 0,
            'prizePool' => 'Auto',
            'prizePoolValue' => 0,
            'playersLimit' => 'Unlimited',
            'minBots' => 0,
            'maxBots' => 0,
            'addBots' => 0,
            'playerBots' => 1,
        ]);

        return view('backstage.tournaments.create')
            ->with('tournaments', null)
            ->with('tournament', null)
            ->with('config', $config)
            ->with('numFirstItemPage', 0);
    }

    public function store(Request $request, Dispatcher $dispatcher)
    {
        $this->validation($request);

        $apiData = $request->ApiData ?? [];

        $registrationDeadlines = $this->calculateRegistrationDeadlines($apiData, (int) $request->late_register, $request->late_register_rule);

        $tournament = new Tournament();
        $tournament->name = $request->name;
        $tournament->players_limit = $request->players_limit;
        $tournament->buy_in = $request->buy_in;
        $tournament->chips = $request->chips;
        $tournament->commission = $request->commission;
        $tournament->late_register = $request->late_register;
        $tournament->late_register_rule = $request->late_register_rule;
        $tournament->prize_pool = $request->prize_pool;

        $state = new TournamentState($request->state);
        $endStates = [TournamentState::CANCELED(), TournamentState::COMPLETED()];
        if ( $state->isOneOf(...$endStates)) {
            $tournament->completed_at = Carbon::now();
        }

        $tournament->state = $request->state;

        $tournament->time_frame = $request->time_frame;
        $tournament->registration_deadline = $registrationDeadlines['registration_deadline'];
        $tournament->late_registration_deadline = $registrationDeadlines['late_registration_deadline'];
        $tournament->bots = $request->bots;
        $tournament->auto_end = $request->auto_end;
        $tournament->save();

        foreach ($apiData as $data) {
            $apiEvent = $this->firstOrCreateApiEvent($data);

            $tournament_event = new TournamentEvent();
            $tournament_event->tournament_id = $tournament->id;
            $tournament_event->api_event_id = $apiEvent->id;
            $tournament_event->save();
        }

        $dispatcher->dispatch(new TournamentUpdate($tournament));

        return 'Data Saved Successfully';
    }

    public function show(Tournament $tournament)
    {
        $selectedEvents = TournamentEvent::with(["apiEvent"])
            ->where("tournament_id", $tournament->id)
            ->get()
            ->map(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->apiEvent);

        $apiSelectedSports = fractal()
            ->collection($selectedEvents, new ApiEventTransformer())
            ->toArray();

        JavaScript::put([
            'apiSelectedSports' => $apiSelectedSports,
            'buyIn' => $tournament->buy_in,
            'chips' => $tournament->chips,
            'commission' => $tournament->commission,
            'config' => '',
            'interval' => $tournament->late_register_rule['interval'] ?? '',
            'lateRegister' => $tournament->late_register,
            'name' => $tournament->name,
            'playersLimit' => $tournament->players_limit,
            'prizePool' => $tournament->prize_pool['type'],
            'prizePoolValue' => $tournament->prize_pool['fixed_value'],
            'state' => $tournament->state,
            'timeFrame' => $tournament->time_frame,
            'value' => $tournament->late_register_rule['value'] ?? '',
            'minBots' => $tournament->bots['min'],
            'maxBots' => $tournament->bots['max'],
            'addBots' => $tournament->bots['add'],
            'playerBots' => $tournament->bots['player'],
            'autoEnd' => $tournament->auto_end,
        ]);

        return view('backstage.tournaments.show')
            ->with('tournaments', null)
            ->with('tournament', $tournament)
            ->with('numFirstItemPage', 0);
    }

    public function edit(Tournament $tournament)
    {
        $selectedEvents = TournamentEvent::with(["apiEvent"])
            ->where("tournament_id", $tournament->id)
            ->get()
            ->map(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->apiEvent);

        $apiSelectedSports = fractal()
            ->collection($selectedEvents, new ApiEventTransformer())
            ->toArray();

        $rule = $tournament->late_register_rule;
        JavaScript::put([
            'name' => $tournament->name,
            'playersLimit' => $tournament->players_limit,
            'config' => '',
            'buyIn' => $tournament->buy_in,
            'chips' => $tournament->chips,
            'commission' => $tournament->commission,
            'lateRegister' => $tournament->late_register,
            'interval' => $rule['interval'] ?? '',
            'value' => $rule['value'] ?? '',
            'prizePool' => $tournament->prize_pool['type'],
            'prizePoolValue' => $tournament->prize_pool['fixed_value'],
            'apiSelectedSports' => $apiSelectedSports,
            'state' => $tournament->state,
            'timeFrame' => $tournament->time_frame,
            'minBots' => $tournament->bots['min'],
            'maxBots' => $tournament->bots['max'],
            'addBots' => $tournament->bots['add'],
            'playerBots' => $tournament->bots['player'],
            'autoEnd' => $tournament->auto_end,
        ]);

        return view('backstage.tournaments.edit')
            ->with('tournaments', null)
            ->with('tournament', $tournament)
            ->with('numFirstItemPage', 0);
    }

    public function update(Request $request, Tournament $tournament, Dispatcher $dispatcher)
    {
        $this->validation($request);

        $apiData = $request->ApiData ?? [];

        $registrationDeadlines = $this->calculateRegistrationDeadlines($apiData, (int) $request->late_register, $request->late_register_rule);

        $tournament->name = $request->name;
        $tournament->players_limit = $request->players_limit;
        $tournament->buy_in = $request->buy_in;
        $tournament->chips = $request->chips;
        $tournament->commission = $request->commission;
        $tournament->late_register = $request->late_register;
        $tournament->late_register_rule = $request->late_register_rule;
        $tournament->prize_pool = $request->prize_pool;

        $state = new TournamentState($request->state);
        $endStates = [TournamentState::CANCELED(), TournamentState::COMPLETED()];

        if (!$tournament->state->isOneOf(...$endStates) && $state->isOneOf(...$endStates)) {
            $tournament->completed_at = Carbon::now();
        }

        $tournament->state = $request->state;

        if ($tournament->state->isOneOf(...$endStates) && $tournament->completed_at === null) {
            $tournament->completed_at = Carbon::now();
        }

        $tournament->time_frame = $request->time_frame;
        $tournament->registration_deadline = $registrationDeadlines['registration_deadline'];
        $tournament->late_registration_deadline = $registrationDeadlines['late_registration_deadline'];
        $tournament->bots = $request->bots;
        $tournament->auto_end = $request->auto_end;
        $tournament->save();

        $apiDataDict = collect($apiData)->mapWithKeys(
            fn(array $data) => [$data['external_id'] => $data],
        );
        $tournamentEvents = TournamentEvent::with(["apiEvent"])
            ->where('tournament_id', $tournament->id)
            ->get();

        // Delete those not existing anymore
        $tournamentEvents
            ->filter(fn(TournamentEvent $event) => !$apiDataDict->has($event->apiEvent->api_id))
            ->each(fn(TournamentEvent $event) => $event->delete());

        // Create new
        foreach ($apiData as $data) {
            $apiEvent = $this->firstOrCreateApiEvent($data);
            TournamentEvent::firstOrCreate([
                'tournament_id' => $tournament->id,
                'api_event_id' => $apiEvent->id,
            ]);
        }

        $dispatcher->dispatch(new TournamentUpdate($tournament));

        return 'Data Updated Successfully';
    }

    public function destroy(Tournament $tournament)
    {
        $tournamentEntity = $this->entityManager->find(TournamentEntity::class, $tournament->id);
        $this->entityManager->remove($tournamentEntity);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function checkForCompletion(Tournament $tournament, JobDispatcher $dispatcher)
    {
        $dispatcher->dispatch(new CheckForTournamentCompletion($tournament->id));
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function validation(Request $request)
    {
        $inputs = [
            'buy_in' => 'required|numeric|min:0',
            'chips' => 'required|min:1',
            'commission' => 'required|numeric|min:0',
            'name' => 'required',
            'players_limit' => 'required',
            'prize_pool.type' => 'required',
            'state' => 'required',
            'time_frame' => 'required',
            'bots.min' => 'required|numeric|min:0',
            'bots.max' => 'required|numeric|min:0',
            'bots.add' => 'required|numeric|min:0',
            'bots.player' => 'required|numeric|min:1',
        ];

        $messages = [
            'players_limit.required' => 'Players limit is required',
            'prize_pool.type.required' => 'Prize pool field is required.',
        ];

        if ($request->players_limit == 'Unlimited') {
            $inputs = array_merge($inputs, [
                'late_register' => 'required',
            ]);
            $messages = array_merge($messages, [
                'late_register.required' => 'Late register is required field',
            ]);
        }

        if ($request->late_register == 1) {
            if (
                $request->late_register_rule['interval'] == 'seconds' ||
                $request->late_register_rule['interval'] == 'minutes'
            ) {
                $inputs = array_merge($inputs, [
                    'late_register_rule.interval' => 'required',
                    'late_register_rule.value' => 'required|numeric|min:1|max:60',
                ]);

                $messages = array_merge($messages, [
                    'late_register_rule.interval.required' => 'Interval is required field.',
                    'late_register_rule.value.required' => 'Interval value is required field.',
                    'late_register_rule.value.numeric' => 'Interval value should be numeric.',
                    'late_register_rule.value.max' => 'Interval value may not be greater than 60.',
                ]);
            } else {
                $inputs = array_merge($inputs, [
                    'late_register_rule.interval' => 'required',
                    'late_register_rule.value' => 'required|numeric|min:1|max:100',
                ]);

                $messages = array_merge($messages, [
                    'late_register_rule.interval.required' => 'Interval is required field.',
                    'late_register_rule.value.required' => 'Interval value is required field.',
                    'late_register_rule.value.numeric' => 'Interval value should be numeric.',
                    'late_register_rule.value.max' => 'Interval value may not be greater than 100.',
                ]);
            }
        }

        if ($request->prize_pool['type'] == 'Fixed') {
            $inputs = array_merge($inputs, [
                'prize_pool.fixed_value' => 'required|numeric|min:0',
            ]);

            $messages = array_merge($messages, [
                'prize_pool.fixed_value.required' => 'Prize pool value is required.',
                'prize_pool.fixed_value.numeric' => 'Prize pool value should be numeric.',
                'prize_pool.fixed_value.min' => 'Prize pool value should be numeric.',
            ]);
        }
        $this->validate($request, $inputs, $messages);
    }

    private function firstOrCreateApiEvent(array $data): ApiEvent
    {
        $apiEvent = ApiEvent::findByApiId($data["external_id"]);

        if (!$apiEvent) {
            $apiEvent = new ApiEvent();
            $apiEvent->api_id = $data["external_id"];
            $apiEvent->time_status = TimeStatus::NOT_STARTED();
            $apiEvent->sport_id = $data["sport_id"];
            $apiEvent->team_away = $data["team_away"];
            $apiEvent->team_home = $data["team_home"];
            $apiEvent->pitcher_away = $data["pitcher_away"];
            $apiEvent->pitcher_home = $data["pitcher_home"];
            $apiEvent->provider = $data["provider"];
            $apiEvent->starts_at = $data["starts_at"];
            $apiEvent->save();
        }

        return $apiEvent;
    }

    private function calculateRegistrationDeadlines(array $apiData, int $lateRegistrationAllowed, ?array $lateRegistrationRule): array
    {
        if (!isset($apiData[0]['starts_at'])) {
            return ['registration_deadline' => null, 'late_registration_deadline' => null];
        }

        $registrationDeadline = Carbon::create($apiData[0]['starts_at']);
        $lateRegistrationDeadline = null;

        foreach ($apiData as $event) {
            $startTime = Carbon::create($event['starts_at']);
            if ($startTime < $registrationDeadline) {
                $registrationDeadline = $startTime;
            }
        }

        if ($lateRegistrationAllowed) {
            $lateRegistrationDeadline = clone $registrationDeadline;
            $lateRegistrationDeadline = $lateRegistrationDeadline->modify(sprintf('+ %d %s', $lateRegistrationRule['value'], $lateRegistrationRule['interval']));
        }

        return ['registration_deadline' => $registrationDeadline, 'late_registration_deadline' => $lateRegistrationDeadline];
    }
}
