<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ThemeController extends BaseController
{
    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        $request->session()->put('theme', $request->input('theme'));

        return back();
    }
}
