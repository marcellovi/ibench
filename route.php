<?php

if(isset($_POST['search_text']) && !empty($_POST['search_text'])){
    $txt = $_POST['search_text'];
    echo "<script>window.location.href = 'https://www.ibench.com.br/_site/searched/$txt';</script>";
}



