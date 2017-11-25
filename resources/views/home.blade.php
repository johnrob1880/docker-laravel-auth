@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
            
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left">Registered Kits</h4>
                    <div class="btn-group pull-right">
                        <a href="{{route('kit.new')}}" class="btn btn-default btn-sm">Add Kit</a>
                    </div>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach ($kits as $kit) 
                        {{ $kit['barcode'] }} - @if ($kit['is_complete']) Complete @else In Progress @endif
                    @endforeach

                    <br><br>
                    <div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left">Profile</h4>
                    <div class="btn-group pull-right">
                        <a href="#edit-profile" class="btn btn-default btn-sm">Edit</a>
                    </div>
                </div>

                <div class="panel-body">
                    <h1>Name: {{ Auth::user()->firstname}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
