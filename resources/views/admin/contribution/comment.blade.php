@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-11 ms-5">
                <div class="col-md-12">
                    <div class="alert alert-secondary">
                        Contribution Comment {{ $contribution->title }} 
                    </div>
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    {{-- <p>Contribution Title: {{ $contribution->title }}</p> --}}

                    <form action="{{ route('contributions.submitComment', $contribution) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <div class="mt-4">
                                <textarea class="form-control" id="comment" name="content" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection