<?php

namespace App\Http\Controllers\Backstage;

use Illuminate\Http\Response;
use JavaScript;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Backstage\Tournament;
use App\Models\Backstage\TournamentEvent;
use App\Models\Backstage\TournamentSport;
use App\Models\Backstage\ApiEvent;
use App\Models\Backstage\Config;
use Illuminate\Support\Facades\DB;

class TournamentsController extends Controller
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
            'prizes' => '',
        ]);

        return view('backstage.tournaments.index')
            ->with('tournaments', $tournaments)
            ->with('tournament', null)
            ->with('numFirstItemPage', $numFirstItemPage);
    }

    public function create(Request $request)
    {
        $config = Config::first();

        JavaScript::put([
            'config' => $config->config,
            'lateRegister' => 0,
            'prizePool' => 'Auto',
            'prizePoolValue' => 0,
            'prizes' => 'Auto',
            'playersLimit' => 'Unlimited',
        ]);

        return view('backstage.tournaments.create')
            ->with('tournaments', null)
            ->with('tournament', null)
            ->with('config', $config)
            ->with('numFirstItemPage', 0);
    }

    public function store(Request $request)
    {
        $this->validation($request);

        $api_data = $request->ApiData;

        foreach ($api_data as &$key) {
            unset($key['Odds']);
        }

        $tournament = new Tournament;
        $tournament->name = $request->name;
        $tournament->type = count(array_unique($request->type))>1?'Multiple':'Single';
        $tournament->players_limit = $request->players_limit;
        $tournament->buy_in = $request->buy_in;
        $tournament->chips = $request->chips;
        $tournament->commission = $request->commission;
        $tournament->late_register = $request->late_register;
        $tournament->late_register_rule = $request->late_register_rule;
        $tournament->prize_pool = $request->prize_pool;
        $tournament->prizes = $request->prizes;
        $tournament->state = $request->state;
        $tournament->save();

        foreach(array_unique($request->type) as $sport_id) {
            TournamentSport::firstOrCreate(
                ['tournament_id' => $tournament->id, 'sport_id' => $sport_id],
                ['sport_id' => $sport_id]
            );
        }

        foreach ($api_data as $data) {
            ApiEvent::firstOrCreate(
                ['api_id' => $data['ID']],
                ['api_data' => $data]
            );

            $tournament_event = new TournamentEvent;
            $tournament_event->tournament_id = $tournament->id;
            $tournament_event->api_event_id = ApiEvent::where('api_id', $data['ID'])->value('id');
            $tournament_event->save();
        }

        return 'Data Saved Successfully';
    }

    public function show(Tournament $tournament)
    {
        $selectedEvents = [];
        $api_event_id = DB::table('tournaments_events')->where('tournament_id', $tournament->id)->get('api_event_id');

        foreach ($api_event_id as $value) {
            /** @var ApiEvent $apiEvent */
            $apiEvent = ApiEvent::find($value->api_event_id);
            array_push($selectedEvents, $apiEvent->api_data);
        }

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
            'prizes' => $tournament->prizes['type'],
            'apiSelectedSports' => $selectedEvents,
            'state' => $tournament->state,
        ]);

        return view('backstage.tournaments.show')
            ->with('tournaments', null)
            ->with('tournament', $tournament)
            ->with('numFirstItemPage', 0);
    }

    public function edit(Tournament $tournament)
    {
        $selectedEvents = [];
        $api_event_id = DB::table('tournaments_events')->where('tournament_id', $tournament->id)->get('api_event_id');

        foreach ($api_event_id as $value) {
            /** @var ApiEvent $apiEvent */
            $apiEvent = ApiEvent::find($value->api_event_id);
            array_push($selectedEvents, $apiEvent->api_data);
        }

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
            'prizes' => $tournament->prizes['type'],
            'apiSelectedSports' => $selectedEvents,
            'state' => $tournament->state,
        ]);

        return view('backstage.tournaments.edit')
            ->with('tournaments', null)
            ->with('tournament', $tournament)
            ->with('numFirstItemPage', 0);
    }

    public function update(Request $request, Tournament $tournament)
    {
        $this->validation($request);

        $api_data = $request->ApiData;

        foreach ($api_data as &$key) {
            if(array_key_exists('Odds', $key)) {
                unset($key['Odds']);
            }
        }

        $tournament->name = $request->name;
        $tournament->type = count(array_unique($request->type))>1 ?'Multiple':'Single';
        $tournament->players_limit = $request->players_limit;
        $tournament->buy_in = $request->buy_in;
        $tournament->chips = $request->chips;
        $tournament->commission = $request->commission;
        $tournament->late_register = $request->late_register;
        $tournament->late_register_rule = $request->late_register_rule;
        $tournament->prize_pool = $request->prize_pool;
        $tournament->prizes = $request->prizes;
        $tournament->state = $request->state;
        $tournament->save();

        TournamentSport::where('tournament_id', $tournament->id)->delete();

        foreach(array_unique($request->type) as $sport_id) {
            TournamentSport::firstOrCreate(
                ['tournament_id' => $tournament->id, 'sport_id' => $sport_id],
                ['sport_id' => $sport_id]
            );
        }

        TournamentEvent::where('tournament_id', $tournament->id)->delete();

        foreach ($api_data as $data) {
            $apiEvent = ApiEvent::firstOrCreate(
                ['api_id' => $data['ID']],
                ['api_data' => $data]
            );

            TournamentEvent::firstOrCreate(
                ['tournament_id' => $tournament->id, 'api_event_id' => $apiEvent->id]
            );
        }

        return 'Data Updated Successfully';
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        TournamentEvent::where('tournament_id', $tournament->id)->delete();
        TournamentSport::where('tournament_id', $tournament->id)->delete();
        return new Response(Response::HTTP_NO_CONTENT);
    }

    private function validation(Request $request)
    {
        $inputs = [
            'name' => 'required',
            'players_limit' => 'required',
            'buy_in' => 'required|numeric|min:1',
            'chips' => 'required|min:1',
            'commission' => 'required|min:1',
            'prize_pool.type' => 'required',
            'state' => 'required',
            'prizes.type' => 'required',
        ];

        $messages = [
            'players_limit.required' => 'Players limit is required',
            'prize_pool.type.required' => 'Prize pool field is required.',
            'prizes.type.required' => 'Please select valid prize.'
        ];

        if ($request->players_limit == 'Unlimited') {
            $inputs = array_merge($inputs,[
                'late_register' => 'required',
            ]);
            $messages = array_merge($messages, [
                'late_register.required' => 'Late register is required field',
            ]);
        }

        if ($request->late_register == 1) {
            if ($request->late_register_rule['interval'] == 'seconds'
                ||$request->late_register_rule['interval'] == 'minutes') {
                $inputs = array_merge($inputs,[
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
                $inputs = array_merge($inputs,[
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

    public function getEvents(Request $request)
    {
        $availableSports = $request->request->get("SelectSport", []);

        $appKey = "3b279a7d-7d95-4eda-89cb-3c1f96093fc6";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://jsonodds.com/api/odds/all");
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'x-api-key:' . $appKey,
        ));

        $res = curl_exec($ch);
        $response = json_decode($res, true);

        return collect($response)
            ->filter(function (array $item) use ($availableSports) {
                return !$availableSports || in_array($item["Sport"], $availableSports);
            })
            ->values()
            ->all();
    }
}
