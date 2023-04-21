<?php

namespace App\Http\Controllers;

use App\Contracts\OccupationParser;
use DivisionByZeroError;
use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OccupationsController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $occparser;

    public function __construct(OccupationParser $parser)
    {
        $this->occparser = $parser;
    }

    public function index()
    {
        return $this->occparser->list();
    }

    private function getMatch(array $occupation1, array $occupation2)
    {
        $indexedSkills1 = $this->indexSkills($occupation1);
        $indexedSkills2 = $this->indexSkills($occupation2);
        $matchedSkills = $this->getMatchedSkills($indexedSkills1, $indexedSkills2);
        $total = $this->getTotal($matchedSkills);


        return [
            'match' => [
                'occupation1' => $occupation1,
                'occupation2' => $occupation2,
                'matchedSkills' => $matchedSkills,
                'total' => $total,
            ]
        ];
    }

    private function getTotal($matchedSkills)
    {
        try {
            // ************************************************************************
            // The scores should be weighted to give high value skills more weight
            // in the comparison than the low value skills
            $weightedScores = array_map(function ($s) {

                // *************************************************************
                // This weight can be used to tune the algorithm,
                // with 1 being maximum adjustment for weight, and 0 being none.
                $weight = config('skills.COMPARISON_WEIGHTING');
                // An upper and lower threshold can be tuned to limit importance
                // to values between these ranges.
                $upperThreshold = config('skills.UPPER_THRESHOLD');
                $lowerThreshold = config('skills.LOWER_THRESHOLD');
                // *************************************************************
                $score1 = $s['score1'];
                $score2 = $s['score2'];
                $comparison = $s['comparison'];
                $highestScore = max($score1, $score2);
                if (
                    $highestScore > $upperThreshold
                    || $highestScore < $lowerThreshold
                ) return $comparison;
                $deficit = 100 - $highestScore;
                $importance = ($highestScore + (1 - $weight) * $deficit) / 100;
                $score = $importance * $comparison;
                return [
                    'score' => $score,
                    'importance' => $importance
                ];
            }, $matchedSkills);
            // $summedImportances = array_sum(array_map(fn ($s) => $s['importance'], $weightedScores));
            // $summedScores = array_sum(array_map(fn ($s) => $s['score'], $weightedScores));
            // $total = round($summedScores / $summedImportances, 2);
            $scores = array_map(fn ($s) => $s['score'], $weightedScores);
            $total = round(array_sum($scores) / count($matchedSkills), 2);
        } catch (Exception $e) {
            if ($e instanceof DivisionByZeroError) $total = 0;
            else throw $e;
        }
        return $total;
    }
    private function getMatchedSkills($indexedSkills1, $indexedSkills2)
    {
        $matchedSkills = [];
        $skillNames = array_unique([...array_keys($indexedSkills1), ...array_keys($indexedSkills2)]);
        foreach ($skillNames as $sn) {
            $skill1 = $indexedSkills1[$sn];
            $skill2 = $indexedSkills2[$sn];
            // If no skills found, nothing should happen
            if (!!$skill1 && !!$skill2) {
                // If both scores are zero, they should be ignored
                // else, if at least one has positive value, a comparable should be generated
                $score1 = $skill1[0];
                $score2 = $skill2[0];
                if ($score1 > 0 || $score2 > 0) $matchedSkills[] = [
                    'score1' => $score1,
                    'score2' => $score2,
                    'name' => $sn,
                    'description' => $skill1[2]
                ];
            }
        }

        return $this->getComparedSkills($matchedSkills);;
    }

    private function getComparedSkills($matchedSkills)
    {
        return array_map(function ($o) {
            return [
                ...$o,
                'comparison' => $this->getComparisonValue($o['score1'], $o['score2'])
            ];
        }, $matchedSkills);
    }

    private function getComparisonValue($s1, $s2)
    {
        return 100 * (10000 - $this->getDiffOfSquares($s1, $s2)) / 10000;
    }

    public function getDiffOfSquares($x1, $x2)
    {
        $x1Squared = $x1 * $x1;
        $x2Squared = $x2 * $x2;
        return abs(($x1Squared - $x2Squared));
    }

    private function indexSkills($occupations)
    {
        $indexedSkills = [];
        foreach ($occupations as $skill) $indexedSkills[$skill[1]] = $skill;
        return $indexedSkills;
    }

    public function compare(Request $request)
    {
        $this->occparser->setScope('skills');
        $occupation1 = $this->occparser->get($request->get('occupation1'));
        $occupation2 = $this->occparser->get($request->get('occupation2'));
        return $this->getMatch($occupation1, $occupation2);
    }
}
