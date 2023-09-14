@extends('layouts.app')

@section('content')
<div class="d-flex" style="height: 100vh;">
    <div class="border-end w-25 scroll-div h-100" style="height: 100vh; max-height: 100vh;">
        @include('spaces.index')
    </div>
    <div class="container">
        <div class="loading text-center centered" style="display: none;">
            <h5><i class="fa fa-spinner fa-spin me-2"></i> Charegement...</h5>
        </div>
        <div class="spaces mx-auto w-100" style="display: none">
            <div class="show_space_results"></div>
        </div>
        <div class="tasks">
            <div class="loading_task text-center centered" style="display: none;">
                <h5><i class="fa fa-spinner fa-spin me-2"></i> Charegement...</h5>
            </div>
            <div class="show_tasks_results"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        let currentDate = new Date();
        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1;
        let day = currentDate.getDate();
        let formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        $('.show_space').on('click', function(){
            $('.loading').show();
            $('.spaces').show();
            $('.tasks').hide();
            let spaceId = $(this).data('id');
            const formData = {
                space_id: spaceId
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('show_spaces') }}",
                data: formData,
                success: function(response){
                    $('.loading').hide();
                    let spacesHtml = '';
                    let beginDate = $('#begin_date').val();
                    let finishDate = $('#finish_date').val();
                    let admin = {{ Auth::user()->is_admin }};
                    let user_auth = {{ Auth::user()->id }};
                    if(response.tasks.length > 0){
                        console.log(response);
                        spacesHtml += "<u><h5 class='mt-3'>"+ response.spaceName.space_name +"</h5></u>";
                        spacesHtml += "<h5 class='mt-3 d-flex'><span class='me-1'>Description:</span>"+ response.spaceName.description +" </h5>";
                        spacesHtml += "<h4 class='text-center mt-3'>Total tâches: " + (admin === 1 ? response.taskCountAll : response.taskCountUser) + "</h4>";
                        $.each(response.tasks, function(key, task){
                            if(admin === 1){
                                let userName = task.user ? task.user.first_name + ' ' + task.user.last_name : 'N/A';
                                let daysDiff = Math.floor((Date.parse(task.task_finish_date) - Date.parse(task.task_begin_date)) / (24 * 60 * 60 * 1000));
                                spacesHtml += '<div class="border rounded mt-4"><table class="text-center"><thead><tr class="spacing-tr">'+
                                '<td>Tâche</td>'+
                                '<td>Assingé</td>' +
                                '<td>Date fin</td>'+
                                '<td>Durée</td>'+
                                '<td>Priorité</td>'+
                                '<td>Etat</td>'+
                                '</tr></thead><tbody><tr class="spacing-tr">' + 
                                '<td>'+task.task_name+(formattedDate <= task.task_finish_date && task.task_state !== "Achevé" ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-task="'+task.task_name+'_'+task.id+'"></i>' : '')+'</td>' + 
                                '<td>'+userName+ (formattedDate <= task.task_finish_date && task.task_state !== "Achevé" ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-user="'+task.user_id+'_'+task.id+ '_' + userName +'"></i>' : '') + '</td>' +
                                '<td>'+task.task_finish_date+(admin === 1 && formattedDate <= task.task_finish_date && task.task_state !== "Achevé"  ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-date="'+task.task_finish_date+'_'+task.id+'"></i>' : '') +'</td>' + 
                                '<td>'+ (daysDiff < 0 ? 'Délai dépassé' : daysDiff === 1 ? '1 jour' : daysDiff + ' jours') +'</td>' + 
                                '<td>' + (task.task_priority === null ? 'Pas de priorité' : task.task_priority) + (formattedDate <= task.task_finish_date && task.task_state !== "Achevé" ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-priority="'+task.task_priority+'_'+task.id+'"></i>' : '') +'</td>' +
                                '<td>' + task.task_state + (formattedDate <= task.task_finish_date ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-state="'+task.task_state+'_'+task.id+'"></i>' : '') +'</td>' +
                                '</tr></tbody></table></div>';
                            }else{
                                if(task.user_id === user_auth){
                                    let userName = task.user ? task.user.first_name + ' ' + task.user.last_name : 'N/A';
                                    let daysDiff = Math.floor((Date.parse(task.task_finish_date) - Date.parse(task.task_begin_date)) / (24 * 60 * 60 * 1000));
                                    spacesHtml += '<div class="border rounded mt-4"><table class="text-center"><thead><tr class="spacing-tr">'+
                                    '<td>Tâche</td>'+
                                    '<td>Date fin</td>'+
                                    '<td>Durée</td>'+
                                    '<td>Priorité</td>'+
                                    '<td>Etat</td>'+
                                    '</tr></thead><tbody><tr class="spacing-tr">' + 
                                    '<td>'+task.task_name+'</td>' + 
                                    '<td>'+task.task_finish_date+'</td>' + 
                                    '<td>'+ (daysDiff < 0 ? 'Délai dépassé' : daysDiff === 1 ? '1 jour' : daysDiff + ' jours') +'</td>' + 
                                    '<td>' + (task.task_priority === null ? 'Pas de priorité' : task.task_priority) + '</td>' +
                                    '<td>' + task.task_state + (formattedDate <= task.task_finish_date ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-state="'+task.task_state+'_'+task.id+'"></i>' : '') +'</td>' +
                                    '</tr></tbody></table></div>';
                                }
                            }
                        })
                        $(".show_space_results").html(spacesHtml);
                    }else{
                        $(".show_space_results").html("<div class='centered'><h2 class='text-body-tertiary text-center'>Pas de tâche pour cet espace pour le moment</h2></div>");
                    }
                },
                error: function(xhr, status, error){
                    $('.loading').hide();
                    console.error(error);
                }
            });
        });
        $('.show_space_0').trigger('click')
        $(document).on('click', '.show_tasks', function(){
            $('.loading_task').show();
            $('.spaces').hide();
            $('.tasks').show();
            let taskId = $(this).data('id');
            const formData = {
                task_id: taskId
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('show_tasks') }}",
                data: formData,
                success: function(response){
                    $('.loading_task').hide();
                    let spacesHtml = '';
                    let beginDate = $('#begin_date').val();
                    let finishDate = $('#finish_date').val();
                    let admin = {{ Auth::user()->is_admin }};
                    if(response.error){
                        $(".show_tasks_results").html("<div class='centered'><h2 class='text-body-tertiary text-center'>Pas de tâche pour cet espace pour le moment</h2></div>");
                    }else{
                        $.each(response, function(key, task){
                            let userName = task.user ? task.user.first_name + ' ' + task.user.last_name : 'N/A';
                            let daysDiff = Math.floor((Date.parse(task.task_finish_date) - Date.parse(task.task_begin_date)) / (24 * 60 * 60 * 1000));
                            spacesHtml += '<div class="border rounded mt-4"><table class="text-center"><thead><tr class="spacing-tr">'+
                            '<td>Tâche</td>'+
                            (admin === 1 ? '<td>Assingé</td>' : '' ) +
                            '<td>Date fin</td>'+
                            '<td>Durée</td>'+
                            '<td>Priorité</td>'+
                            '<td>Etat</td>'+
                            '</tr></thead><tbody><tr class="spacing-tr">' + 
                            '<td>'+task.task_name+(admin === 1 && formattedDate <= task.task_finish_date && task.task_state !== "Achevé" ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-task="'+task.task_name+'_'+task.id+'"></i>' : '')+'</td>' + 
                            (admin === 1 ? '<td>'+userName+ (formattedDate <= task.task_finish_date && task.task_state !== "Achevé" ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-user="'+task.user_id+'_'+task.id+ '_' + userName +'"></i>' : '') + '</td>' : '' ) +
                            '<td>'+task.task_finish_date+(admin === 1 && formattedDate <= task.task_finish_date && task.task_state !== "Achevé" ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-date="'+task.task_finish_date+'_'+task.id+'"></i>' : '') +'</td>' + 
                            '<td>'+ daysDiff + (daysDiff === 1 ? ' jour' : ' jours') +'</td>' + 
                            '<td>' +(task.task_priority === null ? 'Pas de priorité' : task.task_priority) + (admin === 1 && formattedDate <= task.task_finish_date && task.task_state !== "Achevé" ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-priority="'+task.task_priority+'_'+task.id+'"></i>' : '') +'</td>' +
                            '<td>' + task.task_state + (formattedDate <= task.task_finish_date ? '<i class="fa-solid fa-edit text-success ms-2 pointer" data-bs-toggle="modal" data-bs-target="#edit_task" data-state="'+task.task_state+'_'+task.id+'"></i>' : '') +'</td>' +
                            '</tr></tbody></table></div>';
                        })
                        $(".show_tasks_results").html(spacesHtml);
                    }
                },
                error: function(xhr, status, error){
                    $('.loading').hide();
                    console.error(error);
                }
            });
        });
    });
</script>
@endsection