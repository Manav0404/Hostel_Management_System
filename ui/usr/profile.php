<?php
$GLOBALS['title'] = "Profile-HMS";
$page_name = "DashBoard";

require('./../../inc/sessionManager.php');
require('./../../inc/dbPlayer.php');
require('./../../inc/handyCam.php');

$base_url = "http://localhost/hms/";
$ses = new \sessionManager\sessionManager();
$ses->start();

if ($ses->isExpired()) {
    header('Location: ' . $base_url . 'login.php');
} elseif ($ses->Get("userGroupId") == "UG003") {
    header('Location: ' . $base_url . 'edashboard.php');
} else {
    $userIdf = $ses->Get("userIdLoged");
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg === true) {
        $data = array();
        $result = $db->getData("SELECT * FROM studentinfo WHERE userId='" . $userIdf . "'");
        $handyCam = new \handyCam\handyCam();

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);

            $data['name'] = $row['name'];
            $data['studentId'] = $row['studentId'];
            $data['cellNo'] = $row['cellNo'];
            $data['email'] = $row['email'];
            $data['nameOfInst'] = $row['nameOfInst'];
            $data['program'] = $row['program'];
            $data['batchNo'] = $row['batchNo'];
            $data['gender'] = $row['gender'];
            $data['dob'] = $handyCam->getAppDate($row['dob']);
            $data['bloodGroup'] = $row['bloodGroup'];
            $data['nationality'] = $row['nationality'];
            $data['nationalId'] = $row['nationalId'];
            $data['passportNo'] = $row['passportNo'];
            $data['fatherName'] = $row['fatherName'];
            $data['fatherCellNo'] = $row['fatherCellNo'];
            $data['motherName'] = $row['motherName'];
            $data['motherCellNo'] = $row['motherCellNo'];
            $data['localGuardian'] = $row['localGuardian'];
            $data['localGuardianCell'] = $row['localGuardianCell'];
            $data['presentAddress'] = $row['presentAddress'];
            $data['parmanentAddress'] = $row['parmanentAddress'];
            $data['perPhoto'] = $row['perPhoto'];
        } else {
            echo '<script type="text/javascript"> alert("No data found for the user.");</script>';
        }
    } else {
        echo '<script type="text/javascript"> alert("' . $msg . '");</script>';
    }
}

include('./../../master.php');
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Profile<i class="fa fa-hand-o-left"></i></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <i class="fa fa-info-circle fa-fw"></i>User Information
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4">
                        <img src="./../../files/photos/<?php echo isset($data['perPhoto']) ? $data['perPhoto'] : ''; ?>" alt="Avatar" height="220px" class="img-responsive img-rounded proimg">
                    </div>
                    <div class="col-lg-4"></div>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Name:</label>
                            <span><?php echo isset($data['name']) ? $data['name'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Student Id:</label>
                            <span><?php echo isset($data['studentId']) ? $data['studentId'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Cell NO:</label>
                            <span><?php echo isset($data['cellNo']) ? $data['cellNo'] : ''; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Email:</label>
                            <span><?php echo isset($data['email']) ? $data['email'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Institute:</label>
                            <span><?php echo isset($data['nameOfInst']) ? $data['nameOfInst'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Program:</label>
                            <span><?php echo isset($data['program']) ? $data['program'] : ''; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Batch:</label>
                            <span><?php echo isset($data['batchNo']) ? $data['batchNo'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Gender:</label>
                            <span><?php echo isset($data['gender']) ? $data['gender'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Birth Date:</label>
                            <span><?php echo isset($data['dob']) ? $data['dob'] : ''; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Blood Group:</label>
                            <span><?php echo isset($data['bloodGroup']) ? $data['bloodGroup'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Nationality:</label>
                            <span><?php echo isset($data['nationality']) ? $data['nationality'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>National ID:</label>
                            <span><?php echo isset($data['nationalId']) ? $data['nationalId'] : ''; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Passport No:</label>
                            <span><?php echo isset($data['passportNo']) ? $data['passportNo'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Father's Name:</label>
                            <span><?php echo isset($data['fatherName']) ? $data['fatherName'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Father's Cell NO:</label>
                            <span><?php echo isset($data['fatherCellNo']) ? $data['fatherCellNo'] : ''; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Mother's Name:</label>
                            <span><?php echo isset($data['motherName']) ? $data['motherName'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Mother's Cell NO:</label>
                            <span><?php echo isset($data['motherCellNo']) ? $data['motherCellNo'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Local Guardian:</label>
                            <span><?php echo isset($data['localGuardian']) ? $data['localGuardian'] : ''; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Local Guardian's Cell NO:</label>
                            <span><?php echo isset($data['localGuardianCell']) ? $data['localGuardianCell'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Present Address:</label>
                            <div><?php echo isset($data['presentAddress']) ? $data['presentAddress'] : ''; ?></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label>Permanent Address:</label>
                            <div><?php echo isset($data['parmanentAddress']) ? $data['parmanentAddress'] : ''; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <div class="panel-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('./../../footer.php'); ?>
</div>
