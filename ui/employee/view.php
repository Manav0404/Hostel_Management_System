<?php
$GLOBALS['title'] = "Employee-HMS";
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
        $result = $db->execDataTable("SELECT * FROM employee WHERE isActive='Y'");
        if ($result !== false && $result !== null) {
            $output .= '<div class="table-responsive">
                            <table id="employeeList" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Employee Type</th>
                                        <th>Designation</th>
                                        <th>Join Date</th>
                                        <th>Salary</th>
                                        <th>Block No</th>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>';
            while ($row = mysqli_fetch_array($result)) {
                $output .= "<tr>";
                $output .= "<td><img class='perPhoto' src='./../../files/photos/" . $row['perPhoto'] . "'></td>";
                $output .= "<td>" . $row['name'] . "</td>";
                $output .= "<td>" . $row['gender'] . "</td>";
                $output .= "<td>" . $row['empType'] . "</td>";
                $output .= "<td>" . $row['designation'] . "</td>";
                $output .= "<td>";

                 // Here's where you integrate the snippet
                if (isset($row['joinDate'])) {
                     $output .= $row['joinDate'];
                } else {
                if (isset($handyCam)) {
                     $output .= $handyCam->getAppDate($row['doj']);
                } else {
                // Handle the case where $handyCam is not available
                $output .= "Date not available";
                 }
            }

                $output .= "</td>";
                $output .= "<td>" . $row['salary'] . "</td>";
                $output .= "<td>" . $row['blockNo'] . "</td>";
                $output .= "<td>" . $row['address'] . "</td>";
                $output .= "<td><a title='Edit' class='btn btn-success btn-circle' href='edit.php?id=" . $row['empId'] . "&wtd=edit'><i class='fa fa-pencil'></i></a><a title='Delete' class='btn btn-danger btn-circle' href='edit.php?id=" . $row['empId'] . "&wtd=delete'><i class='fa fa-trash-o'></i></a></td>";
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
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Employee List</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Employee List
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
        $('#employeeList').dataTable();
    });
</script>
