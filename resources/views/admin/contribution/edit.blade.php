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

        <form action="{{ route('contributions.update', $contribution->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Sử dụng phương thức PUT để cập nhật -->

            <input type="hidden" name="id" value="{{ $contribution->id }}"> <!-- Thêm trường ẩn chứa ID của contribution -->

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Edit Contribution</div>
                        <div class="card-body">

                            {{-- Các trường cập nhật --}}
                            <div class="form-group mt-4">
                                <label>Created By</label>
                                <input type="text" name="user_id" class="form-control" value="{{ $contribution->user->name }}" disabled>
                            </div>

                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $contribution->title }}">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control" value="{{ $contribution->description }}">
                            </div>
                            
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" name="file[]" class="form-control" multiple>
                            </div>

                            {{-- <div class="form-group">
                                <label>Faculty</label>
                                <select class="form-control" name="faculty_id" id="faculty_id" required="">
                                    @foreach (App\Models\Faculty::all() as $faculty)
                                        <option value="{{ $faculty->id }}" {{ $contribution->faculty_id == $faculty->id ? 'selected' : '' }}>
                                            {{ $faculty->name }} 
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label>Faculty</label>
                                <input type="text" class="form-control" value="{{ $contribution->user->faculty->name }}" disabled>
                            </div>

                            {{-- <div class="form-group">
                                <label>Event</label>
                                <select class="form-control" name="event_id" required="">
                                    @foreach (App\Models\Event::all() as $event)
                                        <option value="{{ $event->id }}" {{ $contribution->event_id == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label>Event</label>
                                <input type="text" class="form-control" value="{{ $contribution->event->title }}" disabled>
                            </div>
                            

                            <div class="mt-4">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>                            
                        </div>                       
                    </div>                   
                </div>                
            </div>
        </form>
    </div> 
@endsection