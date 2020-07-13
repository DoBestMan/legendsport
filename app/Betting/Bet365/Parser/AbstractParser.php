<?php

namespace App\Betting\Bet365\Parser;

use App\Betting\Bet365\Parser\Exception\PathNotFound;
use App\Betting\SportEventOdd;
use Decimal\Decimal;

class AbstractParser
{
    protected string $moneyLineTeamField;
    protected string $spreadTeamField;
    protected string $totalsTypeField;
    protected array $moneyLinePath;
    protected array $spreadPath;
    protected array $totalsPath;

    protected array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function parseMainLines(array $apiResult, string $homeTeamName, string $awayTeamName)
    {
        try {
            $moneyLine = $this->extractOddsGroup($apiResult, $this->moneyLinePath);
            [$moneyLineHome, $moneyLineAway] = $this->extractMoneyLine(
                $moneyLine,
                $homeTeamName,
                $awayTeamName
            );
        } catch (PathNotFound $e) {
            $moneyLineHome = $moneyLineAway = null;
            $this->errors[] = ['Money line not found', ['path' => $this->moneyLinePath, 'availableOdds' => $this->getAvailableOdds($apiResult)]];
        }

        try {
            $spread = $this->extractOddsGroup($apiResult, $this->spreadPath);
            [$pointSpreadHome, $pointSpreadHomeLine, $pointSpreadAway, $pointSpreadAwayLine] = $this->extractSpread(
                $spread,
                $homeTeamName,
                $awayTeamName
            );
        } catch (PathNotFound $e) {
            $pointSpreadHome = $pointSpreadHomeLine = $pointSpreadAway = $pointSpreadAwayLine = null;
            $this->errors[] = ['Spread not found', ['path' => $this->spreadPath, 'availableOdds' => $this->getAvailableOdds($apiResult)]];
        }

        try {
            $totals = $this->extractOddsGroup($apiResult, $this->totalsPath);
            [$overLine, $totalNumber, $underLine] = $this->extractTotals(
                $totals
            );
        } catch (PathNotFound $e) {
            $overLine = $totalNumber = $underLine = null;
            $this->errors[] = ['Totals not found', ['path' => $this->totalsPath, 'availableOdds' => $this->getAvailableOdds($apiResult)]];
        }

        return new SportEventOdd(
            $apiResult['FI'],
            $moneyLineHome,
            $moneyLineAway,
            $pointSpreadHome,
            $pointSpreadAway,
            $pointSpreadHomeLine,
            $pointSpreadAwayLine,
            $overLine,
            $underLine,
            $totalNumber,
        );
    }

    protected function cmpStr(string $a, string $b): bool
    {
        return strtolower(substr(trim($a), 0, strlen($b))) === strtolower(trim($b));
    }

    protected function extractTotals(array $totals): array
    {
        $overLine = null;
        $underLine = null;
        $totalNumber = null;

        foreach ($totals as $item) {
            if (!(isset($item['odds']) && isset($item['handicap']) && isset($item[$this->totalsTypeField]))) {
                $this->errors[] = [
                    sprintf(
                        'Unable to map odds for totals, required fields missing (odds, handicap, %s)',
                        $this->totalsTypeField
                    ),
                    ['item' => $item]
                ];
                continue;
            }

            $odds = decimal_to_american($item['odds']);
            $handicap = new Decimal($item['handicap']);

            if ($this->cmpStr($item[$this->totalsTypeField], 'Over')) {
                $overLine = $odds;
                $totalNumber = $handicap;
                continue;
            }

            if ($this->cmpStr($item[$this->totalsTypeField], 'Under')) {
                $underLine = $odds;
                $totalNumber = $handicap;
                continue;
            }

            $this->errors[] = ['Unable to map odds for totals, no matches found', ['item' => $item]];
        }

        $data = [$overLine, $totalNumber, $underLine];

        if ($overLine === null || $underLine === null) {
            $this->errors[] = ['Unable to find over or under line for totals', ['totals' => $totals, 'found' => $data]];
        }

        return $data;
    }

    protected function extractSpread(array $spread, string $homeTeamName, string $awayTeamName): array
    {
        $pointSpreadHome = null;
        $pointSpreadAway = null;
        $pointSpreadHomeLine = null;
        $pointSpreadAwayLine = null;

        foreach ($spread as $item) {
            if (!(isset($item['odds']) && isset($item['handicap']) && isset($item[$this->spreadTeamField]))) {
                $this->errors[] = [
                    sprintf(
                        'Unable to map odds for spread, required fields missing (odds, handicap, %s)',
                        $this->spreadTeamField
                    ),
                    ['item' => $item]
                ];
                continue;
            }

            $odds = decimal_to_american($item['odds']);
            $handicap = new Decimal($item['handicap']);

            if ($this->cmpStr($item[$this->spreadTeamField], $homeTeamName)) {
                $pointSpreadHome = $odds;
                $pointSpreadHomeLine = $handicap;
                continue;
            }

            if ($this->cmpStr($item[$this->spreadTeamField], $awayTeamName)) {
                $pointSpreadAway = $odds;
                $pointSpreadAwayLine = $handicap;
                continue;
            }

            $this->errors[] = ['Unable to map odds for spread, no matches found', ['item' => $item]];
        }

        $data = [$pointSpreadHome, $pointSpreadHomeLine, $pointSpreadAway, $pointSpreadAwayLine];
        if ($pointSpreadHome === null || $pointSpreadAway === null) {
            $this->errors[] = ['Unable to find home or away line for spread', ['spread' => $spread, 'found' => $data]];
        }

        return $data;
    }

    protected function extractMoneyLine(array $moneyLine, string $homeTeamName, string $awayTeamName): array
    {
        $moneyLineHome = null;
        $moneyLineAway = null;

        foreach ($moneyLine as $item) {
            if (!(isset($item['odds']) && isset($item[$this->spreadTeamField]))) {
                $this->errors[] = [
                    sprintf(
                        'Unable to map odds for money line, required fields missing (odds, %s)',
                        $this->moneyLineTeamField
                    ),
                    ['item' => $item]
                ];
                continue;
            }

            $odds = decimal_to_american($item['odds']);
            if ($this->cmpStr($item[$this->moneyLineTeamField], $homeTeamName)) {
                $moneyLineHome = $odds;
                continue;
            }

            if ($this->cmpStr($item[$this->moneyLineTeamField], $awayTeamName)) {
                $moneyLineAway = $odds;
                continue;
            }

            $this->errors[] = ['Unable to map odds for money line, no matches found', ['item' => $item]];
        }

        $data = [$moneyLineHome, $moneyLineAway];
        if ($moneyLineHome === null || $moneyLineAway === null) {
            $this->errors[] = ['Unable to find home or away money line', ['moneyline' => $moneyLine, 'found' => $data]];
        }

        return $data;
    }

    protected function extractOddsGroup(array $apiResult, array $path): array
    {
        $result = $apiResult;
        foreach ($path as $key) {
            if (!isset($result[$key])) {
                throw new PathNotFound();
            }
            $result = $result[$key];
        }

        return $result;
    }

    protected function getAvailableOdds(array $data): array
    {
        unset($data['event_id'], $data['FI']);
        $availableOdds = [];
        foreach ($data as $category => $odds) {
            $availableOdds[$category] = array_keys($odds['sp']);
        }

        return $availableOdds;
    }
}
