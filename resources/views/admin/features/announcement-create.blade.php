<form action="{{route('announcements.create')}}" method="post" enctype="multipart/form-data" onsubmit="ajaxFormSubmit($(this))">
    @csrf
    @if($announcement != null)
        <input type="hidden" name="id" value="{{$announcement->id}}">
    @endif
    <div class="form-group">
        <label class="form-label" for="image">Upload Photo (Optional)</label>
        <input type="file" name="image" class="form-control" id="image" accept="image/*" />
        <div class="invalid-feedback"></div>
        @if($announcement?->image)
            <div class="mt-2 text-muted">
                <small>Current photo: <a href="{{$announcement->image_path}}" target="_blank">View</a></small>
            </div>
        @endif
    </div>
    <div class="form-group">
        <label class="form-label" for="description">Text content (Optional)</label>
        <textarea name="description" class="form-control" id="description" rows="4" placeholder="Enter text content">{{$announcement?->description ?? ''}}</textarea>
        <div class="invalid-feedback"></div>
    </div>
    <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>