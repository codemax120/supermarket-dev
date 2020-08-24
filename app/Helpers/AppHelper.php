<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppHelper
{

    public function setLogs($user_id, $action, $last_response, $ip_address)
    {
        DB::table('history_logs')->insert([
            'user_id' => $user_id,
            'action' => $action,
            'last_response' => $last_response,
            'ip_address' => $ip_address,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

}
