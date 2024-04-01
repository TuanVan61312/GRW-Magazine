@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    
                </li>
            </ol>
        </nav>

        @if (Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        
        <form action="{{ route('contributions.store')}}" method="post" enctype="multipart/form-data">@csrf

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Add Contribution</div>
                        <div class="card-body">

                            {{-- <div class="form-group">
                                <label>Created By</label>

                                <select class="form-control" name="user_id" required="">

                                    @foreach (App\Models\User as $user)
                                        <option value="{{ $user->id }}">
                                            {{  Auth()->user()->name  }}
                                        </option>
                                    @endforeach

                                </select>

                            </div> --}}

                            <div class="form-group mt-4">
                                <label>Created By</label>
                                <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required="" value="{{auth()->user()->name}}">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        @error('user_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
        
                            </div>

                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" name="file[]" class="form-control" multiple>
                            </div>


                            <div class="form-group">
                                <label>Faculty</label>
                                <select class="form-control" name="faculty_id" id="faculty_id" required="">

                                    @foreach (App\Models\Faculty::all() as $faculty)
                                        <option value="{{ $faculty->id }}">
                                            {{ $faculty->name }} 
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <label>Event</label>
                                <select class="form-control" name="event_id" required="">

                                    @foreach (App\Models\Event::all() as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->title }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- <div class="mt-4">
                                <button class="btn btn-primary " type="submit">Submit</button>
                            </div> --}}
                            <div class="mt-4">
                                @if(!$event->hasExpired())
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                @else
                                    <span class="text-danger">The event is overdue. Cannot submit assignment.</span>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
            </div>
        </form>
    </div> 
@endsection

