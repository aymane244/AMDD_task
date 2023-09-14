<!-- Modal -->
<div class="modal fade" id="create_space" tabindex="-1" aria-labelledby="create_spaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="create_spaceLabel">Création de l'espace</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="create">
                    <div class="row justify-content-center">
                        <div class="col-md-10 mb-3">
                            <label for="space_name" class="col-form-label text-md-end">Espace</label>
                            <input id="space_name" type="text" class="form-control" name="space_name" value="{{ old('space_name') }}">
                            <span class="space_name text-danger"></span>
                        </div>
                        <div class="col-md-10 mb-3">
                            <label for="task_duration" class="col-form-label text-md-end">Description de la tâche</label>
                            <textarea class="form-control" aria-label="With textarea" rows="10" id="description" name="description"></textarea>
                            <span class="description text-danger"></span>
                        </div>
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-primary">Crée l'espace</button>
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
        tinymce.init({
            selector: '#description',
            branding: false,
            plugins: [
                'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
                'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
                'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
            ],
            toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
        });
        $("#create").submit(function(e){ 
            e.preventDefault();
            let spaceName = $("#space_name").val();
            let description = $("#description").val();
            let formData = {
                space_name: spaceName,
                description: description
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('store_space') }}",
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