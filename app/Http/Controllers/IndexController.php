<?php

namespace App\Http\Controllers;

use App\Models\Settings;

class IndexController extends Controller
{
    public function index()
    {
        // Index sayfası her zaman logo ile gösterilir, yönlendirme yapılmaz
        // Login sayfasına sadece /login linkini bilen kişiler erişebilir
        return view('pages.index');
    }
}

