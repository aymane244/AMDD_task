<!-- Modal -->
<div class="modal fade" id="edit_task" tabindex="-1" aria-labelledby="edit_taskLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="edit_taskLabel">Editer <u><span id="title"></span></u></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="update_data">
                    <div class="row justify-content-center">
                        <input type="hidden" id="task_id_input" name="space_id">
                        <div class="col-md-12 mb-3" id="task_values"></div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Modifier la tâche</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('show.bs.modal', '#edit_task', function (event){
            let button = $(event.relatedTarget);
            let taskName = button.data('task');
            let taskUser = button.data('user');
            let taskDate = button.data('date');
            let taskPrio = button.data('priority');
            let taskState = button.data('state');
            let id = '';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if(taskUser === undefined && taskDate === undefined && taskPrio === undefined && taskState === undefined && taskName !== undefined){
                $('#title').html("Tâche");
                let parts = taskName.split('_'); 
                id = parseInt(parts[1]);
                let task_name = parts[0];
                $('#task_values').html('<label for="task_name_update" class="col-form-label text-md-end">Tâche</label>' +
                    '<input id="task_name_update" type="text" class="form-control" name="task_name_update" value="'+task_name+'">'
                );
            }else if(taskName === undefined && taskDate === undefined && taskPrio === undefined && taskState === undefined && taskUser !== undefined){
                $('#title').html("Assingement");
                let parts = taskUser.split('_');
                id = parseInt(parts[1]);
                let task_user = parseInt(parts[0]);
                let full_name = parts[2];
                let selectHtml = '';
                $.ajax({
                    url: "{{ route('get_users') }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(data){
                        $.each(data.users, function(key, user){
                            if(task_user === user.id){
                                selectHtml += '<option value="' + task_user +  '" selected>' + full_name + '</option>';
                            }else{
                                selectHtml += '<option value="' + user.id +  '">' + user.first_name + ' ' + user.last_name + '</option>';
                            }
                        });
                        $('#task_values').html('<label for="task_user_update" class="col-form-label text-md-end">Assigenment</label>' +
                            '<select name="task_user_update" id="task_user_update" class="form-select" aria-label="Assigenment">' +
                                selectHtml +
                            '</select>'
                        );
                    },
                    error: function(error){
                        console.error('Error:', error);
                    }
                });
            }else if(taskName === undefined && taskUser === undefined && taskPrio === undefined && taskState === undefined && taskDate !== undefined){
                $('#title').html("Date de fin");
                let parts = taskDate.split('_');
                id = parseInt(parts[1]);
                let task_date = parts[0];
                $('#task_values').html('<label for="task_date_update" class="col-form-label text-md-end">Date fin</label>' +
                    '<input id="task_date_update" type="date" class="form-control" name="task_date_update" value="'+task_date+'">'
                );
            }else if(taskName === undefined && taskUser === undefined && taskDate === undefined && taskState === undefined && taskPrio !== undefined){
                $('#title').html("Priorité");
                let parts = taskPrio.split('_');
                id = parseInt(parts[1]);
                let task_prio = parts[0];
                $('#task_values').html('<label for="task_prio_update" class="col-form-label text-md-end">Priorité</label>' +
                    '<select name="task_prio_update" id="task_prio_update" class="form-select" aria-label="Priorité">' +
                        '<option value="'+  (task_prio === 'null' ? '' : task_prio) +'">'+  (task_prio === 'null' ? '---Veuillez choisir une priorité---' : task_prio) +'</option>' +
                        (task_prio === 'Urgente' ? '' : '<option value="Urgente">Urgente</option>') +
                        (task_prio === 'Elevé' ? '' : '<option value="Elevé">Elevé</option>') +
                        (task_prio === 'Normal' ? '' : '<option value="Normal">Normal</option>') +
                        (task_prio === 'Basse' ? '' : '<option value="Basse">Basse</option>') +
                    '</select>'
                );
            }else if(taskName === undefined && taskUser === undefined && taskDate === undefined && taskPrio === undefined && taskState !== undefined){
                $('#title').html("Etat");
                let parts = taskState.split('_');
                id = parseInt(parts[1]);
                let task_state = parts[0];
                $('#task_values').html('<label for="task_state_update" class="col-form-label text-md-end">Etat</label>' +
                    '<select name="task_state_update" id="task_state_update" class="form-select" aria-label="Etat">' +
                        '<option value="'+  task_state +'">'+  task_state +'</option>' +
                        (task_state === 'En cours' ? '' : '<option value="En cours">En cours</option>') +
                        (task_state === 'Achevé' ? '' : '<option value="Achevé">Achevé</option>') +
                        (task_state === 'Non achevé' ? '' : '<option value="Non achevé">Non achevé</option>') +
                    '</select>'
                );
            }
            $('#task_id_input').val(id);
        });
        $(document).on('submit', '#update_data',function(e){ 
            e.preventDefault();
            let id = $("#task_name").val();
            let taskName = $("#task_name_update").val();
            let taskUser = $("#task_user_update").val();
            let taskDate = $("#task_date_update").val();
            let taskPriority = $("#task_prio_update").val();
            let taskState = $("#task_state_update").val();
            let TaskId = $("#task_id_input").val();
            let formData = {
                task_name: taskName,
                task_user: taskUser,
                task_date: taskDate,
                task_priority: taskPriority,
                task_state: taskState,
                task_id: TaskId,
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('update_task') }}",
                data: formData,
                success: function(response){
                    Swal.fire({
                        icon: 'success',
                        html: response.message
                    }).then((result) =>{
                        if(result.isConfirmed){
                            window.location.href = 'space';
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    })
</script>