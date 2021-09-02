<?php 
require_once "connection.php";
include "includes/header.php";
$sites_stmt = $pdo->query("SELECT * FROM site WHERE isactive = 1 and isvisible = 1");
$sites = $sites_stmt->fetchAll();
$app_role_stmt = $pdo->query("SELECT * FROM app_role WHERE isactive = 1 and isvisible = 1");
$app_roles = $app_role_stmt->fetchAll();
$default_password = "flipkart@12345";
if(isset($_POST['submit'])){
	if(empty(trim($_POST["fname"])) || empty(trim($_POST["lname"])) || empty(trim($_POST["email_id"])) || empty(trim($_POST['pword'])) || empty(trim($_POST['emp_id'])) || empty(trim($_POST['ldap_id'])) || empty(trim($_POST['default_site_id'])) || empty(isset($_POST['site_ids'])) || empty(trim($_POST['default_approle_id'])) || empty(isset($_POST['approle_ids']))){
        $err = "Please fill the mandatory fields.";
    } 
    else{
    	$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email_id = $_POST['email_id'];
		$pword = password_hash($_POST['pword'], PASSWORD_DEFAULT);
		$emp_id = $_POST['emp_id'];
		$ldap_id = $_POST['ldap_id'];
		$default_site_id = $_POST['default_site_id'];
		$site_ids = implode(",",$_POST['site_ids']);
		$default_approle_id = $_POST['default_approle_id'];
		$approle_ids = implode(",",$_POST['approle_ids']);
		$isvisible = ($_POST['isvisible'] == "on") ? "1" : NULL;
		$isactive = ($_POST['isactive'] == "on") ? "1" : NULL;

		$sql = "SELECT user_key, ad_id, pword FROM app_users WHERE emp_id = :emp_id";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(":emp_id", $param_emp_id, PDO::PARAM_STR);
		$param_emp_id = $emp_id;
		$stmt->execute();
		if($stmt->rowCount() >= 1){
		    $err = "User details already exist";
		}
		else{
			$data = [
			    'fname' => $fname,
			    'lname' => $lname,
			    'email_id' => $email_id,
			    'pword' => $pword,
			    'emp_id' => $emp_id,
			    'ldap_id' => $ldap_id,
			    'default_site_id' => $default_site_id,
			    'site_ids' => $site_ids,
			    'default_approle_id' => $default_approle_id,
			    'approle_ids' => $approle_ids,
			    'isvisible' => $isvisible,
			    'isactive' => $isactive,
			];
			$sql = "INSERT INTO app_users (fname, lname, email_id, pword, emp_id, ldap_id, default_site_id, site_ids, default_approle_id, approle_ids, isvisible, isactive) 
			VALUES (:fname, :lname, :email_id, :pword, :emp_id, :ldap_id, :default_site_id, :site_ids, :default_approle_id, :approle_ids, :isvisible, :isactive)";
			$stmt= $pdo->prepare($sql);
			if($stmt->execute($data)){
				$_POST = array();
				$success = "User details added";
			}
			else{
				$err = "Some problem occured";
			}
			
		}
    }
	
}

?>
<div class="page-contentbar">
<div id="page-right-content">
<div class="container">
<div class="row">
                            <div class="col-sm-12">
                                <h4 class="header-title m-t-0 m-b-20">Add User</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
 		
                                <div class="row">
        <?php 
        if(!empty($err)){
            echo '<div class="alert alert-danger">' . $err . '</div>';
        }  
        if(!empty($success)){
            echo '<div class="alert alert-success">' . $success . '</div>';
        }        
        ?>
                                <form method="post" id="user" action="" class="form-horizontal" role="form">
                         <div class="col-md-6">
                                        
                                        <div class="form-group">
                                    <label class="col-md-2 control-label">First Name<span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="fname" class="form-control" value="<?php if(isset($_POST['fname'])){ echo $_POST['fname'];} ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Last Name<span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="lname" class="form-control" value="<?php if(isset($_POST['lname'])){ echo $_POST['lname'];} ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="example-email">Email<span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input type="email" value="<?php if(isset($_POST['email_id'])){ echo $_POST['email_id'];} ?>" id="email_id" name="email_id" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Password<span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input type="password" name="pword" class="form-control" value="<?php echo $default_password; ?>" id="pword">
                                        <input type="checkbox" onclick="showpassword()">Show Password
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Employee ID<span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" value="<?php if(isset($_POST['emp_id'])){ echo $_POST['emp_id'];} ?>"  name="emp_id">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">LDAP ID<span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" value="<?php if(isset($_POST['ldap_id'])){ echo $_POST['ldap_id'];} ?>" name="ldap_id">
                                    </div>
                                </div>
                                        
                                    </div>

                                    <div class="col-md-6">
                                        

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Default Site<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="default_site_id">
                                            <?php
                                            foreach ($sites as $site){
                                            	$selected="";
                                            	if(isset($_POST['default_site_id'])){
                                            		if($_POST['default_site_id'] == $site['site_id'])
                                            		{
                                            		    $selected="selected = 'selected'";
                                            		}
                                            	}
                                                echo "<option value=".$site['site_id']." ".$selected." >".$site['site_name']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Site Access<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <!-- <select multiple="" class="form-control" name = "site_ids[]">
                                            <?php
                                             foreach ($sites as $site){
                                                echo "<option value=".$site['site_id'].">".$site['site_name']."</option>";
                                            }
                                            ?>
                                        </select> -->
                                         <div class="dropdown-mul-1">
									        <select style="display:none"  name="site_ids[]" id="" multiple placeholder="Select"> 
									          	<?php
	                                             foreach ($sites as $site){
	                                                echo "<option value=".$site['site_id'].">".$site['site_name']."</option>";
	                                            }
	                                            ?>
									        </select>
									      </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Default Role<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="default_approle_id">
                                        <?php
                                        foreach ($app_roles as $app_role){
                                        	$selected="";
                                        	if(isset($_POST['default_approle_id'])){
                                        		if($_POST['default_approle_id'] == $app_role['role_id'])
                                        		{
                                        		    $selected="selected = 'selected'";
                                        		}
                                        	}
                                            echo "<option value=".$app_role['role_id']." ".$selected." >".$app_role['role_name']."</option>";
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Roles<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <!-- <select multiple="" class="form-control" name = "approle_ids[]">
                                        <?php
                                        foreach ($app_roles as $app_role){
                                            echo "<option value=".$site['role_id'].">".$app_role['role_name']."</option>";
                                        }
                                        ?>
                                        </select> -->
                                        <div class="dropdown-mul-2">
									        <select style="display:none"  name="approle_ids[]" id="" multiple placeholder="Select"> 
									          	<?php
	                                            foreach ($app_roles as $app_role){
		                                            echo "<option value=".$site['role_id'].">".$app_role['role_name']."</option>";
		                                        }
	                                            ?>
									        </select>
									      </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                <label class="col-sm-2 control-label">Visible</label>
                                <div class="col-sm-10 checkbox checkbox-success checkbox-circle">
                                    <input id="checkbox-10" type="checkbox" name="isvisible" checked="">
                                    <label for="checkbox-10">
                                    </label>
                                </div>     
                                </div>

                                <div class="form-group">
                                <label class="col-sm-2 control-label">Active</label>
                                <div class="col-sm-10 checkbox checkbox-success checkbox-circle">
                                    <input id="checkbox-10" type="checkbox" name="isactive" checked="">
                                    <label for="checkbox-10">
                                    </label>
                                </div>     
                                </div>
                                
                                <button type="submit" name="submit" style="float:right;" class="btn btn-primary">Submit</button>
                                        
                                    </div>
</form>
                                </div>
                                <!-- end row -->
                            </div> <!-- end col -->
                        </div>
                        </div>
                        </div>
                        </div>
    <?php include "includes/footer.php"; ?>



</body>
</html>