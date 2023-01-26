<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Offers;
use Validator;

class offersController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offers::all();

        return response()->json(['Offers' => $offers->toArray()], $this->successStatus);

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
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'date_max' => 'required',
            'num_candidates' => 'required',
            'cicle_id' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $offer = Offers::create($input);

        return response()->json(['Offer' => $offer->toArray()], $this->successStatus);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = Offers::find($id);

        if (is_null($offer)) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        return response()->json(['Producto' => $offer->toArray()], $this->successStatus);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $offer = Offers::find($id);
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'date_max' => 'required',
            'num_candidates' => 'required',
            'cicle_id' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $offer->title = $input['title'];
        $offer->description = $input['description'];
        $offer->date_max = $input['date_max'];
        $offer->num_candidates = $input['num_candidates'];
        $offer->cicle_id = $input['cicle_id'];
        $offer->save();

        return response()->json(['Offer' => $offer->toArray()], $this->successStatus);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer = Offers::find($id);
        $offer->delete();

        return response()->json(['Offer' => $offer->toArray()], $this->successStatus);

    }
}