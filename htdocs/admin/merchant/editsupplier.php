<?php
	include('connect.php');
	$id=$_GET['id'];
	$result = $db->prepare("SELECT * FROM supliers WHERE suplier_id= :userid");
	$result->bindParam(':userid', $id);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
	
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Edit Supplier</h4>
	</div>
	<div class="modal-body">
		<form action="saveeditsupplier.php" method="post" class = "form-group">
			<div id="ac">		
				<input type="hidden" class = "form-control" name="memi" value="<?php echo $id; ?>" />
				<span>Nama: </span><input type="text" name="name" class = "form-control" value="<?php echo $row['suplier_name']; ?>" />
				<span>Kontak/Sales: </span><input type="text" name="cperson" class = "form-control" value="<?php echo $row['contact_person']; ?>" />
				<span>Alamat: </span><input type="text" name="address" class = "form-control" value="<?php echo $row['suplier_address']; ?>" />
				<span>No. Telp: </span><input type="text" name="contact" class = "form-control" value="<?php echo $row['suplier_contact']; ?>" />
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

	