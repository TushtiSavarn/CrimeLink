<?php
    $servername = "localhost";
    $user = "root";
    $pass = "";
    $database = "crimelink";
    $conn = mysqli_connect($servername, $user, $pass, $database);

    if (!$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }

        
        $reportid = $_POST['reportid'];

        $doc_name=$_FILES['document']['name'];
        $tmp=explode(".",$doc_name);
        $document=round(microtime(true)).'.'.end($tmp);
        $uploadpath="uploads/".$document;
        move_uploaded_file($_FILES['document']['tmp_name'],$uploadpath);

       # $video_name=$_FILES['video']['name'];
       # $video=$_FILES['video']['tmp_name'];
       # $folder='uploads/'.$video_name;
       # move_uploaded_file($video,$folder);

        
        $image_name=$_FILES['image']['name'];
        $tmp=explode(".",$image_name);
        $image=round(microtime(true)).'.'.end($tmp);
        $uploadpath="uploads/".$image;
        move_uploaded_file($_FILES['image']['tmp_name'],$uploadpath);

       # $audio_name = $_FILES['audio']['name'];  
       #$tmp = explode(".", $audio_name);   
       #$audio = round(microtime(true)) . '.' . end($tmp);  
       #$uploadpath = "uploads/" . $audio;  
       # move_uploaded_file($_FILES['audio']['tmp_name'], $uploadpath);  


        

        $query="insert into evidence (photo, document, reportid) values ('$image','$document',' $reportid')";
        if (mysqli_query($conn, $query)) 
        {
            // After successful upload
            header("Location: submitevidencecode.html?success=true");
            exit();
        } 
        else 
        {
            echo "Error: " . mysqli_error($conn);
        }
    
    mysqli_close($conn)
?>