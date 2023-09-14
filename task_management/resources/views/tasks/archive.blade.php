<script>
    $(document).ready(function (){
        $(document).on('click', ".archive_task", function (){
            let taskId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous archiver cette tâche ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Archiver'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/archive-task/' + taskId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data){
                            Swal.fire({
                                icon: 'success',
                                html: data.message
                            }).then((res) =>{
                                window.location.href = 'space';
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