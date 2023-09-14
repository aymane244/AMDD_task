<!-- Modal -->
<div class="modal fade" id="edit_all_task" tabindex="-1" aria-labelledby="edit_all_taskLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="edit_all_taskLabel">Création de la tâche</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="loading_edit text-center my-5" style="display: none;">
                    <h5><i class="fa fa-spinner fa-spin me-2"></i> Charegement...</h5>
                </div>
                <form action="" method="post" id="update_all_data" class="show_data" style="display: none;">
                    <input id="all_task_id" type="hidden" class="form-control" name="all_task_name">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <label for="all_task_name" class="col-form-label text-md-end">Tâche</label>
                            <input id="all_task_name" type="text" class="form-control" name="all_task_name">
                            <span class="task_name text-danger"></span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="all_user_comittee" class="col-form-label text-md-end">Assignement</label>
                            <select name="all_user_comittee" id="all_user_comittee" class="form-select" aria-label="Comité select"></select>
                        </div>
                        <div class="col-md-4 mb-3" id="result_committee" style="display:none"></div>
                        <div class="col-md-4 mb-3">
                            <label for="begin_date_all" class="col-form-label text-md-end">Date début</label>
                            <input id="begin_date_all" type="date" class="form-control" name="begin_date_all" disabled>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="finish_date_all" class="col-form-label text-md-end">Date fin provisionnelle</label>
                            <input id="finish_date_all" type="date" class="form-control" name="finish_date_all">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="task_duration_all" class="col-form-label text-md-end">Durée provisionnelle</label>
                            <input id="task_duration_all" type="text" class="form-control" name="task_duration_all" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="task_prirority_all" class="col-form-label text-md-end">Priorité</label>
                            <select name="task_prirority_all" id="task_prirority_all" class="form-select" aria-label="Priorité select"></select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="task_state_all" class="col-form-label text-md-end">Priorité</label>
                            <select name="task_state_all" id="task_state_all" class="form-select" aria-label="Etat select"></select>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Editer la tâche</button>
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
        $('#edit_all_task').on('show.bs.modal', function (event){
            $('.loading_edit').show();
            $('.show_data').hide();
            let button = $(event.relatedTarget);
            taskId = button.data('id');
            $('#all_task_id').val(taskId)
            $.ajax({
                url: '/get-all-tasks/' + taskId,
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    $('#all_task_name').val(data.task.task_name);
                    $('#begin_date_all').val(data.task.task_begin_date);
                    $('#finish_date_all').val(data.task.task_finish_date);
                    let user_id = parseInt(data.task.user_id)
                    let user_name = data.task.user.first_name + " " + data.task.user.last_name
                    let priority = data.task.task_priority === null ? '---Veuillez choisir une priorité---' : data.task.task_priority;
                    let priority_value = data.task.task_priority === null ? '' : data.task.task_priority;
                    let selectElementPriority = $('#task_prirority_all');
                    let selectElementState = $('#task_state_all');
                    selectElementPriority.html('<option value="'+  priority_value +'">'+  priority +'</option>' +
                        (priority === 'Urgente' ? '' : '<option value="Urgente">Urgente</option>') +
                        (priority === 'Elevé' ? '' : '<option value="Elevé">Elevé</option>') +
                        (priority === 'Normal' ? '' : '<option value="Normal">Normal</option>') +
                        (priority === 'Basse' ? '' : '<option value="Basse">Basse</option>')
                    );
                    selectElementState.html('<option value="'+  data.task.task_state +'">'+  data.task.task_state +'</option>' +
                        (data.task.task_state === 'En cours' ? '' : '<option value="En cours">En cours</option>') +
                        (data.task.task_state === 'Achevé' ? '' : '<option value="Achevé">Achevé</option>') +
                        (data.task.task_state === 'Non achevé' ? '' : '<option value="Non achevé">Non achevé</option>')
                    );
                    $('#begin_date_all, #finish_date_all').on('change', function(){
                        let beginDate = $('#begin_date_all').val();
                        let finishDate = $('#finish_date_all').val();
                        if(beginDate && finishDate){
                            let daysDiff = Math.floor((Date.parse(finishDate) - Date.parse(beginDate)) / (24 * 60 * 60 * 1000));
                            $('#task_duration_all').val(daysDiff + ' days');
                        }
                    });
                    $('#begin_date_all, #finish_date_all').trigger('change');
                    $.ajax({
                        url: '/get-users/',
                        method: 'GET',
                        dataType: 'json',
                        success: function(data){
                            let selectElementCommittee = $('#all_user_comittee');
                            selectElementCommittee.empty();
                            $.each(data.users, function(key, user){
                                let fullName = user.first_name + " " + user.last_name;
                                selectElementCommittee.append($('<option>', {
                                    value: user.id === user_id ? user_id : user.id,
                                    text: user.id === user_id ? user_name : fullName,
                                    selected : user.id === user_id ? true : false
                                }));
                            })
                            $('#all_user_comittee').on('change', function(event){
                                let user_id = $(this).val();
                                $.ajax({
                                    url: '/get-committee/' + user_id,
                                    method: 'GET',
                                    dataType: 'json',
                                    success: function(data){
                                        $('.loading_edit').hide();
                                        $('#result_committee').show()
                                        if(data.error){
                                            $('#result_committee').html(data.error)
                                        }else if(data.committee){
                                            $('#result_committee').html('<label for="comittee" class="col-form-label text-md-end">Commitée</label>' +
                                            '<select name="comittee" id="comittee" class="form-select" aria-label="Comité select" disabled>' +
                                                '<option value="'+ data.committee.id +'">'+ data.committee.name +'</option></select>'
                                                )
                                            }
                                        $('.show_data').show();
                                    },
                                    error: function(error){
                                        console.error('Error:', error);
                                    }
                                });
                            })
                            $("#all_user_comittee").trigger('change');
                        },
                        error: function(error){
                            $('.loading').hide();
                            console.error('Error:', error);
                        }
                    });
                },
                error: function(error){
                    console.error('Error:', error);
                }
            });
        });
        $("#update_all_data").submit(function(e){ 
            e.preventDefault();
            let taskName = $("#all_task_name").val();
            let userCommittee = $("#all_user_comittee").val();
            let finishDate = $("#finish_date_all").val();
            let taskPriority = $("#task_prirority_all").val();
            let taskState = $("#task_state_all").val();
            let taskId = $("#all_task_id").val();
            let formData = {
                task_name: taskName,
                task_user: userCommittee,
                task_date: finishDate,
                task_priority: taskPriority,
                task_state: taskState,
                task_id: taskId,
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('update_all_tasks') }}",
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