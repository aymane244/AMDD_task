@extends('layouts.app')

@section('content')
<div class="container">
    <div class="my-5 text-center">
        <h2>Bonjour, {{ Auth::user()->first_name.' '. Auth::user()->last_name}}</h2>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="box1 rounded shadow d-flex align-items-center px-3 justify-content-between">
                <div class="text-center">
                    <h5 class="text-white">Total Tâches</h5>
                    <h4 class="text-white"> {{ $all_tasks }} </h4>
                </div>
                <img src="{{ asset('../images/businessman_2.png') }}" alt="tasks_image" class="img-fluid w-50">
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="box1 rounded shadow d-flex align-items-center px-3 justify-content-between">
                <div class="text-center">
                    <h5 class="text-white">Tâches en cours</h5>
                    <h4 class="text-white"> {{ $tasks_pending }} </h4>
                </div>
                <img src="{{ asset('../images/businessman_2.png') }}" alt="tasks_image" class="img-fluid w-50">
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="box1 rounded shadow d-flex align-items-center px-3 justify-content-between">
                <div class="text-center">
                    <h5 class="text-white">Tâches terminées</h5>
                    <h4 class="text-white"> {{ $tasks_finished }} </h4>
                </div>
                <img src="{{ asset('../images/app.png') }}" alt="tasks_image" class="img-fluid w-50">
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5 rounded shadow bg-white mb-3 p-3 me-lg-4">
            <h5 class="text-center">Total Membres : {{ $all_users }}</h5>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex">
                    @foreach ($users as $user)
                        @if($user->image === null)
                            <p class="bg-danger text-white rounded-circle respoimages d-flex align-items-center justify-content-center fs-4" style="margin-right: -0.4rem;">{{ substr($user->first_name, 0, 1) }},{{ substr($user->last_name, 0, 1) }}</p>
                        @else
                            <img src="{{ asset('storage/'.$user->image)}}" alt="profil-image" class="respoimages rounded-circle">
                        @endif
                    @endforeach
                    @if($all_users > 1 && $count !== 0)
                        <p class="bg-danger text-white rounded-circle respoimages d-flex align-items-center justify-content-center fs-4" style="margin-right: -0.4rem;">+{{ $count }}</p>
                    @endif
                </div>
                <div>
                    <img src="{{ asset('../images/home.png') }}" alt="tasks_image" class="img-fluid" style="width:200px">
                </div>
            </div>
        </div>
        <div class="col-md-5 rounded shadow bg-white mb-3 p-3">
            <h5 class="text-center">Total Réunions : {{ $all_meetings }}</h5>
            @foreach ($meetings as $meeting)
                <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background-color: #E4E9F6">
                    <div class="d-flex rounded align-items-center">
                        <p class="bg-danger text-white respoimages d-flex align-items-center justify-content-center fs-4">{{ substr($meeting->meeting_name, 0, 1) }}</p>
                        <p class="ms-2 fs-6">
                            {{ $meeting->meeting_name }} <br>
                            {{ __('days.'.$meeting->meeting_date->format('D')) }},
                            {{$meeting->meeting_date->format('d')}}
                            {{__('months.'.$meeting->meeting_date->format('F'))}}
                            {{$meeting->meeting_date->format('Y H:i')}}
                        </p>
                    </div>
                    <p><a href="{{ $meeting->meeting_link }}" target="_blank">Lien de réunion</a></p>
                </div>
            @endforeach
        </div>
    </div>
    <div>
        <canvas id="myChart"></canvas>
    </div>
    @foreach ($spaces as $space)
        @php
            $space_name[] = $space->space_name;
            $task_count[] = $space->tasks_count;
            $space_name_json = json_encode($space_name);
            $task_count_json = json_encode($task_count);
        @endphp
    @endforeach
</div>
<script>
    $(document).ready(function(){
        let chart = $('#myChart');
        new Chart(chart,{
            type: 'bar',
            data:{
                labels: {!! $space_name_json !!},
                datasets: [{
                label: '# des espaces',
                data: {!! $task_count_json !!},
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection