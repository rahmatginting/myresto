<?php
	include('connect.php');
	$id=$_GET['id'];
	$result = $db->prepare("SELECT * FROM restaurant_tables WHERE id= :userid");
	$result->bindParam(':userid', $id);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
	
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Edit Meja</h4>
	</div>
	<div class="modal-body">
		<form action="saveedittable.php" method="post" class = "form-group">
			<div id="ac">		
				<input type="hidden" class = "form-control" name="memi" value="<?php echo $id; ?>" />
				<span>Kode Meja: </span><input type="text" name="code" class = "form-control" value="<?php echo $row['code']; ?>" disabled/>
				<span>Deskripsi: </span><input type="text" name="desc" class = "form-control" value="<?php echo $row['description']; ?>" />
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

	