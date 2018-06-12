<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Result;
use App\Classification;

class ResultsController extends Controller
{
    public function compare(Request $request)
    {
        $validated = $request->validate([
            'c1' => 'required|numeric|integer|different:c2',
            'c2' => 'required|numeric|integer|different:c1'
        ]);

        $c1 = Classification::findOrFail($validated['c1']);
        $c2 = Classification::findOrFail($validated['c2']);

        if($c1->from_date > $c2->from_date) {
            return response()->json(
                ['error' => 'First classification date must be higher than Second classification date']
            );
        }

        $to_low = collect($c2->data['low']['data'])->diff(collect($c1->data['low']['data']));
        $to_top = collect($c2->data['top']['data'])->diff(collect($c1->data['top']['data']));

        $still_low = collect($c1->data['low']['data'])->diff(collect($c2->data['low']['data']));
        $still_top = collect($c1->data['top']['data'])->diff(collect($c2->data['top']['data']));
        $still_middle = collect($c2->data['middle']['data'])->diff(collect($c1->data['middle']['data']));

        $data = [
            'to_low' => [
                'count' => $to_low->count(),
                'data' => $to_low->sort()->values()
            ],
            'to_top' => [
                'count' => $to_top->count(),
                'data' => $to_top->sort()->values()
            ],
            'same' => [
                'count' => $still_low->count() + $still_top->count() + $still_middle->count(),
                'all' => collect([])->concat($still_low)->concat($still_top)->concat($still_middle),
                'data' => [
                    'still_low' => [
                        'count' => $still_low->count(),
                        'data' => $still_low->sort()->values()
                    ],
                    'still_top' => [
                        'count' => $still_top->count(),
                        'data' => $still_top->sort()->values()
                    ],
                    'still_middle' => [
                        'count' => $still_middle->count(),
                        'data' => $still_middle->sort()->values()
                    ]
                ]
            ]
        ];

        Result::create([
            'c1' => $validated['c1'],
            'c2' => $validated['c2'],
            'data' => $data
        ]);

        return response()->json(['done' => true]);
    }

    public function getResults(Request $request)
    {
        if($request->has('ids')) {

            $results = collect([]);

            $ids = collect(explode(',', $request->ids));

            $ids->each(function($id) use ($results) {
                $c = Result::findOrFail($id);
                $results->push($c);
            });

            return response()->json($results);
        }

        $results = Result::all();
        return response()->json($results);
    }

    public function getResult($id)
    {
        $result = Result::findOrFail($id);
        return response()->json($result);
    }
}
