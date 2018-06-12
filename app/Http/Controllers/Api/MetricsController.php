<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Metric;
use App\Snapshot;
use Jacobemerick\KMeans\Kmeans;

class MetricsController extends Controller
{
    private function kmeans($data, $k)
    {
        $data = $data->toArray();
        $k = (float) $k;

        // using floats it doesnt throw "Division by zero" error.

        $kmeans = new Kmeans($data);

        $kmeans->cluster($k);

        $clustered_data = $kmeans->getClusteredData();
        $centroids = $kmeans->getCentroids();

        return [
            'k' => $k,
            'data' => [
                'clustered_data' => $clustered_data,
                'centroids' => $centroids
            ]
        ];
    }

    public function createMetric(Request $request)
    {
        $validated = $request->validate([
            'snapshot_id' => 'required|numeric',
            // 'k' => 'numeric|integer|min:2|max:10'
        ]);

        $snapshot = Snapshot::findOrFail($validated['snapshot_id']);

        $data = $snapshot->data->transform(function($item) {
            return [$item['orders_count']];
        });

        // $metric_data = $this->kmeans($data, $validated['k'] ?? 3);
        $metric_data = $this->kmeans($data, 3);

        $metric = Metric::create([
            'from_date' => $snapshot->from_date,
            'to_date' => $snapshot->to_date,
            'k' => $metric_data['k'],
            'data' => collect($metric_data['data'])
        ]);

        return response()->json($metric->id);
    }

    public function getMetrics(Request $request)
    {
        if($request->has('ids')) {

            $metrics = collect([]);

            $ids = collect(explode(',', $request->ids));

            $ids->each(function($id) use ($metrics) {
                $c = Metric::findOrFail($id);
                $metrics->push($c);
            });

            return response()->json($metrics);
        }

        if($request->has('from_date') && $request->has('to_date')) {

            $validated = $request->validate([
                'from_date' => 'required|date_format:"Y-m-d H:i:s"|before:to_date',
                'to_date' => 'required|date_format:"Y-m-d H:i:s"|after:from_date'
            ]);

            $metrics = Metric::where('from_date', '>=', $validated['from_date'])
                                ->where('to_date', '<=', $validated['to_date'])
                                ->orderBy('created_at', 'desc')->get();

        } else {
            $metrics = Metric::orderBy('created_at', 'desc')->get();
        }

        return response()->json($metrics);
    }
}
