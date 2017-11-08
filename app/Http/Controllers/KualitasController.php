<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kualitas;
class KualitasController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function save(Request $request){
        //validate post data
        $this->validate($request, [
            'photo' => 'image|mimes:jpeg,bmp,png'
        ]);
        $filename = time().'.'.$request->photo->getClientOriginalExtension();
        $kualitas = new Kualitas();
        $kualitas->namafile = $filename;
        $kualitas->kualitas = $request->kualitas;
        $save = $kualitas->save();
        if(!$save){
            App::abort(500, 'Error');
        }
        return redirect('home')->with('status', 'Data Berhasil Disimpan.');
    }
}
