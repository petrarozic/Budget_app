<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Budget-app</title>
    <link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
        integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
        crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <script src="<?php echo __SITE_URL;?>/view/notify.js"></script>
    <script src="<?php echo __SITE_URL;?>/view/CategoriesInSelect.js"></script>
    <script src="<?php echo __SITE_URL;?>/view/FillingEditModule.js"></script>
    <script src="<?php echo __SITE_URL;?>/view/FillingEditModuleC.js"></script>
    <script src="<?php echo __SITE_URL;?>/view/SortTable.js"></script>

    <link rel="shortcut icon" href="<?php echo __SITE_URL;?>/budget.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo __SITE_URL;?>/budget.ico" type="image/x-icon">

  </head>
<?php $_SESSION['lang'] = 'ENG'?>
<body class="body_">
  <div class="title">
    <span class="title-name">Budget-app</span>
    <img src="money.png" alt=" " width="40" class="logo">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user"> </i>
    </button>
    <div class="dropdown-menu" id="link-dropdown" aria-labelledby="dropdownMenu">
     <ul class="link-ul" id="personal">
       <li class="link-item
             <?php if ($extension === 'profile') echo "link-active"; ?>
         ">
       <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=profile">PROFILE</a>
       </li>
       <li class="link-item">
         <form method="post" action="<?php echo __SITE_URL;?>/index.php?rt=language/goToENGPage" style="display:inline;"> <button type="submit" class="IconButton"> <img src="Brit.png" alt=" " width="20" class="logo"> </button> </form>
         <form method="post" action="<?php echo __SITE_URL;?>/index.php?rt=language/goToCROPage" style="display:inline;"> <button type="submit" class="IconButton"> <img src="Cro.png" alt=" " width="20" class="logo"> </button> </form>
       </li>
       <li class="link-item">
         <?php if ($_SESSION['lang'] == 'ENG' )
           echo '<form class="logout" action="'.__SITE_URL.'/index.php?rt=language/goToLoginENG" method="post">';
           else if ($_SESSION['lang'] == 'CRO' )
           echo '<form class="logout" action="'.__SITE_URL.'/index.php?rt=language/goToLoginCRO" method="post">';
          ?>
           <input type="hidden" name="logout">
           <button type="submit" class="btn btn-outline-dark">logout</button>
         </form>
       </li>
     </ul>
    </div>
  </div>

  <?php require_once __SITE_PATH . '/view/_activPage.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2 pull-left">
      <ul class="link-ul">
        <li class="link-item
              <?php if ($extension === 'home') echo "link-active"; ?>
        ">
          <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=home">HOME</a>
        </li>
        <li class="link-item
              <?php if ($extension === 'incomes') echo "link-active"; ?>
          ">
          <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=transactions/incomes">INCOMES</a>
        </li>
        <li class="link-item
              <?php if ($extension === 'expenses') echo "link-active"; ?>
          ">
          <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=transactions/expenses">EXPENSES</a>
        </li>
        <li class="link-item
              <?php if ($extension === 'statistics') echo "link-active"; ?>
          ">
          <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=statistics">STATISTICS</a>
        </li>
        <li class="link-item
              <?php if ($extension === 'category') echo "link-active"; ?>
          ">
          <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=category">CATEGORY</a>
        </li>
     </ul>
   </div>

   <?php require_once __SITE_PATH . '/view/modal_addTransaction.php'; ?>

   <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10 pull-right">
    <div class="info">
      <div class="row">
        <div class="info-box" id="plus">
          <button class="AddButton" type="submit" name="AddTransaction" data-toggle="modal" data-target="#AddTransactionModal" > <i class="fas fa-plus" style="font-size:2em;"></i></button>
        </div>
        <!-- ovdje treba dodatiprae vrijednosti za prekoracenje-->
        <div class="info-box">
          <?php  if($_SESSION['d_limit'] >= 0){
          echo '<span class="info-elem"> Daily limit: </span>';
          echo '<span class="info-amount">+'.$_SESSION['d_limit'].'</span>';
        }
        else{
          echo '<span class="info-elem" style="color: #b30000"> Daily limit: </span>';
          echo '<span class="info-amount" style="color: #b30000">'.$_SESSION['d_limit'].'</span>';
        }?>
        </div>
        <div class="info-box">
          <?php  if( $_SESSION['w_limit'] >= 0 ){
          echo '<span class="info-elem"> Weekly limit: </span>';
          echo '<span class="info-amount">+'.$_SESSION['w_limit'].'</span>';
        }
        else{
          echo '<span class="info-elem" style="color: #b30000"> Weekly limit: </span>';
          echo '<span class="info-amount" style="color: #b30000">'.$_SESSION['w_limit'].'</span>';
        }?>
        </div>
        <div class="info-box">
          <?php  if($_SESSION['m_limit'] >= 0){
          echo '<span class="info-elem"> Monthly limit: </span>';
          echo '<span class="info-amount">+'.$_SESSION['m_limit'].'</span>';
        }
        else{
          echo '<span class="info-elem" style="color: #b30000"> Monthly limit: </span>';
          echo '<span class="info-amount" style="color: #b30000">'.$_SESSION['m_limit'].'</span>';
        }?>
        </div>
      </div>
    </div>
