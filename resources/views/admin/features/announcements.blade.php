@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <div class="row">
        <div class="col-md-12 my-3 d-flex justify-content-between align-items-center">
            <h4 class="card-title">Announcements</h4>
            <a href="javascript:void(0)" class="btn btn-main btn-sm" onclick="commanModel(`{{route('announcements.create')}}`,'Add Announcement')">Add</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="card">
            <div class="card-body">
                <div class="row">
                    @if(isset($announcements) && $announcements->count() > 0)
                        @foreach ($announcements as $announcement)
                        <div class="col-md-4">
                                <div class="card" style="border-radius:10px;">
                                    <img class="card-img-top" src="{{$announcement->image_path ?? '' }}" alt="Card image cap">
                                    <div class="card-body">
                                        <h5><i class="fa fa-volume-full"></i>{{ ucwords($announcement->title) }}</h5>
                                        <p class="card-text">{{ $announcement->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                            <div class="col-md-12 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>No Announcements Found</h6>
                                    </div>
                                </div>
                            </div>
                    @endif
                </div>
            </div>
        </div> --}}
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewAnnouncementModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg" style="border:none; border-radius:18px; overflow:hidden; background: #fff;">
      <button type="button" class="close" data-bs-dismiss="modal" data-dismiss="modal" onclick="$('#previewAnnouncementModal').modal('hide');" aria-label="Close" style="position:absolute; top:12px; right:16px; z-index:15; font-size:1.5rem; text-shadow:0 2px 4px rgba(0,0,0,0.3); border:none; background:transparent;">
          <span aria-hidden="true" id="previewCloseIcon">&times;</span>
      </button>

      <div id="previewImageContainer" style="position:relative; width:100%; max-height:60vh; overflow:hidden; background:#000; display:flex; align-items:center; justify-content:center; display:none;">
        <img id="previewModalImage" src="" class="img-fluid" alt="Announcement" style="width:100%; object-fit:cover;">
        
        <div id="previewTextOverlay" style="position:absolute; bottom:0; left:0; width:100%; padding:20px; background:rgba(255,255,255,1); backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px); border-top:1px solid rgba(255,255,255,0.3); display:none;">
          <p id="previewModalDescriptionOverlay" class="mb-0" style="font-weight:700; font-size:1.05rem; text-align:center; text-shadow: 0 1px 2px rgba(255,255,255,0.5); color: black;">
          </p>
        </div>
      </div>

      <div id="previewTextOnlyContainer" class="modal-body text-center p-4" style="display:none;">
        <div class="mb-3">
           <i class="icon-bell text-main" style="font-size:3rem; color: #00b4d8; background: rgba(0,180,216,0.1); padding: 15px; border-radius: 50%;"></i>
        </div>
        <p id="previewModalDescription" class="mb-0 text-dark" style="font-size:1.1rem; line-height:1.6;">
        </p>
      </div>
      <div class="modal-footer justify-content-center border-0 p-3 pt-0">
         <button type="button" class="btn btn-main rounded-pill px-4" data-bs-dismiss="modal" data-dismiss="modal" onclick="$('#previewAnnouncementModal').modal('hide');" style="box-shadow: 0 4px 15px rgba(3,75,179,0.3);">OK, Got it</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
@parent
    {{ $dataTable->scripts() }}
    <script>
        function previewAnnouncement(imageUrl, description) {
            // Reset visibility
            $('#previewImageContainer').hide();
            $('#previewTextOverlay').hide();
            $('#previewTextOnlyContainer').hide();
            $('#previewModalImage').attr('src', '');
            $('#previewModalDescriptionOverlay').text('');
            $('#previewModalDescription').text('');
            
            if (imageUrl) {
                $('#previewImageContainer').show();
                $('#previewModalImage').attr('src', imageUrl);
                $('#previewCloseIcon').css('color', '#fff');
                
                if (description) {
                    $('#previewTextOverlay').show();
                    $('#previewModalDescriptionOverlay').text(description);
                }
            } else {
                $('#previewCloseIcon').css('color', '#333');
                $('#previewTextOnlyContainer').show();
                $('#previewModalDescription').text(description);
            }
            
            $('#previewAnnouncementModal').appendTo('body').modal('show');
        }
    </script>
@endsection