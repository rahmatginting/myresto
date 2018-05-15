<?php session_start(); ?>    
<?php include 'inc/config.php'; $template['header_link'] = "Testing";?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<!-- DataTables Responsive CSS -->
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="lib/jquery.js" type="text/javascript"></script>
<script src="src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('a[rel*=facebox]').facebox({
            loadingImage : 'src/loading.gif',
            closeImage   : 'src/closelabel.png'
        })
    })
</script>

<!-- Page content -->
<div id="page-content">
    <!-- Table Styles Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Master Merchant</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <h2></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <div id="maintable"><div style="margin-top: 10px; margin-bottom: 10px;">
            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <a href = "#add" data-toggle = "modal" class="btn btn-primary">Tambah Merchant</a>
                <?php include 'addmerchant.php'; ?>
                <thead>
                    <tr>
                        <th> Merchant </th>
                        <th> Deskripsi </th>
                        <th> Alamat </th>
                        <th> Longitude </th>
                        <th> Latitude </th>
                        <th> Telephone </th>
                        <th> Image URL </th>
                        <th width="10%"> Aksi </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include('connect.php');
                        $result = $db->prepare("SELECT * FROM restaurants ORDER BY id DESC");
                        //$result = $db->prepare("SELECT * FROM restaurants WHERE id = '". $_SESSION['sys_restoID'] . "' ORDER BY id DESC");
                        $result->execute();
                        for($i=0; $row = $result->fetch(); $i++){
                    ?>
                    <tr class="record">
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['longitude']; ?></td>
                        <td><?php echo $row['latitude']; ?></td>
                        <td align="right"><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['image']; ?></td>
                        <td align="center"><a rel="facebox" class="btn btn-primary" href="editmerchant.php?id=<?php echo $row['id']; ?>"> <i class="fa fa-pencil"></i> </a>  <a href="#" id="<?php echo $row['id']; ?>" class="btn btn-danger delbutton" title="Click To Delete"><i class = "fa fa-trash"></i></a></td>
                    </tr>
                    <?php
                        }
                        ?>
                </tbody>            
            </table>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<script src="js/jquery.js"></script>
<script type="text/javascript">
    $(function() {
    $(".delbutton").click(function(){

    //Save the link in a variable called element
    var element = $(this);

    //Find the id of the link that was clicked
    var del_id = element.attr("id");

    //Built a url to send
    var info = 'id=' + del_id;
        if(confirm("Apakah anda yakin akan menghapus? Data TIDAK bisa dikembalikan!"))
        {
            $.ajax({
            type: "GET",
            url: "deletemerchant.php",
            data: info,
            success: function(){
        }
    });
    $(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast")
        .animate({ opacity: "hide" }, "slow");
    }
        return false;
    });
    });
</script>

<!-- jQuery -->
<script src="vendors/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="vendors/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="vendors/metisMenu/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

<!-- DataTables JavaScript -->
<script src="vendors/datatables/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="vendors/datatables-responsive/dataTables.responsive.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/uiTables.js"></script>
<script>$(function(){ UiTables.init(); });</script>

<?php include 'inc/template_end.php'; ?>
