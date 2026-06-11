<div style="display:flex; gap:5px; justify-content:center;">
    <button type="button" class="btn btn-sm btn-info text-white" onclick="previewAnnouncement('{{ $model->image_path ? $model->image_path : '' }}', `{{ addslashes(str_replace(array("\r", "\n"), ' ', $model->description)) }}`)">View</button>
    <button type="button" class="btn btn-sm btn-warning text-white" onclick="commanModel(`{{route('announcements.create',['id' => encrypt($model->id)])}}`,'Edit Announcement')">Edit</button>
    <a href="{{ route('announcements.delete', ['id' => encrypt($model->id)]) }}" onclick="return confirm('Are you sure you want to delete this announcement?');" class="btn btn-sm btn-danger text-white">Delete</a>
</div>