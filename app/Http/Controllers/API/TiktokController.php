<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\TikTok;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TiktokController extends AppBaseController
{
    public function listLog(Request $request)
    {
        $toDate = $request->toDate;
        $fromDate = $request->fromDate;
        $ins = TikTok::orderBy('created_at', 'DESC')
            ->when(isset($request->key), function ($q) use ($request) {
                $q->where('url', 'LIKE', '%' . $request->key . '%');
            })
            ->when(($toDate && $fromDate), function ($q) use ($toDate, $fromDate) {
                $q->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $toDate))
                    ->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $fromDate));
            })
            ->paginate(100);
        return $this->responseAPI(true, 'success', $ins, 200);
    }

    public function chartByToday(Request $request)
    {
        $toDate = $request->toDate;
        $fromDate = $request->fromDate;
        $results = [];
        $results['success'] = TikTok::where('status', 1)
            ->when(isset($request->key), function ($q) use ($request) {
                $q->where('url', 'LIKE', '%' . $request->key . '%');
            })
            ->when(($toDate && $fromDate), function ($q) use ($toDate, $fromDate) {
                $q->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $toDate))
                    ->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $fromDate));
            })
            ->when(($toDate == '' || $fromDate == ''), function ($q) use ($toDate, $fromDate) {
                $q->whereDate('created_at', Carbon::today());
            })
            ->count();
        $results['fail'] = TikTok::where('status', 0)->when(($toDate && $fromDate), function ($q) use ($toDate, $fromDate) {
            $q->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $toDate))
                ->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $fromDate));
        })
            ->when(isset($request->key), function ($q) use ($request) {
                $q->where('url', 'LIKE', '%' . $request->key . '%');
            })
            ->when(($toDate == '' || $fromDate == ''), function ($q) use ($toDate, $fromDate) {
                $q->whereDate('created_at', Carbon::today());
            })
            ->count();
        return $this->responseAPI(true, 'success', $results, 200);
    }

    public function reqByMonth(Request $request)
    {
        $now = Carbon::now();

        $response = [];
        $response['crawl'] = TikTok::where('api', 'crawl')->whereMonth('created_at', '=', $now->month)->count();
        $response['public'] = TikTok::where('api', 'public')->whereMonth('created_at', '=', $now->month)->count();

        return $this->responseAPI(true, 'success', $response, 200);
    }

}
