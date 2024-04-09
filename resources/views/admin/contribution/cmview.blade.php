@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-11 ms-5">
                <div class="col-md-12">
                    <div class="alert alert-secondary">
                        Comments
                    </div>
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    
                    <table  class="table table-bordered" id="datatablesSimple">
                        <tbody>
                            @foreach ($comments as $comment)
                            <div class="card mb-2">
                                <div class="card-body">
                                    <p class="card-text">{{ $comment->content }}</p>
                                    <p><span class="badge rounded-pill bg-success">Commented by: {{ $comment->user->name }}</span></p>
                                    <p><span class="badge bg-warning text-dark">Commented at: {{ $comment->commented_at }}</span></p>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
