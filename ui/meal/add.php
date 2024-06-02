<?php
// Set global variables
$GLOBALS['title'] = "Meal-HMS";
$base_url = "http://localhost/hms/";

// Include necessary files
require './../../inc/sessionManager.php';
require './../../inc/dbPlayer.php';

// Start session
$ses = new \sessionManager\sessionManager();
$ses->start();

// Redirect to login page if session is expired
if ($ses->isExpired()) {
    header('Location: ' . $base_url . 'login.php');
    exit(); // Ensure script stops execution after redirection
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnSave"])) {
    // Initialize database connection
    $db = new \dbPlayer\dbPlayer();
    $msg = $db->open();

    if ($msg === true) {
        // Retrieve form data
        $userId = $_POST['person'];
        $noOfMeal = floatval($_POST['noOfMeal']);
        $date = date("Y-m-d");

        // Prepare data for insertion
        $data = array(
            'userId' => $userId,
            'noOfMeal' => $noOfMeal,
            'date' => $date
        );

        // Insert data into database
        $result = $db->insertData("meal", $data);

        if ($result >= 0) {
            echo '<script>alert("Meal Added Successfully."); window.location="add.php";</script>';
            exit(); // Ensure script stops execution after redirection
        } elseif (strpos($result, 'Duplicate') !== false) {
            echo '<script>alert("Meal Already Exists!");</script>';
        } else {
            echo '<script>alert("Error: ' . $result . '");</script>';
        }
    } else {
        echo '<script>alert("Error: ' . $msg . '");</script>';
    }
}

// Fetch data for dropdown
$db = new \dbPlayer\dbPlayer();
$msg = $db->open();
$GLOBALS['output'] = '';

if ($msg === true) {
    $result = $db->getData("SELECT userId, name FROM studentinfo WHERE isActive='Y'");

    if ($result !== false && $result !== null) {
        while ($row = mysqli_fetch_array($result)) {
            $GLOBALS['isData'] = "1";
            $GLOBALS['output'] .= '<option value="' . $row['userId'] . '">' . $row['name'] . '</option>';
        }
    } else {
        echo '<script>alert("Error occurred while fetching data.");</script>';
    }
} else {
    echo '<script>alert("Error: ' . $msg . '");</script>';
}

?>
<?php include('./../../master.php'); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header titlehms"><i class="fa fa-hand-o-right"></i>Meal Add</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-fw"></i>Student Meal [Today]
                </div>
                <div class="panel-body">
                    <form name="meal" action="add.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Student Name</label>
                                    <select class="form-control" name="person" required="">
                                        <?php echo $GLOBALS['output']; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>No Of Meal</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-info"></i></span>
                                        <input type="number" min="1" placeholder="No Of Meal" class="form-control" name="noOfMeal" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-5"></div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success" name="btnSave"><i class="fa fa-2x fa-check"></i>Save</button>
                                    </div>
                                </div>
                                <div class="col-lg-5"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('./../../footer.php'); ?>
<script type="text/javascript">
    $( document ).ready(function() {



    });



</script>
