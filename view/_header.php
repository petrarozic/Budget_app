  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta charset="utf-8">
      <title></title>
      <link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style.css">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
          crossorigin="anonymous">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    </head>

  <body>
    <div class="title">
      <h1>Budget-app</h1>
      <form class="logout" action="<?php echo __SITE_URL; ?>/index.php?rt=login" method="post">
        <input type="hidden" name="logout">
        <button type="submit" class="btn btn-outline-light" style="float:right; margin-top:25px;">logout</button>
      </form>
    </div>

    <div class="row">
      <div class="col-md-2 pull-left side_bar">
        <ul class="nav-ul">
          <li class="nav-item active">
            <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=home">HOME</a>
          </li>
          <li class="nav-item">
            <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=transactions/incomes">INCOMES</a>
          </li>
          <li class="nav-item">
            <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=transactions/expenses">EXPENSES</a>
          </li>
          <li class="nav-item">
            <a class="" href="#">STATISTICS</a>
          </li>
          <li class="nav-item">
          <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=profile">PROFILE</a>
          </li>
       </ul>
      </div>
      <!--DIV za sredisnji dio stranice -->
      <div class="col-md-10 pull-right">
