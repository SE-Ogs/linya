<?php

namespace App\Http\Controllers;


class SettingsController extends Controller{

    public function showSettings(){
        return view('home.settings');
    }
}
