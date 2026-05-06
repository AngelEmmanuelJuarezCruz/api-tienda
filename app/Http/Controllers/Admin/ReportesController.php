<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Mostrar el panel de reportes.
     */
    public function index(Request $request)
    {
        return view('admin.reportes.index');
    }
}
