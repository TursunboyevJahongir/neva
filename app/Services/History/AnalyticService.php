<?php

namespace App\Services\History;

use App\Models\Category;
use App\Models\Analytic;
use App\Models\Image;
use Illuminate\Http\Request;

class AnalyticService
{


    public function record(Request $request)
    {
        $stories = Analytic::where('ip_address', $request->ip())
            ->where('user_agent', $request->userAgent())
            ->orderByDesc('id')
            ->get();

        if($stories->isNotEmpty())
        {
            $history = Analytic::where('ip_address', $request->ip())
                ->where('user_agent', $request->userAgent())
                ->orderByDesc('id')
                ->first();
                if ($history->url !== $request->path()) {
                    Analytic::create([
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
            Analytic::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->path(),
                'start_time' =>\Carbon\Carbon::now(), //date('Y-m-d H:i:s'),
                'end_time' =>\Carbon\Carbon::now()// date('Y-m-d H:i:s')
            ]);

    }
}
