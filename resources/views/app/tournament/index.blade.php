@extends('app.layout')

@section('tournamentActive','active')

@section('tournamentActive1','istabselected(i)')
@section('tournamentActive2','showtab(i)')

@section('HTML-css')
    <link rel="stylesheet" href="{{ mix('/app/css/tournament.css') }}">
@endsection

@section('HTML-js')
    <script type="text/javascript" language="javascript" src="{{ mix('/app/js/tournament.js') }}"></script>
@endsection

@section('HTML-main')
    <section name="tab-content-any-tournament"
        class="tab-content-frm tab-tournament-frm row"
        >
        <section name="betting-section" class="col-3">
            <section class="section bets">
                <div class="title-bar-frm">
                    <span class="title">@{{ balance }}</span>
                </div>

                <div class="tabs-frm">
                    <div name="pending-tab" class="tab-frm">
                        <button type="button"
                            class="tab"
                            :class="{ active: tournament.betting.pending.show }"
                            @click="pending"
                        >Pending</button>
                        <span class="separator">|</span>
                    </div>

                    <div name="history-tab" class="tab-frm">
                        <button type="button"
                            class="tab"
                            :class="{ active: tournament.betting.pending.show2 }"
                            @click="history"
                        >History</button>
                        <span class="separator">|</span>
                    </div>

                    <div name="straight-tab" class="tab-frm">
                        <button type="button"
                            class="tab"
                            :class="{ active: tournament.betting.pending.show3 }"
                            @click="straight"
                        >Straight</button>
                        <span class="separator">|</span>
                    </div>

                    <div name="parlay-tab" class="tab-frm">
                        <button type="button"
                            class="tab"
                            :class="{ active: tournament.betting.pending.show4 }"
                            @click="parlay"
                        >Parlay</button>
                    </div>
                </div>

                <div name="tab-content-pending" v-if="tournament.betting.pending.show"
                    class="tab-content-frm"
                    >
                    <div class="event-frm">
                        <div class="data-frm">
                            <div class="type-bet">Straight</div>

                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Miami Dolphins</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Washington Nationals</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="bet-frm">
                            <div>Bet: $ 0</div>

                            <div>Win: $ 0</div>
                        </div>
                    </div>

                    <div class="event-frm">
                        <div class="data-frm">
                            <div class="type-bet">Parlay</div>

                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Team C</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Team D</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="data-frm">
                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Team C</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Team D</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="bet-frm">
                            <div>Bet: $0</div>

                            <div>Win: $0</div>
                        </div>
                    </div>
                </div>

                <div name="tab-content-history" v-if="tournament.betting.pending.show2"
                    class="tab-content-frm"
                    >
                    <div class="event-frm">
                        <div class="data-frm">
                            <div class="type-bet">Straight</div>

                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Miami Dolphins</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Washington Nationals</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="bet-frm">
                            <div>Bet: $ 0</div>

                            <div>Win: $ 0</div>
                        </div>

                        <div class="result lost"><i class="icon fas fa-frown"></i> YOU LOST!</div>
                    </div>

                    <div class="event-frm">
                        <div class="data-frm">
                            <div class="type-bet">Parlay</div>

                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Team C</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Team D</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="data-frm">
                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Team C</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Team D</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="bet-frm">
                            <div>Bet: $ 0</div>

                            <div>Win: $ 0</div>
                        </div>

                        <div class="result win"><i class="icon fas fa-laugh-beam"></i> YOU WON!</div>
                    </div>
                </div>
            </section>
        </section>

        <section name="events-section" class="col-6">
            <div class="section matches">
                <div class="tabs-frm">
                    <div class="tab-frm">
                        <button type="button"
                            class="tab active"
                        >NFL</button>

                        <span class="separator">|</span>
                    </div>

                    <div class="tab-frm">
                        <button type="button"
                            class="tab"
                        >AAF</button>

                        <span class="separator">|</span>
                    </div>
                </div>

                <div class="actions-frm">
                    <button type="button" class="button game-line">Game Line</button>

                    <button type="button" class="button game-first-half checked">1st half</button>

                    <button type="button" class="button update">Update</button>
                </div>

                <div class="tables-frm">
                    <table class="match table">
                        <thead class="thead">
                            <tr class="tr">
                                <th class="th col-datetime" scope="col">Oct, 30 at 08:00 ET</th>
                                <th class="th col-money" scope="col">Money line</th>
                                <th class="th col-spread" scope="col">Spread</th>
                                <th class="th col-total" scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <tr class="tr">
                                <td class="td col-datetime">Buffalo Bills</td>

                                <td class="td col-money">
                                    <button type="button" class="button">
                                        -10<br>100
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button checked">
                                        -10<br>100
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button">
                                        -10<br>100
                                    </button>
                                </td>
                            </tr>

                            <tr class="tr">
                                <td class="td col-datetime">Miami Dolphins</td>

                                <td class="td col-money">
                                    <button type="button" class="button checked">
                                        -20<br>200
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button">
                                        -20<br>200
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button">
                                        -20<br>200
                                    </button>
                                </td>
                            </tr>

                            <tr class="tr">
                                <td class="td col-datetime">New England Patriots</td>

                                <td class="td col-money">
                                    <button type="button" class="button">
                                        -10<br>100
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button">
                                        -10<br>100
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button checked">
                                        -10<br>100
                                    </button>
                                </td>
                            </tr>

                            <tr class="tr">
                                <td class="td col-datetime">New York Jets</td>

                                <td class="td col-money">
                                    <button type="button" class="button">
                                        -20<br>200
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button">
                                        -20<br>200
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button checked">
                                        -20<br>200
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="match table">
                        <thead class="thead">
                            <tr class="tr">
                                <th class="th col-datetime" scope="col">Oct, 30 at 09:00 ET</th>
                                <th class="th col-money" scope="col">Money line</th>
                                <th class="th col-spread" scope="col">Spread</th>
                                <th class="th col-total" scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <tr class="tr">
                                <td class="td col-datetime">Baltimore Ravens</td>

                                <td class="td col-money">
                                    <button type="button" class="button">
                                        -10<br>100
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button checked">
                                        -10<br>100
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button">
                                        -10<br>100
                                    </button>
                                </td>
                            </tr>

                            <tr class="tr">
                                <td class="td col-datetime">Cincinnati Bengals</td>

                                <td class="td col-money">
                                    <button type="button" class="button">
                                        -20<br>200
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button">
                                        -20<br>200
                                    </button>
                                </td>

                                <td class="td col-spread">
                                    <button type="button" class="button checked">
                                        -20<br>200
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section name="info-section" class="col-3">
            <div class="section info">
                <div class="title-bar-frm">
                    <div class="img-frm">
                        <div class="img">
                            <i class="icon fas fa-football-ball"></i>
                        </div>
                    </div>

                    <div class="title-frm">
                        <div class="title">@{{ title }}</div>
                    </div>

                    <div class="status-frm">
                        <div class="status">@{{ status }}</div>
                    </div>
                </div>

                <div class="tournament-frm">
                    <div class="row">
                        <div class="col-6">
                            <div class="title">Start time</div>
                            <div class="value">Finished</div>
                        </div>

                        <div class="col-6">
                            <div class="title">In</div>
                            <div class="value">@{{ hours }} hours</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="title"># Players</div>
                            <div class="value">@{{ players }}</div>
                        </div>

                        <div class="col-4">
                            <div class="title">Buy-In</div>
                            <div class="value">@{{ buy }}k</div>
                        </div>

                        <div class="col-4">
                            <div class="title">Prize pool</div>
                            <div class="value">$1,000</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="title">Sports</div>
                            <div class="value">NFL</div>
                        </div>
                    </div>
                </div>

                <table class="awards table"
                    >
                    <thead class="thead">
                        <tr class="tr">
                            <th class="col-position" class="th" scope="col">Position</th>
                            <th class="col-prize" class="th" scope="col">Prize</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        <template v-for="i in 3">
                            <tr class="tr">
                                <td class="td col-position">@{{ i }}</td>
                                <td class="td col-prize">$@{{ 900 / i }}</td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <table class="rank table">
                    <thead class="thead">
                        <tr class="tr">
                            <th id="col-position" class="th" scope="col">Rank</th>
                            <th id="col-player" class="th" scope="col">Players</th>
                            <th id="col-price" class="th" scope="col">Price</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        <template v-for="i in 5">
                            <tr class="tr"
                                :class="{ selected: i == 3 }"
                                >
                                <td class="td col-position">@{{ i }}</td>
                                <td class="td col-player">
                                    <div class="img-frm">
                                        <i class="icon fas fa-user-circle"></i>

                                        <div class="img">
                                        </div>
                                    </div>
                                    Player name
                                </td>
                                <td class="td">1,000</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
