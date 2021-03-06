@extends('admin::layouts.master')

@section('styles')
<link href="{{ asset('public/assets/admin/global/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/assets/admin/global/plugins/dropzone/basic.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')


                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-settings font-dark"></i>
                                        <span class="caption-subject bold uppercase">Media Library</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="btn-group">
                                                    <button id="sample_editable_1_new" class="btn sbold green upload-button"> Add New
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="pull-right">
                                                    <input class="form-control spinner" type="text" placeholder="Search">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row upload-area">
                                        <div class="col-md-12">
                                            <form action="{{ route('admin.media.add.post') }}" class="dropzone dropzone-file-area" id="my-dropzone" style="margin-top: 10px;">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                <h3 class="sbold">Drop files here or click to upload</h3>
                                            </form>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row media">
                                    @if(isset($media))
                                        @foreach($media as $m)
                                        <div class="col-md-2 col-sm-2 col-xs-3"><a data-toggle="modal" data-number="{{$m->id}}" href="#media-full" class="media-data"><img class="img-responsive" src="{{asset('public')}}/uploads/thumbnail/{{$m->guid}}"></a></div>
                                        @endforeach
                                    @endif
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="media-full" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-full">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h4 class="modal-title">Attachment Details</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <img class="img-responsive full-image" src="">
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                <div><strong>File name:</strong> <span class="file-name"></span></div>
                                                <div><strong>File type:</strong> <span class="file-type"></span></div>
                                                <div><strong>Uploaded on:</strong> <span class="file-date"></span></div>
                                                <div><strong>File size:</strong> <span class="file-size"></span></div>
                                                <div><strong>Dimensions:</strong> <span class="file-dimensions"></span></div>
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <form method="post" action="" class="delete" onsubmit="return confirm('Are you sure about this action?');">
                                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                <input type="hidden" name="id" value="" class="media_id"/>
                                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                <input type="submit" value="Delete" class="btn sbold red bt">
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->

@stop
@section('scripts')
<script src="{{ asset('public/assets/admin/global/plugins/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/admin/pages/scripts/form-dropzone.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/admin/pages/scripts/date.format.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$('body').on('click', '.media-data', function () {
    var number = $(this).attr("data-number");
    $('.full-image').attr("src", "");
    jQuery.ajax({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "{{ route('admin.media.ajax.post') }}",
        data:'number='+number,
        success: function(data){
            if(data.success) {
                var media = data.media;
                $('.full-image').attr("src", "{{asset('public')}}/uploads/full/"+media.guid);
                $('.delete').attr('action',"{{ route('admin.media.delete.post', ['id' => null]) }}/"+media.id);
                $('.media_id').attr('value', media.id);
                $('.file-name').html(media.name);
                $('.file-type').html(media.mime_type);
                $('.file-date').html(new Date(media.created_at).format("F j, Y"));
                $('.file-size').html(formatSizeUnits(media.meta.size));
                $('.file-dimensions').html(media.meta.dimensions.large.width +' x '+media.meta.dimensions.large.height);
                $('#media-full').modal({ show: true });
            } else {
                $('#media-full').modal({ show: false });
            }
        }
    });
});
</script>
@stop