<div class="py-5">
    <h5 class="text-center"><u>{{ Auth::user()->is_admin === 1 ? "Tous l'espaces" : 'Vos espaces' }}</u></h5>
    <div class="mt-3">
        @if(Auth::user()->is_admin === 1)
            @include('spaces.admin')
        @else
            @include('spaces.user')
        @endif
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.font-icons').on('mouseover', function(){
            let index = $(this).data('index');
            $('.font-div_'+index).show();
        })
        $('.font-icons').on('mouseleave', function(){
            let index = $(this).data('index');
            $('.font-div_'+index).hide();
        })
        $('.area-collapse').on('click', function(){
            let index = $(this).data('index');
            $('.loading_all_task_'+index).show();
            $('.arrow-details_'+index).show();
            $('.task-space_'+index).hide();
            $(this).hide();
            let spaceId = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let formData = {
                space_id: spaceId,
            };
            $.ajax({
                type: "POST",
                url: "{{ route('show_task') }}",
                data: formData,
                success: function(response){
                    $('.loading_all_task_'+index).hide();
                    if(response.error){
                        $(".show_results_error_"+index).html('<p class="ps-5">Pas de tâche en ce moment</p>');
                    }else{
                        let tasksHtmlAdmin = '';
                        let tasksHtmlUser = '';
                        let admin = {{ Auth::user()->is_admin }};
                        if(admin === 1){
                            $.each(response.task_admin, function(key, task){
                                let currentDate = new Date();
                                let year = currentDate.getFullYear();
                                let month = currentDate.getMonth() + 1;
                                let day = currentDate.getDate();
                                let formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                                tasksHtmlAdmin += '<div class="pointer bg-space py-1 ps-5 d-flex justify-content-between font-icons-tasks" data-id="'+task.id+'" data-index="'+key+'">'+
                                    '<div class="d-flex align-items-center show_tasks" data-id="'+task.id+'">'+task.task_name+'</div>' +
                                    '<div class="font-div-task_'+task.id+' pe-2" style="display:none">'+
                                        (formattedDate <= task.task_finish_date && task.task_state !== "Achevé" ? '<i class="fa-solid fa-edit text-success me-2" data-bs-toggle="modal" data-bs-target="#edit_all_task" data-id="'+task.id+'"></i>' : '<i class="fa-solid fa-folder-open text-info archive_task me-2" data-id="'+task.id+'"></i>' ) +
                                        '<i class="fa-solid fa-trash text-danger delete_task" data-id="'+task.id+'"></i>' +
                                    '</div>'+
                                '</div>'
                            });
                        }else{
                            $.each(response.task_user, function(key, task){
                                tasksHtmlUser += '<div class="show_tasks pointer bg-space py-1 ps-5 d-flex justify-content-between font-icons-tasks" data-id="'+task.id+'" data-index="'+key+'">'+
                                    task.task_name+
                                '</div>'
                            });
                        }
                        (admin === 1 ?  $(".show_results_"+index).html(tasksHtmlAdmin) : $(".show_results_"+index).html(tasksHtmlUser))
                    }
                    $('.task-space_'+index).show();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
        $('.details').on('click', function(){
            let index = $(this).data('index');
            $('.arrow-collapse_'+index).show();
            $('.task-space_'+index).hide();
            $(this).hide();
        });
        $(document).on('mouseover', '.font-icons-tasks', function(){
            let id = $(this).data('id');
            $('.font-div-task_'+id).show();
        })
        $(document).on('mouseleave', '.font-icons-tasks', function(){
            let id = $(this).data('id');
            $('.font-div-task_'+id).hide();
        })
    });
</script>
@include('tasks.edit')
@include('tasks.edit-all-tasks')
@include('tasks.delete')
@include('tasks.archive')