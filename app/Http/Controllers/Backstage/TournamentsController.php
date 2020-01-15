<?php
namespace App\Http\Controllers\Backstage;

use JavaScript;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Backstage\Tournaments;
use App\Models\Backstage\Config;

class TournamentsController extends Controller
{
    public function ejemplo()
    {

    }

    public function index()
    {
        $tournaments = Tournaments::paginate(10);

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
        $data = json_decode(file_get_contents("php://input"), TRUE);

        $appKey = "3b279a7d-7d95-4eda-89cb-3c1f96093fc6";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://jsonodds.com/api/odds/$request->SelectSport");
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'x-api-key:' . $appKey
        ));

        $res = curl_exec($ch);
        $response = json_decode($res);

        $config = Config::first();
        
        JavaScript::put([
            'apiSports' => $response,
            'config' => $config->config,
            'lateRegister' => 0,
            'prizePool' => 'Auto',
            'prizes' => 'Auto',
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

        $tournament = new Tournaments;
        $tournament->name = $request->name;
        $tournament->type = $request->type;
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

        return redirect()->route('tournaments.index');
    }

    public function show(Tournaments $tournament)
    {
        JavaScript::put([
            'buy_in' => $tournament->buy_in,
            'config' => $config->config,
            'chips' => '',
            'commision' => '',
            'lateRegister' => $tournament->late_register,
        ]);

        return view('backstage.tournaments.show')
            ->with('tournaments', null)
            ->with('tournament', $tournament)
            ->with('numFirstItemPage', 0);
    }

    public function edit(Tournaments $tournament)
    {
        JavaScript::put([
            'playersLimit' => $tournament->players_limit,
            'config' => '',
            'buy_in' => $tournament->buy_in,
            'chips' => $tournament->chips,
            'commission' => $tournament->commission,
            'lateRegister' => $tournament->late_register,
            'prizePool' => $tournament->prize_pool['type'],
            'prizes' => $tournament->prizes['type'],
        ]);

        return view('backstage.tournaments.edit')
            ->with('tournaments', null)
            ->with('tournament', $tournament)
            ->with('numFirstItemPage', 0);
    }

    public function update(Request $request, Tournaments $tournament)
    {
        $this->validation($request);

        $tournament->name = $request->name;
        $tournament->type = $request->type;
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

        return redirect()->route('tournaments.index');
    }
    
    public function destroy(Tournaments $tournament)
    {
        $tournament->delete();
        
        return redirect()->route('tournaments.index');
    }
    
    private function validation(Request $request)
    {
        $request->validate([
            'name'=> 'required',
            'type'=> 'required',
            'players_limit'=> 'required',
            'buy_in'=> 'required',
            'chips'=> 'required',
            'commission'=> 'required',
            'late_register'=> 'required',
            'state'=> 'required',
            'prizes'=> 'required',
        ]);
    }

    public function ajaxCall(){
        return response()->json(['posts' => Post::all()]);
   }
}