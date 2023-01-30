<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Applied;
use App\Offers;
use App\Cicles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class OffersController extends Controller
{   

    public function generatePDF() {
        $user = Auth::user();
        $cicle = Cicles::where('id', $user->cicle_id)->get();
        //dd($cicle);
        $applieds = Applied::where('user_id', $user->id)->get();
        $offers = Offers::join('applieds','offers.id','=','applieds.offer_id')
                                    ->where('applieds.user_id', $user->id)->get();
        //dd($offers[0]->title);
        $pdf = \PDF::loadView('offers', compact('user','applieds','offers','cicle'));
        // Para crear un pdf en el navegador usaremos la siguiente línea
        return $pdf->stream();
    }

    public function sendPDF(){
        $user = Auth::user();
        $cicle = Cicles::where('id', $user->cicle_id)->get();
        
        $applieds = Applied::where('user_id', $user->id)->get();
        $offers = Offers::join('applieds','offers.id','=','applieds.offer_id')
                                    ->where('applieds.user_id', $user->id)->get();                                                   
        $pdf = \PDF::loadView('offers', compact('user','applieds','offers','cicle'))->output();
        $data = [
            'emailto' => "salesinlaravelcopia@outlook.es",
            'subject' => "SalesIn pdf generated",
            'content' => "You can download your pdf.",
        ];
        Storage::disk('thepdf')->delete('applied_offers.pdf');
        Storage::disk('thepdf')->put('applied_offers.pdf', $pdf);
        Mail::send('vistaEmail', $data, function($message) use ($data, $pdf) {
            $message->from(Auth::user()->email, Auth::user()->name);
            $message->to($data["emailto"], $data["emailto"])
                ->subject($data["subject"])
                ->attach(public_path('pdf\applied_offers.pdf'));
        });
        
        return back();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offers::latest()->paginate(10);
        return view('offersViews/offersIndex', compact('offers'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        //
       
    }

    
   

}
