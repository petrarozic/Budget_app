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
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    </head>

  <body class="body_">
    <div class="title">
      <span class="title-name">Budget-app</span>

      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        IKONA
      </button>
      <div class="dropdown-menu" id="link-dropdown" aria-labelledby="dropdownMenu_kajJeTo">
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
          <li class="link-item link-active
                <?php if ($extension === 'expenses') echo "link-active"; ?>
            ">
            <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=transactions/expenses">EXPENSES</a>
          </li>
          <li class="link-item
                <?php if ($extension === '#') echo "link-active"; ?>
            ">
            <a class="" id="STATISTICS" href="#">STATISTICS</a>
          </li>
       </ul>
       <div class="dropdown-divider"></div>
       <ul class="link-ul">
         <li class="link-item
               <?php if ($extension === 'profile') echo "link-active"; ?>
           ">
         <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=profile">PROFILE</a>
         </li>
         <li class="link-item">
           <form class="logout" action="<?php echo __SITE_URL; ?>/index.php?rt=login" method="post">
             <input type="hidden" name="logout">
             <button type="submit" class="btn btn-outline-light">logout</button>
           </form>
         </li>
       </ul>
      </div>
    </div>

    <?php require_once __SITE_PATH . '/view/_activPage.php'; ?>

    <div class="row">
      <div class="col-md-2 pull-left side_bar">
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
                <?php if ($extension === '#') echo "link-active"; ?>
            ">
            <a class="" href="#">STATISTICS</a>
          </li>
          <li class="link-item
                <?php if ($extension === 'profile') echo "link-active"; ?>
            ">
          <a class="" href="<?php echo __SITE_URL; ?>/index.php?rt=profile">PROFILE</a>
          </li>
       </ul>
      </div>
      <!--DIV za sredisnji dio stranice -->
      <div class="col-md-10 pull-right mainContent">
          <div class="info">
            <div class="row">
              <div class="info-box">
                <span class="info-elem"> Daily limit </span>
                <span class="info-amount">+100</span>
              </div>
              <div class="info-box">
                <span class="info-elem" style="color: indianred"> Weekly limit </span>
                <span class="info-amount" style="color: indianred">-20</span>
              </div>
              <div class="info-box">
                <span class="info-elem"> Monthly limit </span>
                <span class="info-amount">+100</span>
              </div>
            </div>
          </div>
