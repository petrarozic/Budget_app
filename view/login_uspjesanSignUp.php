<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous">

<!--potrebno za nav-tabs-->
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
<!-- do tuda -->
    <title></title>

    <style media="screen">
      .col-centered{
        float: none;
        margin: 0 auto;
        }
    </style>
  </head>
  <body>
    <div class="col-lg-11 col-centered" style="width: 27rem;">
        <ul class="nav nav-tabs" id="myTab" role="tablist" >
          <li class="nav-item">
            <a class="nav-link" id="log_in-tab" data-toggle="tab" href="#log_in" role="tab" aria-controls="log_in" aria-selected="true">LOG IN</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" id="sign_up-tab" data-toggle="tab" href="#sign_up" role="tab" aria-controls="sign_up" aria-selected="false">SIGN UP</a>
          </li>

        </ul>
        <div class="tab-content" id="myTabContent" >
          <div class="tab-pane fade show active" id="log_in" role="tabpanel" aria-labelledby="log_in-tab">

            <div style="margin: 20px;"> <!-- style="width: 27rem;"-->
              <form class="" action="<?php echo __SITE_URL; ?>/index.php?rt=login/obradaLogina" method="post">

              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
              </div>

              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>

              <button type="submit" class="btn btn-success">LOG IN</button>
              <button type="reset" class="btn btn-secondary">Reset</button>


            </form>
            </div>

          </div>
          <div class="tab-pane fade" id="sign_up" role="tabpanel" aria-labelledby="sign_up-tab">

            <div style="margin: 20px;">
							<p>
									Registracija je uspješno provedena, sad se možete ulogirati.
							</p>
            </div>
          </div>
        </div>

    </div>



    <script type="text/javascript">
      $('#myTab a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
      })
    </script>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
