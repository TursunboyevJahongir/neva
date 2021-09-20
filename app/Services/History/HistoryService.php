<?php

namespace App\Services\History;

use App\Models\Category;
use App\Models\HistoryView;
use App\Models\Image;
use Illuminate\Http\Request;

class HistoryService
{


    public function record(Request $request)
    {
        $stories = HistoryView::where('ip_address', $request->ip())
            ->where('user_agent', $request->userAgent())
            ->orderByDesc('id')
            ->get();

        if($stories->isNotEmpty())
        {
            $history = HistoryView::where('ip_address', $request->ip())
                ->where('user_agent', $request->userAgent())
                ->orderByDesc('id')
                ->first();
                if ($history->url !== $request->path()) {
                    HistoryView::create([
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'url' => $request->path(),
                        'start_time' => \Carbon\Carbon::now(),//date('Y-m-d H:i:s'),
                        'end_time' =>\Carbon\Carbon::now(),// date('Y-m-d H:i:s')
                    ]);
                }
            $history->end_time=\Carbon\Carbon::now();
            $history->save();
        }
        else
            HistoryView::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->path(),
                'start_time' =>\Carbon\Carbon::now(), //date('Y-m-d H:i:s'),
                'end_time' =>\Carbon\Carbon::now()// date('Y-m-d H:i:s')
            ]);

    }
}
