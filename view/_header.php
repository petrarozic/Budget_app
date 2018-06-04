  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta charset="utf-8">
      <title></title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
      <link rel="stylesheet" href="style.css">
      <style>
        .title{
          background-color:  #202020;
          color: white;
          padding: 30px;
        }

        h1{
          display: inline;
        }
        li{
        	 text-align: center;
           padding: 20%;
        }
        td, th{
          text-align: center;
        }

        .logout{
          display: inline;
        }
      </style>
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
      <nav class="col-md-2 pull-left navbar navbar-light bg-light">
        <div class="navbar-brand" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">HOME<span class="sr-only">(current)</span></a>
            </li>
            <li>
              <a class="nav-link" href="<?php echo __SITE_URL; ?>/index.php?rt=transactions/incomes">INCOMES</a>
            </li>
            <li>
              <a class="nav-link" href="<?php echo __SITE_URL; ?>/index.php?rt=transactions/expenses">EXPENSES</a>
            </li>
            <li>
              <a class="nav-link" href="#">STATISTICS</a>
            </li>
            <li>
            <a class="nav-link" href="<?php echo __SITE_URL; ?>/index.php?rt=profile">PROFILE</a>
            </li>
         </ul>
        </div>
      </nav>
      <!--DIV za sredisnji dio stranice -->
      <div class="col-md-10 pull-right">
