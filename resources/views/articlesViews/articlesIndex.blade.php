@extends('layouts/base')
 

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style type="text/css">
        
        .margen{
        margin-left: 40px;
        }
        .fondoCabecero{
        background-color: #555353
        }

        #column{
            max-width: 500px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            width: 100%;
        }

        
    </style>
</head>

<body class="bg-dark">

@section('main')
<div id="app">
    <main class="py-4">
        @yield('main')
    </main>
    
    <div class="row">
        <div class="col-sm-12">
            <h1 class="display-3 text-light">Articles</h1>
            <div>
                <form action="{{ route('articlesViews.create')}}" >
                    @csrf
                    <button class="btn btn-primary" type="submit">Add Article</button>
                </form>
            </div>
        </div>     
        @if($emptyList==True)
            <div class="column col-sm-7">
                <h2 class="display-4 text-light bg-danger text-center">There are no articles yet</h2>
            </div>
        @else
        <table class="table table-striped">
            <thead>
                <tr>
                <td class="text-light">ID</td>
                <td class="text-light">Title</td>
                <td class="text-light">Image</td>
                <td class="text-light">Description</td>
                <td class="text-light">Cicle_id</td>
                <td class="text-light text-center">Actions</td>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                @if($article->deleted==0)
                <tr>
                    <td class="text-light">{{($article->id)}}</td>
                    <td class="text-light" id="column">{{($article->title)}} </td>
                    <td class="text-light">
                        @if(str_contains($article->image, ".png" or ".jpg"))
                        <img class="group list-group-image" src="{{('images/'.$article->image) }}" width=150px height=150px/>
                        @else
                        <img class="group list-group-image" src="{{ ('images/noimage.png') }}" width=150px height=150px/>
                        @endif
                    </td>
                    <td class="text-light" id="column">{{($article->description)}}</td>
                    <td class="text-light">{{($article->cicle_id)}}</td>
                    <td class="text-light">
                        <a href="{{ route('articlesViews.edit', $article->id)}}" class="btn btn-primary">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('articlesViews.destroy', $article->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Are you sure to delete this article?');" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
        
    <div>
        @if($articles->count())
            {{ $articles->links() }}
        @endif
    </div>
</body>
@endsection