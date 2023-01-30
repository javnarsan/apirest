<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articles;
use App\Cicles;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $emptyList=False;
        $articles = Articles::latest()->paginate(10);
        if($articles->isEmpty()){
            $emptyList=True;
        }else{
            foreach($articles as $article){
                if($article->deleted==0){
                    $emptyList=False;
                    break;
                }else{
                    $emptyList=True;
                }
            }
        }
        return view('articlesViews/articlesIndex', compact('articles','emptyList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cicles=cicles::all();
        return view('articlesViews/createArticle', compact('cicles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'image'=>'required',
            'description'=>'required',
            'cicle_id'=>'required'
        ]); 
        $article = new Articles;
        $article->title = $request->get('title');
        $file = $request->file('image');
        //obtenemos el nombre del archivo
        $nombre =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('imagenperfil')->put($nombre,  \File::get($file));
        $article->image = $nombre;
        $article->description = $request->get('description');
        $article->cicle_id = $request->get('cicle_id');
        $article->save();
        
        return redirect('articlesViews')->with('success', 'Article created.');
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
        $cicles=cicles::all();
        $article = Articles::find($id);
        
        return view('articlesViews/editArticle', compact(['article','cicles']));
        
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
        $request->validate([
            'title'=>'required',
            'image',
            'description'=>'required',
            'cicle_id',
        ]); 
        $article = Articles::find($id);
        // Getting values from the blade template form
        $article->title = $request->get('title');
        if($request->file('image')==null){

        }else{
        $file = $request->file('image');
        //obtenemos el nombre del archivo
        $nombre =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('imagenperfil')->put($nombre,  \File::get($file));
        $article->image = $nombre;
        }
        $article->description = $request->get('description');
        if($request->get('cicle_id')==null){

        }else{
        $article->cicle_id = $request->get('cicle_id');
        }
        $article->update();

        return redirect('articlesViews')->with('success', 'Article updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Articles::find($id);
        $article->deleted=1;
        $article->update();
        return redirect('articlesViews')->with('success', 'Article deleted.'); 
    }
}
