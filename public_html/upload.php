<?php
	session_start();
	include_once 'classes.php';
	include_once 'connection.php';
	$username=$_SESSION['username'];

	$blogger = new Blogger($conn);
	  
	  $permission = $blogger->is_permitted($username);
	  if($permission == false)
	  {
	  	echo "<script type='text/javascript'>alert('You are not Permitted to write blog contact admin');window.location.href = 'userhome.php';</script>";
	  }

	$title = htmlspecialchars($_POST['title'],ENT_QUOTES);
  	$category = htmlspecialchars($_POST['category'],ENT_QUOTES);
  	$desc = htmlspecialchars($_POST['desc'],ENT_QUOTES);
	  $username=$_SESSION['username'];
		$target_dir = "images/";
		$target_file = $target_dir.basename($_FILES["blog_image"]["name"]);
    $target_filep = $target_dir.$_FILES["blog_image"]["name"];
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["blog_image"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
	// Check file size
	if ($_FILES["blog_image"]["size"] > 500000) {
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
  print_r($_FILES);
  echo $target_filep;
	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["blog_image"]["tmp_name"], $target_filep)) {

	        echo "The file ". basename( $_FILES["blog_image"]["name"]). " has been uploaded.";

          $publish = $blogger->publish($username,$title,$category,$desc,$target_file);
          if($publish == true)
          {
            echo "<script type='text/javascript'>alert('Published Succesfully');window.location.href = 'userhome.php';</script>";
          }
          if($publish == false){
            echo "<script type='text/javascript'>alert('There is something wrong, your blog is saved as draft');window.location.href = 'userhome.php';</script>";
          }
          if($publish == "No blogger id found"){
            echo "No id found";
          }
	    } 
      else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
?>