@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-secondary d-flex justify-content-between align-items-center" role="alert">
                    <h4 class="alert-heading">Event List</h4>
                    <a href="{{ route('events.create') }}" type="button" class="btn btn-primary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;">
                        Add Event
                    </a>
                </div>
                @if (Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                @endif

                <table class="table table-bordered" id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Faculty</th>
                            <th>Start Date</th>
                            <th>Final Date</th>
                            <th>Delete</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $key => $event)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                {{-- <td>{{ $event->title }}</td> --}}
                                <td>
                                    <a href="{{ route('contributions.create', [$event->id]) }}">
                                        {{ $event->title }}
                                    </a>
                                </td>
                                <td>{{ $event->description }}</td>
                                <td>{{ $event->faculty->name ?? '' }}</td>
                                <td>{{ $event->start_date }}</td>
                                <td>{{ $event->final_date }}</td>
                                <td>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $event->id }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('events.edit', [$event->id]) }}">
                                        <i class="fa-sharp fa-light fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{ $event->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('events.destroy', [$event->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this event?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7">No events to display</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
