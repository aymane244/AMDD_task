@if(count($all_spaces) > 0)
    @foreach($all_spaces as $index => $space)
        <div class="d-flex justify-content-between px-2 align-items-center bg-space pointer show-font-icons_{{ $index }} font-icons" data-index="{{ $index }}" data-index="{{ $index }}">
            <div class="d-flex py-2 get_space align-items-center">
                <i class="fa-solid fa-caret-right arrow-color me-2 arrow-collapse_{{ $index }} area-collapse" data-id="{{ $space->id }}" data-index="{{ $index }}"></i> 
                <i class="fa-solid fa-sort-down arrow-color me-2 arrow-details_{{ $index }} details" style="display:none" data-index="{{ $index }}"></i>
                <span class="badge-bg rounded d-flex align-items-center justify-content-center text-white fs-6 show_space" style="height:20px; width:20px" data-id="{{ $space->id }}">{{ substr($space->space_name, 0, 1) }}</span>
                <span class="ms-3 show_space show_space_{{ $index }}" data-id="{{ $space->id }}">{{ $space->space_name }}</span>
            </div>
            <div class="font-div_{{ $index }}" style="display: none">
                <i class="fa-solid fa-plus text-success" data-bs-toggle="modal" data-bs-target="#create_task" data-id="{{ $space->id }}"></i>
                <i class="mx-1 fa-solid fa-trash text-danger delete_space" data-id="{{ $space->id }}"></i>
                <i class="fa-solid fa-folder-open text-info archive_space" data-id="{{ $space->id }}"></i>
            </div>
        </div>
        <div class="mt-2 task-space_{{ $index }}" style="display:none" data-index="{{ $index }}">
            <div class="show_results_{{ $index }}"></div>
            <div class="show_results_error_{{ $index }}"></div>
        </div>
        <div class="mt-2 text-center loading_all_task_{{ $index }}" style="display: none;">
            <p><i class="fa fa-spinner fa-spin me-2"></i> Charegement...</p>
        </div>
    @endforeach
    @include('tasks.add')
    @include('spaces.delete')
    @include('spaces.archive')
@else
    <div class="text-center mt-5">
        <h6>Pas d'espace pour le moment,Veuillez créer un nouvel espace</h6>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create_space">Créer un espace</button>
    </div>
@endif