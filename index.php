<?php

include('includes/producttable.php');

// redirect('productsystem/ProductGroupList.php');
include("includes/purchasetable.php");
include("includes/salestable.php");

header("Location: productsystem/ProductGroupList.php");


?>