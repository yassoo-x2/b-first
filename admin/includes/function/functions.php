<?php
    function getTitle() {
        global $pageTitle;
        if (isset($pageTitle)){
            echo $pageTitle ;
        }else {
            echo 'defulte';
    }
    }
    /*
    **Redirect Function[this fiunction accept parameters]
    **$erorrmsg echo The erorr Messege
    **$secounds 
    */
    function redirectHome($erorrMsg,$secounds){

    	echo "<div>$erorrMsg</div>";

    	echo "<div class='alert alert-info'>You will Go to Home Page After $secounds secounds</div>";
    	header("refresh:$secounds;url=index.php");
    	exit();
    }
 function redirectAdmin($Succes,$secounds){
    	echo "<div class='alert alert-info'>$Succes</div>";
    	header("refresh:$secounds;url=members.php");
    	exit();
    }
    
    /*
    **check item Functions
    **Function to check items in data base[Function Accept parameters]
    **$select = Item to select [exp:user , item, category]
    **$from   = Table select from [exp: user ,item,category]
    **$value  = The value of select[exp:fooz, box]
    */
	function checkItem($select,$from,$value){

    	global $con;
    	$statement = $con->prepare("SELECT $select FROM $from WHERE $select= ?");
    	$statement->execute(array($value));
    	$count  = $statement->rowcount();
    	return $count;
    }

/*
**count number of items functions 
**function to oount number of items rows
**$item = the item to count
**$table = the table to choose from
*/
function countItems($item, $table){
    global $con;

     $stmt5 = $con->prepare(" SELECT COUNT($item) FROM $table  ");

      $stmt5->execute();

      return $stmt5->fetchColumn();
}

function countNum($item, $table ,$condtion,$colum ){
    global $con;


     $stmt6 = $con->prepare(" SELECT COUNT($item) FROM $table WHERE $condtion = '$colum'");

      $stmt6->execute();

      return $stmt6->fetchColumn();
}

