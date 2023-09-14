@if(count($user_spaces) > 0)
    @foreach($user_spaces as $index => $spaces)
        <div class="d-flex justify-content-between px-2 align-items-center bg-space pointer show-font-icons_{{ $index }} font-icons" data-index="{{ $index }}" data-id="{{ $spaces->space->id }}">
            <div class="d-flex py-2 get_space align-items-center">
                <i class="fa-solid fa-caret-right arrow-color me-2 arrow-collapse_{{ $index }} area-collapse" data-id="{{ $spaces->space->id }}" data-index="{{ $index }}"></i> 
                <i class="fa-solid fa-sort-down arrow-color me-2 arrow-details_{{ $index }} details" style="display:none" data-index="{{ $index }}" data-id="{{ $spaces->space->id }}"></i>
                <span class="badge-bg rounded d-flex align-items-center justify-content-center text-white fs-6 show_space" style="height:20px; width:20px">{{ substr($spaces->space->space_name, 0, 1) }}</span>
                <span class="ms-3 show_space show_space_{{ $index }}" data-id="{{ $spaces->space->id }}">{{ $spaces->space->space_name }}</span>
            </div>
        </div>
        <div class="mt-2 task-space_{{ $index }}" style="display:none" data-index="{{ $index }}">
            <div class="show_results_{{ $index }}"></div>
            <div class="show_results_error_{{ $index }}"></div>
        </div>
    @endforeach
@else
    <div class="text-center mt-5">
        <h6>Pas d'espace pour le moment,Veuillez contacter votre administrateur</h6>
    </div>
@endif