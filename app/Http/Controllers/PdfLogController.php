<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdfImportLog;

class PdfLogController extends Controller
{
    public function index()
    {
        // Recupera tutti i log, ordinandoli per data decrescente
        $logs = PdfImportLog::orderBy('imported_at', 'desc')->paginate(10);

        // Passa i log alla vista
        return view('logs.index', compact('logs'));
    }
}
