@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-center mt-4">
        <button class="btn btn-primary" id="space">Espaces</button>
        <button class="btn btn-primary mx-3" id="task">Tâches</button>
        <button class="btn btn-primary" id="meeting">Réunions</button>
    </div>
    <div id="div-space">
        <table class="table table-dark table-striped my-5">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Nom de l'espace</th>
                    <th>Nom de la tâche</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody class="text-center align-middle">
                @php
                    $i = 1;   
                @endphp
                @foreach($spaces as $space)        
                    <tr>
                        @if($space->trashed())    
                            <td>{{ $i++ }}</td>
                            <td>{{ $space->space_name }}</td>
                            <td>
                                @php
                                    $tasksForSpace = $tasks->where('space_id', $space->id);
                                @endphp
                                @if($tasksForSpace->isEmpty())
                                    Pas de tâche pour {{ $space->space_name }}
                                @else
                                    @foreach($tasksForSpace as $task)
                                        {{ $task->task_name }} <br>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <i class="fa-solid fa-trash text-danger delete_spaces fs-4 pointer" data-id="{{ $space->id }}"></i>
                                <i class="fa-solid fa-reply text-success restore_space fs-4 pointer ms-2" data-id="{{ $space->id }}"></i>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="div-task" style="display:none">
        <table class="table table-dark table-striped my-5">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Nom de la tâche</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody class="text-center align-middle">
                @php
                    $i = 1;   
                @endphp
                @foreach($allTasks as $task)        
                    <tr>
                        @if($task->trashed())    
                            <td>{{ $i++ }}</td>
                            <td>{{ $task->task_name }}</td>
                            <td>
                                <i class="fa-solid fa-trash text-danger delete_task fs-4 pointer" data-id="{{ $task->id }}"></i>
                                <i class="fa-solid fa-reply text-success restore_task fs-4 pointer ms-2" data-id="{{ $task->id }}"></i>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="div-meeting" style="display:none">
        <table class="table table-dark table-striped my-5">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Nom de la réunion</th>
                    <th>Objet de la réunion</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody class="text-center align-middle">
                @php
                    $i = 1;   
                @endphp
                @foreach($meetings as $meeting)        
                    <tr>
                        @if($meeting->trashed())    
                            <td>{{ $i++ }}</td>
                            <td>{{ $meeting->meeting_name }}</td>
                            <td>{{ $meeting->meeting_object }}</td>
                            <td>
                                <i class="fa-solid fa-trash text-danger delete_meeting fs-4 pointer" data-id="{{ $meeting->id }}"></i>
                                <i class="fa-solid fa-reply text-success restore_meeting fs-4 pointer ms-2" data-id="{{ $meeting->id }}"></i>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#space').on('click', function () {
            $('#div-task').hide();
            $('#div-meeting').hide();
            $('#div-space').show();
        });
        $('#task').on('click', function () {
            $('#div-task').show();
            $('#div-meeting').hide();
            $('#div-space').hide();
        });
        $('#meeting').on('click', function () {
            $('#div-task').hide();
            $('#div-space').hide();
            $('#div-meeting').show();
        });
        $('.delete_spaces').on('click', function(){
            let spaceId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous supprimer cet espace ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Supprimer'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/delete-space/' + spaceId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data){
                            Swal.fire({
                                icon: 'success',
                                html: data.message
                            }).then((res) =>{
                                window.location.href = 'archives';
                            })
                        },
                        error: function(error){
                            console.error('Error:', error);
                        }
                    });
                }
            });
        })
        $('.delete_task').on('click', function(){
            let taskeId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous supprimer cete tâche ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Supprimer'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/delete-task/' + taskeId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data){
                            Swal.fire({
                                icon: 'success',
                                html: data.message
                            }).then((res) =>{
                                window.location.href = 'archives';
                            })
                        },
                        error: function(error){
                            console.error('Error:', error);
                        }
                    });
                }
            });
        })
        $('.delete_meeting').on('click', function(){
            let meetingeId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous supprimer cete réunion ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Supprimer'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/delete-meeting/' + meetingeId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data){
                            Swal.fire({
                                icon: 'success',
                                html: data.message
                            }).then((res) =>{
                                window.location.href = 'archives';
                            })
                        },
                        error: function(error){
                            console.error('Error:', error);
                        }
                    });
                }
            });
        })
        $('.restore_task').on('click', function(){
            let taskId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous restaurer cette tâche ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Restaurer'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/restore-task/' + taskId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data){
                            Swal.fire({
                                icon: 'success',
                                html: data.message
                            }).then((res) =>{
                                window.location.href = 'archives';
                            })
                        },
                        error: function(error){
                            console.error('Error:', error);
                        }
                    });
                }
            });
        });
        $('.restore_space').on('click', function(){
            let spaceId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous restaurer cet espace ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Restaurer'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/restore-space/' + spaceId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data){
                            Swal.fire({
                                icon: 'success',
                                html: data.message
                            }).then((res) =>{
                                window.location.href = 'archives';
                            })
                        },
                        error: function(error){
                            console.error('Error:', error);
                        }
                    });
                }
            });
        });
        $('.restore_meeting').on('click', function(){
            let meetingId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous restaurer cette réunion ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Restaurer'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/restore-meeting/' + meetingId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data){
                            Swal.fire({
                                icon: 'success',
                                html: data.message
                            }).then((res) =>{
                                window.location.href = 'archives';
                            })
                        },
                        error: function(error){
                            console.error('Error:', error);
                        }
                    });
                }
            });
        });
    });
</script>
@endsection