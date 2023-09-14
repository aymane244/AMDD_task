@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::user()->is_admin === 1)
        <div class="text-center my-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create_meeting">Créer une réunion</button>
        </div>
    @endif
    <table class="table table-dark table-striped my-5">
        <thead class="text-center">
            <tr>
                <th>#</th>
                <th>Réunion</th>
                <th>Objet</th>
                <th>Date</th>
                <th>Lien</th>
                <th>Invités</th>
                @if(Auth::user()->is_admin === 1)
                    <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-center align-middle">
            @foreach($meetings as $key => $meeting)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $meeting->meeting_name }}</td>
                    <td>{{ $meeting->meeting_object }}</td>
                    <td>
                        {{ __('days.'.$meeting->meeting_date->format('D')) }},
                        {{$meeting->meeting_date->format('d')}}
                        {{__('months.'.$meeting->meeting_date->format('F'))}}
                        {{$meeting->meeting_date->format('Y H:i')}}
                    </td>
                    <td> <a href="{{ $meeting->meeting_link }}" target="_blank">Lien de réunion</a></td>
                    <td>
                        @foreach($meeting->users as $user)
                            {{ $user->first_name }} {{ $user->last_name }}<br>
                        @endforeach
                    </td>
                    @if(Auth::user()->is_admin === 1)
                        <td>
                            <i class="fa-solid fa-edit text-success fs-4 pointer edit_meeting" data-bs-toggle="modal" data-bs-target="#edit_meeting" data-id="{{ $meeting->id }}"></i>
                            <i class="mx-3 fa-solid fa-trash text-danger delete_meeting fs-4 pointer" data-id="{{ $meeting->id }}"></i>   
                            <i class="fa-solid fa-folder-open text-info archive_meeting fs-4 pointer" data-id="{{ $meeting->id }}"></i>                 
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('meetings.add')
@include('meetings.edit')
@include('meetings.delete')
@include('meetings.archive')
@endsection