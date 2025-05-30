
<div id="container_documents" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Container documents</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">     
                </button>
            </div>
            <div class="modal-body p-8">

                <form action="{{route('container_documents.store')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="transit_id" value="{{$transit->id}}">
            
                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="file_name" class="control-label">Document name</label>
                                <input type="text" class="form-control" name="file_name">
                            </div>
                        </div>              
                    </div>

                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="file_url" class="control-label">Upload Document</label> <br>
                                <input type="file" name="file_url">
                            </div>
                        </div>              
                    </div>

                    <hr>
                    <button class="btn btn-danger" type="submit">Save</button>
                </form>

            </div>
        </div>
    </div>
</div>