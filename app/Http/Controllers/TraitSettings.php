<?php

namespace App\Http\Controllers;

use DB;

trait TraitSettings
{
    /**
     * get application settings
     *
     * @return object
     */
    public function getapplications()
    {
        $data = DB::table('settings')->where('settingsid', '1')->get();

        return json_decode($data);
    }
}
