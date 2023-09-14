<!-- Modal -->
<div class="modal fade" id="edit_meeting" tabindex="-1" aria-labelledby="edit_meetingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="edit_meetingLabel">Création de la réunion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="update_meeting">
                    <input type="hidden" name="meeting_id" id="meeting_id">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meeting_edit_name" class="col-form-label text-md-end">Nom de la Réunion</label>
                                    <input id="meeting_edit_name" type="text" class="form-control" name="meeting_edit_name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meeting_edit_object" class="col-form-label text-md-end">Objet Réunion</label>
                                    <input id="meeting_edit_object" type="text" class="form-control" name="meeting_edit_object">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meeting_edit_date" class="col-form-label text-md-end">Date de la Réunion</label>
                                    <input id="meeting_edit_date" type="datetime-local" class="form-control" name="meeting_edit_date">
                                    <span class="meeting_edit_date text-danger"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meeting_edit_link" class="col-form-label text-md-end">Lien de Réunion</label>
                                    <input id="meeting_edit_link" type="text" class="form-control" name="meeting_edit_link">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 mb-3">
                            <table class="table table-striped">
                                <thead class="text-center">
                                    <tr>
                                        <th>
                                            <div class="d-flex">
                                                <div class="me-2">
                                                    <input 
                                                        type="checkbox" 
                                                        id="member_header"
                                                        class="form-check-input member_header"
                                                    />
                                                </div>
                                                <div>
                                                    Membre
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center align-middle" id="ftech_meeting_user">
                                    
                                </tbody>
                            </table>
                            <span class="user_invitations text-danger"></span>
                        </div>
                        <div class="col-md-10 text-center">
                            <button type="submit" class="btn btn-primary">Editer Réunion</button>
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
        let inputLength;
        $('.edit_meeting').on('click', function(){
            let meetingId = $(this).data('id');
            $('#meeting_id').val(meetingId);
            $.ajax({
                url: '/get-meeting/' + meetingId,
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    let datetimeFetched = data.meeting.meeting_date;
                    let datetime = new Date(datetimeFetched);
                    let date = datetime.toISOString().split('T')[0];    
                    let time = datetime.toTimeString().split(' ')[0];
                    let hours = parseInt(time.split(':')[0]) - 1;
                    let minutes = time.split(':')[1];
                    let formattedHours = hours < 10 ? '0' + hours : hours.toString();
                    let formattedMinutes = minutes < 10 ? '0' + minutes : minutes.toString();
                    let formattedDatetime = date + ' ' + formattedHours  + ':' + formattedMinutes;
                    $('#meeting_edit_name').val(data.meeting.meeting_name)
                    $('#meeting_edit_object').val(data.meeting.meeting_object)
                    $('#meeting_edit_date').val(formattedDatetime)
                    $('#meeting_edit_link').val(data.meeting.meeting_link)
                    let tableHtml = '';
                    $.each(data.users, function(key, user){
                        let isChecked = data.meeting.users.some(meetingUser => meetingUser.id === user.id);
                        let checkedAttribute = isChecked ? 'checked' : '';
                        tableHtml +='<tr class="spacing-tr">' + 
                        '<td><div class="d-flex"><div class="me-2">'+
                        '<input type="checkbox" value="'+user.id +'" name="user_edit_invitations[]" id="user_edit_invitations" class="form-check-input check_member" ' + checkedAttribute + '/>' +
                        '</div><div>'+ user.first_name +' '+ user.last_name +'<br></div></div></td>';
                    })
                    inputLength = $('.check_member').length;
                    $('#ftech_meeting_user').html(tableHtml);
                    if(($('.check_member:checked').length) ===  inputLength){
                        $('.member_header').prop('checked', true)
                    }else{
                        $('.member_header').prop('checked', false)
                    }
                },
                error: function(error){
                    console.error('Error:', error);
                }
            });
        })
        $(document).on('click', '.check_member', function(){
            if(($('.check_member:checked').length) ===  inputLength){
                $('.member_header').prop('checked', true)
            }else{
                $('.member_header').prop('checked', false)
            }
        })
        $('.member_header').on('click', function(){
            if($(this).is(':checked')){
                $('.check_member').prop('checked', true)
            }else{
                $('.check_member').prop('checked', false)
            }
        })
        $("#update_meeting").submit(function(e){ 
            e.preventDefault();
            let meetingId = $("#meeting_id").val();
            let meetingName = $("#meeting_edit_name").val();
            let meetingObject = $("#meeting_edit_object").val();
            let meetingDate = $("#meeting_edit_date").val();
            let meetingLink = $("#meeting_edit_link").val();
            let selectedMembers = [];
            $('.check_member:checked').each(function() {
                selectedMembers.push($(this).val());
            });
            let formData = {
                meeting_id: meetingId,
                meeting_name: meetingName,
                meeting_object: meetingObject,
                meeting_date: meetingDate,
                meeting_link: meetingLink,
                user_invitations: selectedMembers,
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('update_meeting') }}",
                dataType: "json",
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        html: response.message
                    }).then((result) =>{
                        if(result.isConfirmed){
                            window.location.href = 'meetings';
                        }
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        html: error.message
                    })
                }
            });
        });
    })
</script>