<?php
	include('connect.php');
	$id=$_GET['id'];
	$result = $db->prepare("SELECT * FROM restaurants WHERE id= :userid");
	$result->bindParam(':userid', $id);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
	
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Edit Supplier</h4>
	</div>
	<div class="modal-body">
		<form action="saveeditmerchant.php" method="post" class = "form-group">
			<div id="ac">		
				<input type="hidden" class = "form-control" name="memi" value="<?php echo $id; ?>" />
				<span>Merchat: </span><input type="text" name="name" class = "form-control" value="<?php echo $row['name']; ?>" />
				<span>Deskripsi: </span><input type="text" name="desc" class = "form-control" value="<?php echo $row['description']; ?>" />
				<span>Alamat: </span><input type="text" name="address" class = "form-control" value="<?php echo $row['address']; ?>" />
				<span>Longitude: </span><input type="text" name="longitude" class = "form-control" value="<?php echo $row['longitude']; ?>" />
				<span>Latitude: </span><input type="text" name="latitude" class = "form-control" value="<?php echo $row['latitude']; ?>" />
				<span>Telephone: </span><input type="text" name="telp" class = "form-control" value="<?php echo $row['phone']; ?>" />
				<span>Image URL: </span><input type="text" name="image" class = "form-control" value="<?php echo $row['image']; ?>" />
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>			
		</form>
	</div>

<?php
}
?>

	