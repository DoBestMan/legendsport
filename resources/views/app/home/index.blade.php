@extends('app.layout')

@section('homeActive','active')
    
@section('HTML-css')
    <link rel="stylesheet" href="{{ asset('app/css/home.css') }}">
@endsection

@section('HTML-js')
    <script type="text/javascript" language="javascript" src="{{ asset('app/js/home.js') }}"></script>
@endsection

@section('HTML-main')
    <section name="tab-content-home"
        id="tab-home-frm"
        class="tab-content-frm row"
        >
        <div class="col">
            <section id="filters-frm" class="row">
                <div name="type" class="col-1">
                    <label for="type" class="control-title">Type</label>

                    <select id="type"
                        class="form-control control-input"
                    >
                        <option value=""> </option>
                        <option value="299">55</option>
                        <option value="">56</option>
                    </select>
                </div>

                <div name="sport" class="col-3">
                    <label for="sport" class="control-title">Sport</label>

                    <select id="sport"
                        class="form-control control-input"
                    ></select>
                </div>

                <div name="buy-in" class="col-1">
                    <label for="buyin" class="control-title">Buy-In</label>

                    <select id="buyin"
                        class="form-control control-input"
                    ></select>
                </div>

                <div name="time-frame" class="col-1">
                    <label for="time-frame" class="control-title">Time Frame</label>

                    <select id="time-frame"
                        class="form-control control-input"
                    ></select>
                </div>

                <div name="upcoming" class="col-2">
                    <label for="upcoming" class="control-title">Show upcoming only</label>

                    <input type="text"
                        id="upcoming"
                        class="form-control control-input"
                    >
                </div>

                <div name="search" class="offset-1 col-3">
                    <label for="search" class="control-title">Search</label>

                    <input type="text"
                        id="search"
                        class="form-control control-input"
                    >
                </div>
            </section>

            <section id="tournaments-frm" class="row">
                <div name="table" class="col-9">
                    <div id="table-frm">
                        <table id="tournaments" class="table">
                            <thead class="thead">
                                <tr class="tr">
                                    <th id="col-start" class="th" scope="col">Start</th>
                                    <th id="col-sports" class="th" scope="col">Sports</th>
                                    <th id="col-buy-in" class="th" scope="col">Buy-In</th>
                                    <th id="col-name" class="th" scope="col">Tournament name</th>
                                    <th id="col-time-frame" class="th" scope="col">Time Frame</th>
                                    <th id="col-status" class="th" scope="col">Status</th>
                                    <th id="col-enrolled" class="th" scope="col">Enrolled</th>
                                    <th id="col-players" class="th" scope="col">Players</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                <template v-for="tournament in home.tournaments">
                                    <tr class="tr"
                                        :class="{ selected: tournament.selected == true }"
                                        >
                                        <td class="td">@{{ tournament.starts }}</td>
                                        <td class="td">@{{ tournament.sports }}</td>
                                        <td class="td">@{{ tournament.buy_in }}</td>
                                        <td class="td">@{{ tournament.name }}</td>
                                        <td class="td">@{{ tournament.time_frame }}</td>
                                        <td class="td">@{{ tournament.status }}</td>
                                        <td class="td">@{{ tournament.enrolled }}</td>
                                        <td class="td">@{{ tournament.players }}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div name="info" class="col-3">
                    <div id="info-frm">
                        <div id="title-bar-frm">
                            <div id="img-frm">
                                <div id="img">
                                    <i class="icon fas fa-football-ball"></i>
                                </div>
                            </div>

                            <div id="title-frm">
                                <div id="title">Weekend NFL</div>
                            </div>

                            <div id="status-frm">
                                <div id="status">Completed</div>
                            </div>
                        </div>

                        <div id="data-frm">
                            <div class="row">
                                <div class="col-6">
                                    <div class="title">Start time</div>
                                    <div class="value">Finished</div>
                                </div>

                                <div class="col-6">
                                    <div class="title">In</div>
                                    <div class="value"></div>
                                </div>
                            </div>

                            <div name="players" class="row">
                                <div class="col">
                                    <div class="title"># Players</div>
                                    <div class="value">254</div>
                                </div>
                            </div>

                            <div name="sports" class="row">
                                <div class="col">
                                    <div class="title">Sports</div>
                                    <div class="value">NFL</div>
                                </div>
                            </div>

                            <div name="title-table" class="row">
                                <div class="col">
                                    <div v-if="home.info.games.show" class="title">Games</div>

                                    <div v-else class="title">Rank</div>
                                </div>
                            </div>
                        </div>

                        <div class="tables-frm">
                            <table name="games" v-if="home.info.games.show"
                                id="games"
                                class="table"
                                >
                                <thead class="thead">
                                    <tr class="tr">
                                        <th id="col-time" class="th" scope="col">Time</th>
                                        <th id="col-sport" class="th" scope="col">Sport</th>
                                        <th id="col-game" class="th" scope="col">Game</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                    <template v-for="i in 5">
                                        <tr class="tr"
                                            :class="{ selected: i == 1 }"
                                            >
                                            <td class="td col-time">Oct, 22</td>
                                            <td class="td col-sport">NFL</td>
                                            <td class="td col-game">
                                                <div class="team">Miami Dolphins</div>
                                                <div class="score">0</div>
                                                <div class="vs">@</div>
                                                <div class="team">Washington Nationals</div>
                                                <div class="score">0</div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                            <table name="rank" v-else
                                id="rank"
                                class="table"
                                >
                                <thead class="thead">
                                    <tr class="tr">
                                        <th id="col-position" class="th" scope="col">#</th>
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
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
