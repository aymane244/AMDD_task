<script>
    $(document).ready(function (){
        $(document).on('click', ".archive_space", function (){
            let spaceId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous archiver cet espace ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Archiver'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/archive-space/' + spaceId,
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