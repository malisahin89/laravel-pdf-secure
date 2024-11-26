<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PdfFile;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        $user = Auth::user();
        $pdfPath = PdfFile::select('unikey','file_name')->where('user_id',$user->id)->get();

        return view('home', ['pdfs' => $pdfPath]);
    }
}
