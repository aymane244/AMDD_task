<script>
    $(document).ready(function (){
        $(document).on('click', ".delete_space", function (){
            let spaceId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous supprimer cet espace ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Supprimer'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/delete-space/' + spaceId,
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