<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Legend Sports</title>

    <link rel="icon" href="img/favicon.png" type="image/png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/solid.css" integrity="sha384-doPVn+s3XZuxfJLS7K1E+sUl25XMZtTVb3O46RyV3JDU2ehfc0Aks4z0ufFpA2WC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/brands.css" integrity="sha384-tft2+pObMD7rYFMZlLUziw/8QrQeKHU4GYYvA5jVaggC74ZrYdTASheA2vckPcX5" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/fontawesome.css" integrity="sha384-+pqJl+lfXqeZZHwVRNTbv2+eicpo+1TR/AEzHYYDKfAits/WRK21xLOwzOxZzJEZ" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <div id="app" class="container-fluid">
        <nav id="menuFrm" class="row">
            <div class="col-4">
                <a id="brandFrm" href="#">
                    {{-- <img id="logo" src="" class="d-inline-block align-top" alt=""> --}}
                    <div id="logoTextFrm" class="d-inline-blockx align-top">
                        <div id="logoText" class="">LS</div>
                    </div>
                    <span id="text">Legend Sports</span>
                </a>
            </div>

            <div v-if="isLogin" class="offset-5 col-3">
                <div id="userMenuFrm">
                    <div id="imgFrm">
                        <div id="img">
                            <i class="icon fas fa-user"></i>
                        </div>
                    </div>

                    <div id="titleFrm">
                        <div id="title">
                            Michael Jarrod
                            <br>
                            <span class="balance">Bal: $3,000</span>
                        </div>
                    </div>

                    <i id="bar" class="icon fas fa-bars"></i>
                </div>
            </div>

            <div v-else id="signFrm" class="offset-6 col-2">
                <button id="btn-sign-up" type="button" class="button float-rightx">Sign up</button>
                <button id="btn-sign-in" type="button" class="button float-rightx">Sign in</button>
            </div>
        </nav>

        <section class="row">
            <div class="col tabsRowFrm">
                <div id="tabsFrm">
                    <div class="tabFrm">
                        <button type="button"
                            id="home"
                            class="tab"
                            :class="{ active: home.show }"
                            >
                            <i class="icon fas fa-home"></i>
                            Home
                        </button>
                        <span class="separator">|</span>
                    </div>

                    <template v-for="(tab, i) in tabs">
                        <div class="tabFrm">
                            <button type="button"
                                class="tab"
                                :class="{ active: (i == 1) && (home.show == false) }"
                            >@{{ tab }}</button>

                            <span class="separator">|</span>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        <section v-if="home.show"
            id="homeTabFrm"
            class="tabTournament row"
            >
            <div class="col">
                <section id="filtersFrm" class="row">
                    <div id="typeFrm" class="col-1">
                        <label for="type" class="control-title">Type</label>

                        <select id="type"
                            class="form-control control-input"
                        ></select>
                    </div>

                    <div id="sportFrm" class="col-3">
                        <label for="sport" class="control-title">Sport</label>

                        <select id="sport"
                            class="form-control control-input"
                        ></select>
                    </div>

                    <div id="buyinFrm" class="col-1">
                        <label for="buyin" class="control-title">Buy-In</label>

                        <select id="buyin"
                            class="form-control control-input"
                        ></select>
                    </div>

                    <div id="timeFrameFrm" class="col-1">
                        <label for="timeFrame" class="control-title">Time Frame</label>

                        <select id="timeFrame"
                            class="form-control control-input"
                        ></select>
                    </div>

                    <div id="upcomingFrm" class="col-2">
                        <label for="upcoming" class="control-title">Show upcoming only</label>

                        <input type="text"
                            id="upcoming"
                            class="form-control control-input"
                        >
                    </div>

                    <div id="searchFrm" class="offset-1 col-3">
                        <label for="search" class="control-title">Search</label>

                        <input type="text"
                            id="search"
                            class="form-control control-input"
                        >
                    </div>
                </section>

                <section id="tournamentsFrm" class="row">
                    <div class="col-9">
                        <div class="tableFrm">
                            <table id="tournamentsTbl" class="table">
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

                    <div class="col-3">
                        <div id="infoTournamentFrm">
                            <div id="titleBarFrm">
                                <div id="imgFrm">
                                    <div id="img">
                                        <i class="icon fas fa-football-ball"></i>
                                    </div>
                                </div>

                                <div id="titleFrm">
                                    <div id="title">Weekend NFL</div>
                                </div>

                                <div id="statusFrm">
                                    <div id="status">Completed</div>
                                </div>
                            </div>

                            <div id="infoFrm">
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

                                <div class="row">
                                    <div class="col">
                                        <div class="title"># Players</div>
                                        <div class="value">254</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="title">Sports</div>
                                        <div class="value">NFL</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div v-if="home.info.games.show" class="title">Games</div>

                                        <div v-else class="title">Rank</div>
                                    </div>
                                </div>
                            </div>

                            <div class="tablesFrm">
                                <table v-if="home.info.games.show"
                                    id="gamesTbl"
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
                                                :class="{ selected: i == 3 }"
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

                                <table v-else
                                    id="rankTbl"
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
                                                    <div class="imgFrm">
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

        <section v-if="home.show == false"
            class="tabTournament row"
            >
            <section class="col-3">
                <section class="section bets">
                    <div class="titleBarFrm">
                        <span class="title">Balance: $3,000</span>
                    </div>

                    <div class="tabsFrm">
                        <div class="tabFrm">
                            <button type="button"
                                class="tab"
                                :class="{ active: tournament.betting.pending.show }"
                            >Pending</button>
                            <span class="separator">|</span>
                        </div>

                        <div class="tabFrm">
                            <button type="button"
                                class="tab"
                                :class="{ active: tournament.betting.pending.show == false  }"
                            >History</button>
                            <span class="separator">|</span>
                        </div>

                        <div class="tabFrm">
                            <button type="button"
                                class="tab"
                            >Straight</button>
                            <span class="separator">|</span>
                        </div>

                        <div class="tabFrm">
                            <button type="button"
                                class="tab"
                            >Parlay</button>
                        </div>
                    </div>

                    <div v-if="tournament.betting.pending.show" class="pendingFrm">
                        <div class="gameFrm">
                            <div class="dataFrm">
                                <div class="typeBet">Straight</div>

                                <div class="text">[datetime]</div>
                                <div class="text matchFrm">
                                    <div class="text team">Miami Dolphins</div>
                                    <div class="text score">0</div>
                                    <div class="text vs">@</div>
                                    <div class="text team">Washington Nationals</div>
                                    <div class="text score">0</div>
                                </div>
                                <div class="text">[team/+odds/result]</div>
                            </div>

                            <div class="placedBetFrm">
                                <div>Bet: $ 0</div>

                                <div>Win: $ 0</div>
                            </div>
                        </div>

                        <div class="gameFrm">
                            <div class="dataFrm">
                                <div class="typeBet">Parlay</div>

                                <div class="text">[datetime]</div>
                                <div class="text matchFrm">
                                    <div class="text team">Team C</div>
                                    <div class="text score">0</div>
                                    <div class="text vs">@</div>
                                    <div class="text team">Team D</div>
                                    <div class="text score">0</div>
                                </div>
                                <div class="text">[team/+odds/result]</div>
                            </div>

                            <div class="dataFrm">
                                <div class="text">[datetime]</div>
                                <div class="text matchFrm">
                                    <div class="text team">Team C</div>
                                    <div class="text score">0</div>
                                    <div class="text vs">@</div>
                                    <div class="text team">Team D</div>
                                    <div class="text score">0</div>
                                </div>
                                <div class="text">[team/+odds/result]</div>
                            </div>

                            <div class="placedBetFrm">
                                <div>Bet: $0</div>

                                <div>Win: $0</div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="historyFrm">
                        <div class="gameFrm">
                            <div class="dataFrm">
                                <div class="typeBet">Straight</div>

                                <div class="text">[datetime]</div>
                                <div class="text matchFrm">
                                    <div class="text team">Miami Dolphins</div>
                                    <div class="text score">0</div>
                                    <div class="text vs">@</div>
                                    <div class="text team">Washington Nationals</div>
                                    <div class="text score">0</div>
                                </div>
                                <div class="text">[team/+odds/result]</div>
                            </div>

                            <div class="placedBetFrm">
                                <div>Bet: $ 0</div>

                                <div>Win: $ 0</div>
                            </div>

                            <div class="result lost"><i class="icon fas fa-frown"></i> YOU LOST!</div>
                        </div>

                        <div class="gameFrm">
                            <div class="dataFrm">
                                <div class="typeBet">Parlay</div>

                                <div class="text">[datetime]</div>
                                <div class="text matchFrm">
                                    <div class="text team">Team C</div>
                                    <div class="text score">0</div>
                                    <div class="text vs">@</div>
                                    <div class="text team">Team D</div>
                                    <div class="text score">0</div>
                                </div>
                                <div class="text">[team/+odds/result]</div>
                            </div>

                            <div class="dataFrm">
                                <div class="text">[datetime]</div>
                                <div class="text matchFrm">
                                    <div class="text team">Team C</div>
                                    <div class="text score">0</div>
                                    <div class="text vs">@</div>
                                    <div class="text team">Team D</div>
                                    <div class="text score">0</div>
                                </div>
                                <div class="text">[team/+odds/result]</div>
                            </div>

                            <div class="placedBetFrm">
                                <div>Bet: $ 0</div>

                                <div>Win: $ 0</div>
                            </div>

                            <div class="result win"><i class="icon fas fa-laugh-beam"></i> YOU WON!</div>
                        </div>
                    </div>
                </section>
            </section>

            <section class="col-6">
                <div class="section matches">
                    <div class="tabsFrm">
                        <div class="tabFrm">
                            <button type="button"
                                class="tab active"
                            >NFL</button>
                            <span class="separator">|</span>
                        </div>

                        <div class="tabFrm">
                            <button type="button"
                                class="tab"
                            >AAF</button>
                            <span class="separator">|</span>
                        </div>
                    </div>

                    <div class="actionsFrm">
                        <button type="button" class="button gameLine">Game Line</button>

                        <button type="button" class="button firstHalf checked">1st half</button>

                        <button type="button" class="button update">Update</button>
                    </div>

                    <div class="tablesFrm">
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

            <section class="col-3">
                <div class="section info">
                    <div class="titleBarFrm">
                        <div class="imgFrm">
                            <div class="img">
                                <i class="icon fas fa-football-ball"></i>
                            </div>
                        </div>

                        <div class="titleFrm">
                            <div class="title">Weekend NFL</div>
                        </div>

                        <div class="statusFrm">
                            <div class="status">Completed</div>
                        </div>
                    </div>

                    <div class="infoFrm">
                        <div class="row">
                            <div class="col-6">
                                <div class="title">Start time</div>
                                <div class="value">Finished</div>
                            </div>

                            <div class="col-6">
                                <div class="title">In</div>
                                <div class="value">3 hours</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="title"># Players</div>
                                <div class="value">254</div>
                            </div>

                            <div class="col-4">
                                <div class="title">Buy-In</div>
                                <div class="value">50k</div>
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
                                        <div class="imgFrm">
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

        <footer id="footerFrm" class="row">
            <div id="promoFrm" class="col-4">
                <div id="promoImage"></div>
            </div>

            <div id="linksFrm" class="offset-4 col-3">
                <div class="row">
                    <div id="aboutFrm" class="col-4">
                        <div class="linkTitle">About<span class="separator">|</span></div>

                        <div class="linkFrm">
                            <a class="link" href="#">About us</a>
                        </div>

                        <div class="linkFrm">
                            <a class="link" href="#">Privacy</a>
                        </div>

                        <div class="linkFrm">
                            <a class="link" href="#">Terms of use</a>
                        </div>
                    </div>

                    <div id="supportFrm" class="col-4">
                        <div class="linkTitle">Support<span class="separator">|</span></div>

                        <div class="linkFrm">
                            <a class="link" href="#">Contact us</a>
                        </div>

                        <div class="linkFrm addMultiline">
                            <a class="link" href="#">Forgot password</a>
                        </div>
                    </div>

                    <div id="supportFrm" class="col-4">
                        <div class="linkTitle">Follow us</div>

                        <div class="linkFrm">
                            <a class="link" href="#"><i class="icon fab fa-facebook-square"></i>Facebook</a>
                        </div>

                        <div class="linkFrm">
                            <a class="link" href="#"><i class="icon fab fa-twitter-square"></i>Twitter</a>
                        </div>

                        <div class="linkFrm">
                            <a class="link" href="#"><i class="icon fab fa-instagram"></i>Instagram</a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="toogleFrm" class="col-1">
                <button type="button" class="btn btn-secondary float-right"><i class="fas fa-angle-up"></i></button>
            </div>
        </footer>
    </div>

    <script type="text/javascript" language="javascript" src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('js/home.js') }}"></script>
</body>
</html>
