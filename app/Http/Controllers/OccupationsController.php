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
        $sortedSkills1 = $this->sortSkills($occupation1);
        $sortedSkills2 = $this->sortSkills($occupation2);
        $matchedSkills = $this->getMatchedSkills($sortedSkills1, $sortedSkills2);

        // ****************************************************************************
        // TODO: check if unmatched/incomparable skills are actually a possibility
        // before implementing, else remove
        //
        // $unmatchedSkills = $this->getUnmatchedSkills($sortedSkills1, $sortedSkills2, $matchedSkills);
        // ****************************************************************************
        try {
            $scores = array_sum(array_map(fn ($s) => $s['comparison'], $matchedSkills));
            $total = round($scores / count($matchedSkills), 2);
        } catch (Exception $e) {
            if ($e instanceof DivisionByZeroError) $total = 0;
            else throw $e;
        }

        return [
            'match' => [
                'occupation1' => $occupation1,
                'occupation2' => $occupation2,
                'matchedSkills' => $matchedSkills,
                'total' => $total,
                // ***********************************************************************
                // TODO: check if unmatched/incomparable skills are actually a possibility
                // before implementing, else remove
                //
                // 'unmatchedSkills' => $unmatchedSkills
                // ***********************************************************************
            ]
        ];
    }

    private function getMatchedSkills($sortedSkills1, $sortedSkills2)
    {
        $matchedSkills = [];

        foreach ($sortedSkills1 as $skillName => $skill1) {
            $skill2 = $sortedSkills2[$skillName];
            if ($skill2) $matchedSkills[] = [
                'score1' => $skill1[0],
                'score2' => $skill2[0],
                'name' => $skillName,
                'description' => $skill1[2]
            ];
        }

        return $this->getComparedSkills($matchedSkills);;
    }

    // *******************************************************************************************
    // TODO: check if unmatched/incomparable skills are actually a possibility
    // before implementing, else remove
    //
    // private function getUnmatchedSkills($sortedSkills1, $sortedSkills2, $matchedSkills)
    // {
    //     $unmatchedSkills = [];
    //     $keys1 = array_keys($sortedSkills1);
    //     $keys2 = array_keys($sortedSkills2);
    //     $allKeys = array_unique([...$keys1, ...$keys2]);
    //     $matchedKeys = array_map(fn ($s) => $s['name'], $matchedSkills);
    //     $unmatchedKeys = array_diff($allKeys, $matchedKeys);
    //     // filter matchedSkills by unmatchedKeys
    //     return $unmatchedSkills;
    // }
    // *******************************************************************************************

    private function getComparedSkills($matchedSkills)
    {
        return array_map(function ($o) {
            return [
                ...$o,
                'comparison' => 100 - abs((int)($o['score1'] - $o['score2']))
            ];
        }, $matchedSkills);
    }

    private function sortSkills($occupations)
    {
        $sortedOccupations = [];
        foreach ($occupations as $occupation) $sortedOccupations[$occupation[1]] = $occupation;
        return $sortedOccupations;
    }

    public function compare(Request $request)
    {
        $this->occparser->setScope('skills');
        $occupation1 = $this->occparser->get($request->get('occupation1'));
        $occupation2 = $this->occparser->get($request->get('occupation2'));
        return $this->getMatch($occupation1, $occupation2);
    }
}
