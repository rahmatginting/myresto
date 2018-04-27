<?php include 'inc/config.php'; ?>
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
                    <h1>Master Supplier</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <!--
                    <ul class="breadcrumb breadcrumb-top">
                        <li>User Interface</li>
                        <li>Elements</li>
                        <li><a href="">Tables</a></li>
                    </ul>
                    -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Table Styles Header -->

    <!-- Table Styles Block -->
    <!--<div class="block">
        ##Table Styles Title 
        **<div class="block-title clearfix">
            ##Changing classes functionality initialized in js/pages/tablesGeneral.js 
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default active" id="style-striped" data-toggle="tooltip" title=".table-striped">Striped</a>
                <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default" id="style-condensed" data-toggle="tooltip" title=".table-condensed">Condensed</a>
                <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default" id="style-hover" data-toggle="tooltip" title=".table-hover">Hover</a>
            </div>
            <div class="block-options pull-right">
                <div id="style-borders" class="btn-group">
                    <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default" id="style-default" data-toggle="tooltip">Default</a>
                    <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default active" id="style-bordered" data-toggle="tooltip" title=".table-bordered">Bordered</a>
                    <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default" id="style-borderless" data-toggle="tooltip" title=".table-borderless">Borderless</a>
                </div>
            </div>
            <h2><span class="hidden-xs">Table</span> Styles</h2>
        </div> -->
        <!-- END Table Styles Title -->

        <!-- Table Styles Content -->
    <div class="table-responsive">
            <!--
            Available Table Classes:
                'table'             - basic table
                'table-bordered'    - table with full borders
                'table-borderless'  - table with no borders
                'table-striped'     - striped table
                'table-condensed'   - table with smaller top and bottom cell padding
                'table-hover'       - rows highlighted on mouse hover
                'table-vcenter'     - middle align content vertically
            -->
        <div id="maintable"><div style="margin-top: 10px; margin-bottom: 10px;">
            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <a href = "#add" data-toggle = "modal" class="btn btn-primary">Tambah Supplier</a>
                <?php include 'addsupplier.php'; ?>
                <thead>
                    <tr>
                        <th> Supplier </th>
                        <th> Kontak </th>
                        <th> Alamat </th>
                        <th> No. Telp </th>
                        <th width="10%"> Aksi </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include('connect.php');
                        $result = $db->prepare("SELECT * FROM supliers ORDER BY suplier_id DESC");
                        $result->execute();
                        for($i=0; $row = $result->fetch(); $i++){
                    ?>
                    <tr class="record">
                        <td><?php echo $row['suplier_name']; ?></td>
                        <td><?php echo $row['contact_person']; ?></td>
                        <td><?php echo $row['suplier_address']; ?></td>
                        <td align="right"><?php echo $row['suplier_contact']; ?></td>
                        <td align="center"><a rel="facebox" class="btn btn-primary" href="editsupplier.php?id=<?php echo $row['suplier_id']; ?>"> <i class="fa fa-pencil"></i> </a>  <a href="#" id="<?php echo $row['suplier_id']; ?>" class="btn btn-danger delbutton" title="Click To Delete"><i class = "fa fa-trash"></i></a></td>
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
            url: "deletesupplier.php",
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