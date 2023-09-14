<script>
    $(document).ready(function (){
        $(document).on('click', ".archive_meeting", function (){
            let meetingId = $(this).data('id');
            Swal.fire({
                title: 'Voulez-vous archiver cette réunion ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Archiver'
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url: '/archive-meeting/' + meetingId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data){
                            Swal.fire({
                                icon: 'success',
                                html: data.message
                            }).then((res) =>{
                                window.location.href = 'meetings';
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