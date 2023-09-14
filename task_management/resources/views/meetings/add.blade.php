<!-- Modal -->
<div class="modal fade" id="create_meeting" tabindex="-1" aria-labelledby="create_meetingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="create_meetingLabel">Création de la réunion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="create_meetings">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meeting_name" class="col-form-label text-md-end">Nom de la Réunion</label>
                                    <input id="meeting_name" type="text" class="form-control" name="meeting_name" value="{{ old('meeting_name') }}">
                                    <span class="meeting_name text-danger"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meeting_object" class="col-form-label text-md-end">Objet Réunion</label>
                                    <input id="meeting_object" type="text" class="form-control" name="meeting_object" value="{{ old('meeting_object') }}">
                                    <span class="meeting_object text-danger"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meeting_date" class="col-form-label text-md-end">Date de la Réunion</label>
                                    <input id="meeting_date" type="text" class="form-control" name="meeting_date" value="{{ date('Y-m-d H:00') }}" min="{{ date('Y-m-d H:i') }}" step="900">
                                    <span class="meeting_date text-danger"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meeting_link" class="col-form-label text-md-end">Lien de Réunion</label>
                                    <input id="meeting_link" type="text" class="form-control" name="meeting_link" value="{{ old('meeting_link') }}">
                                    <span class="meeting_link text-danger"></span>
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
                                                        id="committee_header"
                                                        class="form-check-input committee_header"
                                                    />
                                                </div>
                                                <div>Committé</div>
                                            </div>
                                        </th>
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
                                <tbody class="text-center align-middle">
                                    @foreach($committes as $committee)
                                        <tr>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <input 
                                                            type="checkbox" 
                                                            name="committee" 
                                                            id="committee"
                                                            class="form-check-input check_committee committe_{{ $committee->id }}"
                                                            value="{{ $committee->id }}"
                                                        />
                                                    </div>
                                                    <div>
                                                        {{ $committee->name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>    
                                                @php
                                                    $committeeUser = $users->where('committee_id', $committee->id);
                                                @endphp
                                                @if(!$committeeUser->isEmpty())
                                                    @foreach($committeeUser as $member)
                                                        <div class="d-flex">
                                                            <div class="me-2">
                                                                <input 
                                                                    type="checkbox" 
                                                                    value="{{ $member->id }}"
                                                                    name="user_invitations[]" 
                                                                    id="user_invitations"
                                                                    class="form-check-input check_member_{{ $committee->id }} check_member"
                                                                    data-committee-id="{{ $committee->id }}"
                                                                />
                                                            </div>
                                                            <div>
                                                                {{ $member->first_name.' '.$member->last_name }} <br>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <span class="user_invitations text-danger"></span>
                        </div>
                        <div class="col-md-10 text-center">
                            <button type="submit" class="btn btn-primary">Envoyer invitation</button>
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
        const datetimeInput = $('#meeting_date');
        datetimeInput.prop('type', 'datetime-local');
        datetimeInput.on('input', function (){
            // Parse the input value
            const datetimeValue = new Date(this.value);
            // Check if the selected minutes are not 00 or 30
            if(datetimeValue.getMinutes() !== 0 && datetimeValue.getMinutes() !== 30){
                // Set the minutes to 00
                datetimeValue.setMinutes(0);
                // Update the input value with the modified datetime
                this.value = datetimeValue.toISOString().slice(0, 16);
            }
        });
        $('.committee_header').on('click', function(){
            if($('.committee_header').is(':checked')){
                $('.check_committee').prop('checked', true)
                $('.check_member').prop('checked', true)
                $('.member_header').prop('checked', true)
            }else{
                $('.check_committee').prop('checked', false)
                $('.check_member').prop('checked', false)
                $('.member_header').prop('checked', false)
            }
        })
        $('.member_header').on('click', function(){
            if($('.member_header').is(':checked')){
                $('.check_committee').prop('checked', true)
                $('.check_member').prop('checked', true)
                $('.committee_header').prop('checked', true)
            }else{
                $('.check_committee').prop('checked', false)
                $('.check_member').prop('checked', false)
                $('.committee_header').prop('checked', false)
            }
        })
        $('.check_committee').on('click', function(){
            let committeeId = $(this).val(); 
            if($('.committe_'+committeeId).is(':checked')){
                $('.check_member_'+ committeeId).prop('checked', true)
            }else{
                $('.check_member_'+ committeeId).prop('checked', false)
            }
            if(($('.check_committee:checked').length) ===  $('.check_committee').length){
                $('.committee_header').prop('checked', true)
                $('.member_header').prop('checked', true)
            }else{
                $('.committee_header').prop('checked', false)
                $('.member_header').prop('checked', false)
            }
        })
        $('.check_member').on('click', function(){
            let committeeId = $(this).data('committee-id'); 
            if(($('.check_member_'+committeeId+':checked').length) ===  $('.check_member_'+committeeId).length){
                $('.committe_'+ committeeId).prop('checked', true)
            }else{
                $('.committe_'+ committeeId).prop('checked', false)
            }
            if(($('.check_member:checked').length) ===  $('.check_member').length){
                $('.committee_header').prop('checked', true)
                $('.member_header').prop('checked', true)
            }else{
                $('.committee_header').prop('checked', false)
                $('.member_header').prop('checked', false)
            }
        })
        $("#create_meetings").submit(function(e){ 
            e.preventDefault();
            let meetingName = $("#meeting_name").val();
            let meetingObject = $("#meeting_object").val();
            let meetingDate = $("#meeting_date").val();
            let meetingLink = $("#meeting_link").val();
            let selectedMembers = [];
            $('.check_member:checked').each(function(){
                selectedMembers.push($(this).val());
            });
            let formData = {
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
                url: "{{ route('store_meeting') }}",
                dataType: "json",
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
                                window.location.href = 'meetings';
                            }
                        });
                    }
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