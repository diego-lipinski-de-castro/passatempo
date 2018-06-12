<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classification;
use App\Metric;
use App\User;

class ClassificationsController extends Controller
{
    public function createClassification(Request $request)
    {
        $validated = $request->validate([
            'metric_id' => 'required|numeric'
        ]);

        $metric = Metric::findOrFail($validated['metric_id']);

        $centroids = collect($metric->data['centroids'])->transform(function($c) {
            return round($c[0]);
        });

        $centroids = $centroids->sort()->values();

        $users = User::select('id')->whereHas('orders', function ($q) use ($metric) {
            $q->whereBetween('date', [$metric->from_date, $metric->to_date]);
        })->get();

        $users = $users->each(function($u) {
            return $u->orders_count = $u->orders()->count();
        });

        $users = $users->sortBy('orders_count')->values();

        $low = $users->where('orders_count', '<', $centroids->first())->pluck('id')->toArray();

        $middle = $users->whereIn('orders_count', [$centroids->first(), $centroids->last()])
                        ->values()->pluck('id')->toArray();

        $top = $users->where('orders_count', '>', $centroids->last())->values()->pluck('id')->toArray();

        Classification::create([
            'from_date' => $metric->from_date,
            'to_date' => $metric->to_date,
            'data' => [
                'low' => [
                    'count' => count($low),
                    'data' => $low
                ],
                'middle' => [
                    'count' => count($middle),
                    'data' => $middle
                ],
                'top' => [
                    'count' => count($top),
                    'data' => $top
                ]
            ]
        ]);

        return response()->json(['done' => true]);
    }

    public function getClassifications(Request $request)
    {
        if($request->has('ids')) {

            $classifications = collect([]);

            $ids = collect(explode(',', $request->ids));

            $ids->each(function($id) use ($classifications) {
                $c = Classification::findOrFail($id);
                $classifications->push($c);
            });

            return response()->json($classifications);
        }

        if($request->has('from_date') && $request->has('to_date')) {

            $validated = $request->validate([
                'from_date' => 'required|date_format:"Y-m-d H:i:s"|before:to_date',
                'to_date' => 'required|date_format:"Y-m-d H:i:s"|after:from_date'
            ]);

            $classifications = Classification::where('from_date', '>=', $validated['from_date'])
                                ->where('to_date', '<=', $validated['to_date'])
                                ->orderBy('created_at', 'desc')->get();

        } else {
            $classifications = Classification::orderBy('created_at', 'desc')->get();
        }

        return response()->json($classifications);
    }
}
