<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Slots extends Model
{

    public static function getCached() {
        $setting = Setting::first();
        $operator_id = $setting->b2b_operator_id;
        $slots = Cache::remember('slots.games.cached', 3600, function () use ($operator_id) {
			$response = Http::get("https://int.apiforb2b.com/frontendsrv/apihandler.api?cmd={%22api%22:%22ls-games-by-operator-id-get%22,%22operator_id%22:%22{$operator_id}%22}");
			return $response->json()['locator'];
		});
		return collect($slots['groups'])->map(function ($group) {
			return collect($group['games'])->map(function ($game) use ($group) {
				$game['provider'] = [
					'title' => $group['gr_title'],
					'id' => $group['gr_id']
				];
				return $game;
			})->values();
		})->flatten(1);
    }
}