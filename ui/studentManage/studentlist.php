<?php
$GLOBALS['title'] = "Student-HMS";
$base_url = "http://localhost/hms/";
require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');

$ses = new \sessionManager\sessionManager();
$ses->start();

if ($ses->isExpired()) {
    header('Location: ' . $base_url . 'login.php');
} else {
    $msg = "";
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();
    
    if ($msg === true) {
        $output = '';
        $result = $db->execDataTable("SELECT * FROM studentinfo WHERE isActive='Y'");
        if ($result !== false && $result !== null) {
            $output .= '<div class="table-responsive">
                            <table id="studentList" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Mobile No</th>
                                        <th>Institute</th>
                                        <th>Program</th>
                                        <th>L.Guardian</th>
                                        <th>L.G. Mobile</th>
                                        <th>P.Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>';
            while ($row = mysqli_fetch_array($result)) {
                $output .= "<tr>";
                $output .= "<td>" . $row['name'] . "</td>";
                $output .= "<td>" . $row['cellNo'] . "</td>";
                $output .= "<td>" . $row['nameOfInst'] . "</td>";
                $output .= "<td>" . $row['program'] . "</td>";
                $output .= "<td>" . $row['localGuardian'] . "</td>";
                $output .= "<td>" . $row['localGuardianCell'] . "</td>";
                $output .= "<td>" . $row['presentAddress'] . "</td>";
                $output .= "<td><a title='View' class='btn btn-danger btn-circle' href='studentedit.php?id=" . $row['userId'] . "&wtd=view'><i class='fa fa-file-o'></i></a><a title='Edit' class='btn btn-success btn-circle' href='studentedit.php?id=" . $row['userId'] . "&wtd=edit'><i class='fa fa-pencil'></i></a><a title='Delete' class='btn btn-danger btn-circle' href='studentedit.php?id=" . $row['userId'] . "&wtd=delete'><i class='fa fa-trash-o'></i></a></td>";
                $output .= "</tr>";
            }
            $output .= '</tbody></table></div>';
        } else {
            $output = '<div class="alert alert-danger" role="alert">Error occurred while fetching data.</div>';
        }
    } else {
        $output = '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
    }
}    

include('./../../master.php');
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Student List</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Student List
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <hr />
                            <?php echo $output; ?>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
<!-- /#page-wrapper -->

<?php include('./../../footer.php'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#studentList').dataTable();
    });
</script>
