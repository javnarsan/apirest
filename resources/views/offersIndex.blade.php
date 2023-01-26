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
            <h1 class="display-3 text-light">Offers</h1>
            
        </div>     
       
        <table class="table table-striped">
            <thead>
                <tr>
                <td class="text-light">ID</td>
                <td class="text-light">Title</td>
                <td class="text-light">Description</td>
                <td class="text-light">Date Max </td>
                <td class="text-light">Candidates </td>
                <td class="text-light">Cicle_id</td>
               
                </tr>
            </thead>
            <tbody>
                @foreach($offers as $offer)
          
                <tr>
                    <td class="text-light">{{($offer->id)}}</td>
                    <td class="text-light" >{{($offer->title)}} </td>
                    <td class="text-light" id="column">{{($offer->description)}}</td>
                    <td class="text-light" id="column">{{($offer->date_max)}}</td>
                    <td class="text-light">{{($offer->num_candidates)}}</td>
                    <td class="text-light">{{($offer->cicle_id)}}</td>
                 
                </tr>
        
                @endforeach
            </tbody>
        </table>
      
    </div>
        
    <div>
        @if($offers->count())
            {{ $offers->links() }}
        @endif
    </div>
</body>
@endsection