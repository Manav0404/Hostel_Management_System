<?php
// Set the title and base URL
$GLOBALS['title'] = "Bill-HMS";
$base_url = "http://localhost/hms/";

// Include necessary files
require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');

// Start the session
$ses = new \sessionManager\sessionManager();
$ses->start();

// Redirect to login page if session is expired
if ($ses->isExpired()) {
    header('Location: ' . $base_url . 'login.php');
    exit; // Ensure script execution stops after redirection
} else {
    // Get session data
    $loginId = $ses->Get("userIdLoged");
    $loginGrp = $ses->Get("userGroupId");

    // Initialize variables
    $msg = "";
    $output = "";
    $isData = "0"; // Assume no data initially

    // Create database connection
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg == "true") {
        // Construct SQL query based on user group
        if ($loginGrp == "UG001") {
            $query = "SELECT a.billId, b.name, SUM(a.amount) as amount, DATE_FORMAT(a.billingDate,'%D %M,%Y') as date 
                      FROM billing AS a, studentinfo AS b 
                      WHERE a.billTo = b.userId AND b.isActive = 'Y' 
                      GROUP BY billId";
        } else {
            $query = "SELECT a.billId, b.name, SUM(a.amount) as amount, DATE_FORMAT(a.billingDate,'%D %M,%Y') as date 
                      FROM billing AS a, studentinfo AS b 
                      WHERE a.billTo = b.userId AND b.isActive = 'Y' AND a.billTo = '".$loginId."' 
                      GROUP BY billId";
        }

        // Execute query
        $result = $db->getData($query);

        if ($result !== false) {
            // Build table if data is available
            $output .= '<div class="table-responsive">
                            <table id="billList" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Bill Id</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Bill Date</th>';
            // Show Action column only for certain user groups
            if ($loginGrp === "UG001") {
                $output .= '<th>Action</th>';
            }
            $output .= '</tr>
                        </thead>
                        <tbody>';

            // Fetch and display data
            while ($row = mysqli_fetch_array($result)) {
                $isData = "1"; // Data is available
                $output .= "<tr>";
                $output .= "<td><a href='single.php?billId=".$row["billId"]."' title='View Details'>" . $row['billId'] . "</a></td>";
                $output .= "<td>" . $row['name'] . "</td>";
                $output .= "<td>" . $row['amount'] . "/-</td>";
                $output .= "<td>" . $row['date'] . "</td>";
                // Show Delete action only for certain user groups
                if ($loginGrp === "UG001") {
                    $output .= "<td><a title='Delete' class='btn btn-danger btn-circle' href='action.php?id=" . $row['billId'] . "&wtd=delete'><i class='fa fa-trash-o'></i></a></td>";
                }
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

// Include appropriate master file based on user group
if ($loginGrp === "UG004") {
    include('./../../smater.php');
} else {
    include('./../../master.php');
}
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Billing View</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i> Hostel Bill List View

                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">


                    <div class="row">
                        <div class="col-lg-12">
                            <hr />
                            <?php if($GLOBALS['isData']=="1"){echo $GLOBALS['output'];}?>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header alert alert-info">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 id="myModalLabel" class="modal-title"></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="col-lg-6">
                                           <div class=""><label>Bill No: </label> <span id="billId"></span></div>
                                            </div> <div class="col-lg-6">
                                           <div class=""><label>Bill Date: </label> <span id="billDate"></span></div>
                                            </div>
                                            </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                        <table id="mbilllist" class="table table-responsive table-hover text-center">
                                            <thead >
                                            <tr>
                                                <th class="text-center text-primary">Type</th>
                                               <th class="text-center text-primary">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            </table>
                                            <div class="text-info"><label>Total: </label> <span id="total"></span></div>
                                        </div>

                                    </div>
                                <p></p>
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-primary" type="button">Close</button>

                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->



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
    $( document ).ready(function() {



        $('#billList').dataTable();
        $('.showModal').on('click', function(e) {
            e.preventDefault();

           var table =document.getElementById('billList');
            var r =  $(this).parent().parent().index();
           var BillTo =table.rows[r+1].cells[1].innerHTML;
            var billId=table.rows[r+1].cells[0].innerHTML;
            var date = table.rows[r+1].cells[3].innerHTML;
            var t = table.rows[r+1].cells[2].innerHTML;
            $('#myModalLabel').text("Billing Info of ["+BillTo+"]");
            $('#billId').text(billId);
            $('#billDate').text(date);
            $('#total').text(t);


            value = new Array();
            $.ajax({
                type: "GET",
                url: "action.php",
                dataType: 'json',
                success: function (result) {
                    alert(result);
                }

            });
          //  $("#myModal").modal('show');


        });
    });




</script>