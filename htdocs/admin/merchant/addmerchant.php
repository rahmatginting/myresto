 <div class="panel-body">
    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Merchant</h4>
                </div>
                <div class="modal-body">
                    <form action="savemerchant.php" method="post" class = "form-group">
 						<div id="ac">
 						<span>Merchant: </span><input type="text" name="name" class = "form-control" />
 						<span>Deskripsi: </span><input type="text" name="desc" class = "form-control" />
 						<span>Alamat: </span><input type="text" name="address" class = "form-control" />
                        <span>Longitude: </span><input type="text" name="longitude" class = "form-control" />
                        <span>Latitude: </span><input type="text" name="latitude" class = "form-control" />
 						<span>No. Telp: </span><input type="text" name="telp" class = "form-control" />
                        <span>Image URL: </span><input type="text" name="image" class = "form-control" />
 						<span>&nbsp;</span><input class="btn btn-primary btn-block"  type="submit" value="Simpan" class = "form-control" />
 						</div>
 					</form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
                <!-- /.modal-content -->
        </div>
            <!-- /.modal-dialog -->
    </div>
                        <!-- /.modal -->
</div>                        