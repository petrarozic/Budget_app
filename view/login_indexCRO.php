
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous">
    <!--for mail icon-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
      integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
      crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
      integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
      crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?php echo __SITE_URL;?>/view/notify.js"></script>

    <title>Budget-app</title>

  </head>
  <body class="log-body">
    <div class="title">
      <span class="title-name">Budget-app</span>
      <img src="money.png" alt=" " width="40" class="logo">
      <span class="changeLang">
        <form method="post" action="<?php echo __SITE_URL;?>/index.php?rt=language/goToLoginENG" style="display:inline;"> <button type="submit" class="IconButton"> <img src="Brit.png" alt=" " width="20" class="logo"> </button> </form>
        <form method="post" action="<?php echo __SITE_URL;?>/index.php?rt=language/goToLoginCRO" style="display:inline;"> <button type="submit" class="IconButton"> <img src="Cro.png" alt=" " width="20" class="logo"> </button> </form>
      </span>
    </div>

    <div class="container-fluid">
      <div class="row">

    <div class="col-md-6 pull-left">
       <div class="col-lg-11 log-mainPart" style="max-width: 29rem; margin-top: 3rem; float:right;">
        <ul class="nav nav-tabs" id="myTab" role="tablist" >
          <li class="nav-item">
            <?php echo '<a class="nav-link';
             if(isset($l_flag) && $l_flag === 1) echo ' active"'; else echo '"';
             echo ' id="log_in-tab" data-toggle="tab" href="#log_in" role="tab" aria-controls="log_in" aria-selected="true">LOG IN</a>'; ?>
          </li>
          <li class="nav-item">
            <?php echo '<a class="nav-link';
             if(!isset($l_flag) || $l_flag !== 1) echo ' active"'; else echo '"';
             echo ' id="sign_up-tab" data-toggle="tab" href="#sign_up" role="tab" aria-controls="sign_up" aria-selected="false">SIGN UP</a>'; ?>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent" >
          <?php echo '<div class="tab-pane fade';
           if(isset($l_flag) && $l_flag === 1) echo ' show active"'; else echo '"';
           echo ' id="log_in" role="tabpanel" aria-labelledby="log_in-tab">'; ?>

            <div style="margin: 20px;">
              <form class="" action="<?php echo __SITE_URL; ?>/index.php?rt=login/processLogin" method="post">
                <div class="form-group">
                  <label for="username">Korisničko ime</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                </div>

                <div class="form-group">
                  <label for="password">Lozinka</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>

                <button type="submit" class="btn bgreen float-right"> Ulogiraj se! </button>
                <button type="reset" class="btn btn-secondary float-right" style="margin-right: 5px;">Resetiraj</button>
                <br><br>
                <a data-toggle="modal" href="#forgot" class="float-right">Zaboravljena lozinka?</a>
                <br><br>
                <p style="text-align:center"> <?php //if(isset($lmessage)) echo $lmessage; ?> </p>

                <script>
                  var fll = <?php   if ( isset($lmessage)) echo json_encode($_SESSION['flag']);   else echo "2"; ?>;
                  var mess = <?php if ( isset($lmessage)) echo json_encode($lmessage);           else echo "undefined"; ?>;

                    if(fll.toString() === '0')
                        $.notify(mess, "error");

                    else if(fll.toString() === '1')
                        $.notify(mess, "success");
                </script>
              </form>
            </div>
          </div>
          <?php echo '<div class="tab-pane fade';
           if(!isset($l_flag) || $l_flag !== 1) echo ' show active"'; else echo '"';
           echo ' id="sign_up" role="tabpanel" aria-labelledby="sign_up-tab">'; ?>

            <div style="margin: 20px;">
              <?php
              if(isset($output))
                echo '<p> '. $output. ' </p>';

              else{ ?>
                <form class="" action="<?php echo __SITE_URL; ?>/index.php?rt=login/processSignUp" method="post">

                  <div class="form-group">
                    <label for="username">Korisničko ime</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Korisničko ime">
                  </div>

                  <div class="form-group">
                    <label for="password">Lozinka</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Lozinka">
                    <small id="passwordHelpBlock" class="form-text text-muted">
                      Vaša lozinka se mora sastojati od 3 do 20 slova, brojeva, specijalnih znakova ili smajlica.
                    </small>
                  </div>

                  <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="email@primjer.com ">
                      <small id="emailHelp" class="form-text text-muted"> Vaša email adresa je potrebna za verifikaciju računa.</small>
                  </div>

                  <button type="submit" class="btn bgreen float-right">Priključi se!</button>
                  <button type="reset" class="btn btn-secondary float-right" style="margin-right: 5px;">Resetiraj</button>
                  <br><br><br>

                  <script>
                        var fls = <?php if ( isset($smessage)) echo json_encode($_SESSION['flag']); else echo "2"?>;
                        var mess = <?php if ( isset($smessage)) echo json_encode($smessage); else echo "undefined";?>;

                        if(fls.toString() === '0')
                            $.notify(mess, "error");

                        else if(fls.toString() === '1')
                            $.notify(mess, "success");
                  </script>
                </form>
              <?php  } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-4 pull-right">
      <div class="col-lg-11" style="margin-top: 3rem;">
        <div class="text-center">
          <img src="money.png" alt=" " width="215" class="image">
        </div>
        <p class="description"> Budget-app je aplikacija za praćenje osobnih troškova. <br> Trošite pametno i uživajte u aplikaciji!</p>
      </div>
    </div>

  </div>

    <script type="text/javascript">
      $('#myTab a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
      })
    </script>

    <!--- Modal - forgot password--->
    <div class="modal" id="forgot" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"> Zaboravljena lozinka </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <small id="passwordHelpBlock" class="form-text text-muted">
              Nova lozinka biti će poslana na Vašu email adresu.
            </small>
            <br>
            <form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=profile/forgotPassword">
              <div class="form-group">
                <label for="username_forgot"> Vaše Korisničko ime: </label>
                <input type="text" class="form-control" placeholder="korisničko ime" name="username_forgot">
               </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn bgreen">Submit</button>
          </div>
          </form>
        </div>
      </div>
    </div>
<?php
  $_SESSION['lang'] = 'CRO';
  $_SESSION['page'] = 'login';
  require_once __SITE_PATH . '/view/_footerCRO.php';
?>
