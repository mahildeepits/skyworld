<form action="{{route('news.events.store')}}" method="post" onsubmit="ajaxFormSubmit($(this))" >
    @csrf
    @if ($news_event != null)
        <input type="hidden" name="id" value="{{$news_event->id ?? null}}">
    @endif
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{$news_event?->title ?? ''}}" placeholder="Title">
        <div class="invalid-feedback"></div>
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" class="form-control" id="image" name="image">
        <div class="invalid-feedback"></div>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" class="form-control" id="description">{{$news_event?->description ?? ''}}</textarea>
        <div class="invalid-feedback"></div>
    </div>
    <div class="mt-3">
        <input type="submit" value="Submit" name="submit" class="btn btn-primary">
    </div>
</form>