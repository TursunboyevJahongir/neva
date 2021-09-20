<?php

namespace App\Services\History;

use App\Models\Category;
use App\Models\Analytic;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticService
{


    public function record(Request $request)
    {
        $stories = Analytic::where('ip_address', $request->ip())
            ->where('user_agent', $request->userAgent())
            ->where('user_id' , (Auth::guest() ? null: Auth::id()))
            ->orderByDesc('id')
            ->get();

        if($stories->isNotEmpty())
        {
            $history = Analytic::where('ip_address', $request->ip())
                ->where('user_agent', $request->userAgent())
                ->where('user_id' , (Auth::guest() ? null: Auth::id()))
                ->orderByDesc('id')
                ->first();
                if ($history->url !== $request->path()) {
                    Analytic::create([
                        'ip_address' => $request->ip(),
                        'user_id' => Auth::guest() ? null: Auth::id(),
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
