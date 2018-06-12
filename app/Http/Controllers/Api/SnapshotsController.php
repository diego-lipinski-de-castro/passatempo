<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Snapshot;

class SnapshotsController extends Controller
{
    public function createSnapshot(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'required|date_format:"Y-m-d H:i:s"|before:to_date',
            'to_date' => 'required|date_format:"Y-m-d H:i:s"|after:from_date'
        ]);

        $data = User::select('id')->whereHas('orders', function ($q) use ($validated) {
            $q->whereBetween('date', [$validated['from_date'], $validated['to_date']]);
        })->get();

        $data = $data->each(function($u) {
            $u->orders_count = $u->orders()->count();
        });

        $data = $data->transform(function($u) {
            return ['orders_count' => $u->orders_count];
        });

        $data = $data->sortBy('orders_count')->values();

        $snapshot = Snapshot::create([
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'data' => $data
        ]);

        return response()->json($snapshot->id);
    }

    public function getSnapshots(Request $request)
    {
       if($request->has('ids')) {

            $snapshots = collect([]);

            $ids = collect(explode(',', $request->ids));

            $ids->each(function($id) use ($snapshots) {
                $c = Snapshot::findOrFail($id);
                $snapshots->push($c);
            });

            return response()->json($snapshots);
        }

        if($request->has('from_date') && $request->has('to_date')) {

            $validated = $request->validate([
                'from_date' => 'required|date_format:"Y-m-d H:i:s"|before:to_date',
                'to_date' => 'required|date_format:"Y-m-d H:i:s"|after:from_date'
            ]);

            $snapshots = Snapshot::where('from_date', '>=', $validated['from_date'])
                                ->where('to_date', '<=', $validated['to_date'])
                                ->orderBy('created_at', 'desc')->get();

        } else {
            $snapshots = Snapshot::orderBy('created_at', 'desc')->get();
        }

        return response()->json($snapshots);
    }
}
