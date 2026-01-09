<?php

namespace App\Http\Controllers;

use App\User;
use App\Slots;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlotsController extends Controller {

	public function getGames(Request $r) {
		$search = $r->search;
		$page = $r->page ?: 1;
		$provider = $r->provider;
		$show = 30;

		$games = Slots::getCached();

		$games = !$search
			? $games
			: $games->filter(function ($game) use ($search) {
				return false !== stristr($game['gm_title'], $search) || false !== stristr($game['provider']['title'], $search);
			});

		$games = !$provider
			? $games
			: $games->filter(function ($game) use ($provider) {
				return $game['provider']['id'] == $provider;
			});

		$games_count = $games->count();
		$games = $games->skip($show * ($page - 1))->take($show)->values();

		$games = $games->map(function ($game) {
			return [
				'title' => $game['gm_title'],
				'game_id' => $game['gm_bk_id'],
				'game_url' => route('slots.game', ['gameId' => $game['gm_bk_id']]),
				'game_url_demo' => route('slots.game.demo', ['gameId' => $game['gm_bk_id'], 'demo' => 'demo']),
				'game_icon' => "https://int.apiforb2b.com/game/icons/" . $game['icons'][0]['ic_name'],
				'provider' => $game['provider'],
			];
		});

		return [
			'success' => true,
			'games' => $games,
			'games_count' => $games_count,
			'lastPage' => $games_count - $show * $page < 1,
		];
	}

	public function getGameURI(Request $r) {
		$gameId = $r->gameId;

		if (!$this->user) return [
			'error' => true,
			'message' => 'Вы не авторизованы'
		];

		if ($this->user->ban) return [
			'error' => true,
			'message' => 'Ваш аккаунт заблокирован'
		];

		if (!$this->user->api_token) return [
			'error' => true,
			'message' => 'Отсутствует api_token. Обновите страницу'
		];

		if (!in_array($this->user->admin, [1,3]) && $this->user->last_seven_days_deps < 150) {
			return [
				'error' => true,
				'message' => 'Вы ещё не пополнили на 150 рублей за последнюю неделю!'
			];
		}

		if($this->user && $this->user->is_youtuber) {
			$this->OPERATOR_ID = $this->YT_OPERATOR_ID;
		}

		$slot = Slots::getCached()->where('gm_bk_id', $gameId)->map(function ($game) {
			return [
				'title' => $game['gm_title'],
				'game_id' => $game['gm_bk_id'],
				'game_url' => route('slots.game', ['gameId' => $game['gm_bk_id']]),
				'game_url_demo' => route('slots.game.demo', ['gameId' => $game['gm_bk_id'], 'demo' => 'demo']),
				'game_icon' => "https://int.apiforb2b.com/game/icons/" . $game['icons'][0]['ic_name'],
				'provider' => $game['provider'],
			];
		})->first();

		$url = "https://int.apiforb2b.com/gamesbycode/{$gameId}.gamecode?operator_id={$this->OPERATOR_ID}&language=ru&user_id={$this->user->id}&auth_token={$this->user->api_token}&currency=RUB&home_url=https://{$_SERVER['HTTP_HOST']}/slots";

		return [
			'success' => true,
			'url' => $url,
			// 'slot' => $slot,
			'name' => $slot['title'],
			'image' => $slot['game_icon'],
		];
	}

	public function game(Request $r, $gameId, $demo = null) {
		// if (!$this->user || !in_array($this->user->id, [1,12,3])) return redirect('/');
		$user_id = $this->user ? $this->user->id : 0;
		$user_api_token = $this->user ? $this->user->api_token : 0;
		if ($demo == 'demo') {
			$this->OPERATOR_ID = 0;
		} else {
			if($this->user && $this->user->is_youtuber) {
				$this->OPERATOR_ID = $this->YT_OPERATOR_ID;
			}
		}
		$slot = Slots::getCached()->where('gm_bk_id', $gameId)->first();
		if (!$slot) return view('open');
		$gameUrl = "https://int.apiforb2b.com/gamesbycode/{$gameId}.gamecode?operator_id={$this->OPERATOR_ID}&language=ru&user_id={$user_id}&auth_token={$user_api_token}&currency=RUB&home_url=https://{$_SERVER['HTTP_HOST']}/slots";
		return view('pages.slots.game', compact('gameUrl', 'slot', 'demo'));
	}

	public function callback(Request $request) {
		Log::debug(">> slot call");
		if (!in_array($this->getIp(), ['62.112.11.44'])) return response(['message' => 'hacking attempt!']);
		try {
			switch ($request->api) {
				case 'do-auth-user-ingame':
					$data = app('App\Http\Controllers\Slots\AuthController')->initAuth($request);
					return json_encode($data);
					break;

				case 'do-debit-user-ingame':
					$data = app('App\Http\Controllers\Slots\DebitController')->debit($request);
					return json_encode($data);
					break;

				case 'do-credit-user-ingame':
					$data = app('App\Http\Controllers\Slots\CreditController')->credit($request);
					return json_encode($data);
					break;

				case 'do-rollback-user-ingame':
					break;

				case 'do-get-features-user-ingame':
					$data = app('App\Http\Controllers\Slots\FeaturesController')->getFeatures($request);
					return json_encode($data);
					break;

				case 'do-activate-features-user-ingame':
					$data = app('App\Http\Controllers\Slots\FeaturesController')->activateFeatures($request);
					return json_encode($data);
					break;

				case 'do-update-features-user-ingame':
					$data = app('App\Http\Controllers\Slots\FeaturesController')->updateFeatures($request);
					return json_encode($data);
					break;

				case 'do-end-features-user-ingame':
					$data = app('App\Http\Controllers\Slots\FeaturesController')->endFeatures($request);
					return json_encode($data);
					break;

				default:
					throw new Exception("Unknown api");
			}
		} catch (Exception $e) {
			$response = (object) [];
			$response->answer = (object) [];
			$response->answer->error_code = 1;
			$response->answer->error_description = $e->getMessage();
			$response->answer->timestamp = '"' . time() . '"';
			$response->api = $request->api;
			$response->success = true;

			return json_encode($response);
		}
	}
}