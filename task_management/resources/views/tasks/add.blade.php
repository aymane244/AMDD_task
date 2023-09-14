<!-- Modal -->
<div class="modal fade" id="create_task" tabindex="-1" aria-labelledby="create_taskLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="create_taskLabel">Création de la tâche</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="send_data">
                    <input type="hidden" id="space_id_input" name="space_id">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <label for="task_name" class="col-form-label text-md-end">Tâche</label>
                            <input id="task_name" type="text" class="form-control" name="task_name" value="{{ old('task_name') }}">
                            <span class="task_name text-danger"></span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="user_comittee" class="col-form-label text-md-end">Assignement</label>
                            <select name="user_comittee" id="user_comittee" class="form-select" aria-label="Comité select">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3" id="result" style="display:none"></div>
                        <div class="col-md-4 mb-3">
                            <label for="begin_date" class="col-form-label text-md-end">Date début</label>
                            <input id="begin_date" type="date" class="form-control" name="begin_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="task_name" class="col-form-label text-md-end">Date fin provisionnelle</label>
                            <input id="finish_date" type="date" class="form-control" name="finish_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="task_duration" class="col-form-label text-md-end">Durée provisionnelle</label>
                            <input id="task_duration" type="text" class="form-control" name="task_duration" disabled>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Crée la tâche</button>
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
        $('#create_task').on('show.bs.modal', function (event){
            let button = $(event.relatedTarget);
            let spaceId = button.data('id');
            $('#space_id_input').val(spaceId);
        });
        $('#user_comittee').on('change', function(event){
            let user_id =event.target.value
            $.ajax({
                url: '/get-committee/' + user_id,
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    if(data.error){
                        $('#result').html(data.error)
                    }else if(data.committee){
                        $('#result').show()
                        $('#result').html('<label for="comittee" class="col-form-label text-md-end">Commitée</label>' +
                            '<select name="comittee" id="comittee" class="form-select" aria-label="Comité select" disabled>' +
                            '<option value="'+ data.committee.id +'">'+ data.committee.name +'</option></select>'
                        )
                    }
                },
                error: function(error){
                    console.error('Error:', error);
                }
            });
        })
        $("#user_comittee").trigger('change');
        $('#begin_date, #finish_date').on('change', function(){
            let beginDate = $('#begin_date').val();
            let finishDate = $('#finish_date').val();
            if(beginDate && finishDate){
                let daysDiff = Math.floor((Date.parse(finishDate) - Date.parse(beginDate)) / (24 * 60 * 60 * 1000));
                $('#task_duration').val(daysDiff + ' days');
            }
        });
        $('#begin_date, #finish_date').trigger('change');
        $("#send_data").submit(function(e){ 
            e.preventDefault();
            let taskName = $("#task_name").val();
            let userCommittee = $("#user_comittee").val();
            let beginDate = $("#begin_date").val();
            let finishDate = $("#finish_date").val();
            let spaceId = $("#space_id_input").val();
            let formData = {
                task_name: taskName,
                user_comittee: userCommittee,
                begin_date: beginDate,
                finish_date: finishDate,
                space_id: spaceId,
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('store_task') }}",
                data: formData,
                success: function(response) {
                    if(response.errors){
                        $.each(response.errors, function(field, messages){
                            $("#" + field).addClass("is-invalid");
                            $("." + field).html(messages[0]);
                        });
                    }else{
                        Swal.fire({
                            icon: 'success',
                            html: response.message
                        }).then((result) =>{
                            if(result.isConfirmed){
                                window.location.href = 'space';
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    })
</script>