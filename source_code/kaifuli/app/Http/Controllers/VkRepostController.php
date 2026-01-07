<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \VK\CallbackApi\VKCallbackApiHandler;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use DB;

class VkRepostController extends VKCallbackApiHandler
{
    public function success()
    {
        $data = json_decode(file_get_contents('php://input'));
        if ($data->type == 'wall_post_new') {
            $this->wall_post_new($data);
        }
        if ($data->type == 'wall_repost') {
            $this->wall_repost($data);
        }
        return 'ok';
    }
    public function wall_post_new($data)
    {
        $select = DB::table('group_post')->where(['post_id' => $data->object->id])->count();
        if (!$select) {
            DB::table('group_post')->insert([
                'post_id' => $data->object->id
            ]);
        }
    }
    public function wall_repost($data)
    {
        $post_id = $data->object->copy_history[0]->id;
        $user_id = $data->object->from_id;
        $user = User::where(['vk_id' => $user_id])->first();
        $take15 = DB::table('group_post')->latest('id')->take(5)->get();
        $arr = [];

        for ($i = 0; $i < count($take15); $i++) {
            $arr[] = $take15[$i]->post_id;
        }
        if (in_array($post_id, $arr)) {
            if ($user) {
                $select = DB::table('user_repost')->where(['user_id' => $user->id, 'post_id' => $post_id])->count();
                if (!$select) {
                    DB::table('user_repost')->insert([
                        'user_id' => $user->id,
                        'post_id' => $post_id
                    ]);
                }
            }
        }
    }
}
