<?php
namespace App\Http\Controllers\Backstage\View;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\SportEventTransformer;
use App\Models\ApiEvent;
use App\Models\Config;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Tournament\Events\TournamentUpdate;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;

class TournamentController extends Controller
{
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
            'lateRegister' => 0,
            'prizePool' => 'Auto',
            'prizePoolValue' => 0,
            'playersLimit' => 'Unlimited',
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

        $tournament = new Tournament();
        $tournament->name = $request->name;
        $tournament->players_limit = $request->players_limit;
        $tournament->buy_in = $request->buy_in;
        $tournament->chips = $request->chips;
        $tournament->commission = $request->commission;
        $tournament->late_register = $request->late_register;
        $tournament->late_register_rule = $request->late_register_rule;
        $tournament->prize_pool = $request->prize_pool;
        $tournament->state = $request->state;
        $tournament->time_frame = $request->time_frame;
        $tournament->save();

        foreach ($apiData as $data) {
            ApiEvent::firstOrCreate(['api_id' => $data['external_id']], ['api_data' => $data]);

            $tournament_event = new TournamentEvent();
            $tournament_event->tournament_id = $tournament->id;
            $tournament_event->api_event_id = ApiEvent::where(
                'api_id',
                $data['external_id'],
            )->value('id');
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
            ->map(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->apiEvent->api_data);

        $apiSelectedSports = fractal()
            ->collection($selectedEvents, new SportEventTransformer())
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
            ->map(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->apiEvent->api_data);

        $apiSelectedSports = fractal()
            ->collection($selectedEvents, new SportEventTransformer())
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

        $tournament->name = $request->name;
        $tournament->players_limit = $request->players_limit;
        $tournament->buy_in = $request->buy_in;
        $tournament->chips = $request->chips;
        $tournament->commission = $request->commission;
        $tournament->late_register = $request->late_register;
        $tournament->late_register_rule = $request->late_register_rule;
        $tournament->prize_pool = $request->prize_pool;
        $tournament->state = $request->state;
        $tournament->time_frame = $request->time_frame;
        $tournament->save();

        $apiDataDict = collect($apiData)->mapWithKeys(
            fn(array $data) => [$data['external_id'] => $data],
        );
        $tournamentEvents = TournamentEvent::with(["apiEvent"])
            ->where('tournament_id', $tournament->id)
            ->get();

        // Delete those not existing anymore
        $tournamentEvents
            ->filter(
                fn(TournamentEvent $tournamentEvent) => !$apiDataDict->has(
                    $tournamentEvent->apiEvent->api_id,
                ),
            )
            ->each(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->delete());

        // Create new
        foreach ($apiData as $data) {
            $apiEvent = ApiEvent::firstOrCreate(
                ['api_id' => $data['external_id']],
                ['api_data' => $data],
            );

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
        $tournament->events()->delete();
        $tournament->delete();
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function validation(Request $request)
    {
        $inputs = [
            'buy_in' => 'required|numeric|min:1',
            'chips' => 'required|min:1',
            'commission' => 'required|min:1',
            'name' => 'required',
            'players_limit' => 'required',
            'prize_pool.type' => 'required',
            'state' => 'required',
            'time_frame' => 'required',
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
}
