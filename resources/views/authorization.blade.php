<!DOCTYPE html>
<html lang="en">

<head>
  <title>HR Portal</title>
  <meta charset="utf-8">
  <meta http-equiv=content-type content="text/html; charset=utf-8"/>
  <meta name=keywords content="HRPortal, Universitas Pakuan, UNPAK, HR Portal Unpak, Sistem Informasi HR Unpak"/>
  <meta name=robots content="noindex, nofollow"/>
  <meta name=description content="HRPortal Universitas Pakuan (UNPAK) adalah platform yang dirancang untuk pengelolaan sumber daya manusia secara efisien dan efektif di lingkungan Universitas Pakuan."/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <link rel="canonical" href="https://hrportal.unpak.ac.id/" />
  <link href="{{ Utility::loadAsset('assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ Utility::loadAsset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <!--<link href="{{ Utility::loadAsset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<style>
  small {
    font-size: 18px;
    font-weight: 400;
    color: #012970;
    font-family: "Nunito", sans-serif;
  }
</style>
<body>
      <section class="bg-light p-3 p-md-4 p-xl-5">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-12 col-xxl-11">
              <div class="card border-light-subtle shadow-sm">
                <div class="row g-0">
                  <div class="col-12 col-md-6">
                    <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="{{ Utility::loadAsset('assets/img/logo-img-1.jpg') }}" alt="background">
                  </div>
                  <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                    <div class="col-12 col-lg-11 col-xl-10">
                      <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="row">
                          <div class="col-12">
                            <div class="mb-5">
                              <div class="text-center mb-4">
                                <a href="#!">
                                  <img src="{{ Utility::loadAsset('assets/img/logo.webp') }}" alt="logo" width="100" height="100">
                                </a>
                              </div>
                              <h4 class="text-center">Login</h4>
                            </div>
                          </div>
                        </div>
                        {{ Utility::showNotif() }}
                        <form action="{{route('auth.authentication')}}" method="post" id="loginForm">
                          @csrf
                          <div class="row gy-3 overflow-hidden">
                            <div class="col-12">
                              <div class="form-floating mb-3">
                                <input type="text" name="username" class="form-control" id="yourUsername" placeholder="Username" required>
                                <label for="username" class="form-label">Username</label>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                <label for="password" class="form-label">Password</label>
                              </div>
                            </div>
                            <div class="col-12 d-grid gap-4">
                              <div class="g-recaptcha" data-sitekey="6Ldt5p0qAAAAAOAmxxoLG-WXnKLN-cZkUL0fvym8"></div>
                              <button class="btn btn-dark" type="submit">Login</button>
                              <div class="col-12 d-grid gap-2">
                                <a href="https://unpak.link/hrportal_app" target="_blank" class="btn btn-success"><i class="bi bi-android2"></i> Versi Android</a>
                                <a href="{{ Utility::loadAsset('USER GUIDE DOSEN & TENDIK NON STRUKTURAL (26-08-2024).pdf')}}" target="_blank" class="btn btn-primary"><i class="bi bi-journal-album"></i> Manual Guide</a>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

  <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  @stack('scripts')
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-XYJ8LJNYGV"></script>
<script>
    // Function to check if reCAPTCHA is checked
    function validateRecaptcha() {
      var response = grecaptcha.getResponse();
      if (response.length == 0) { 
        alert("Please complete the reCAPTCHA to proceed.");
        return false;
      }
      return true;
    }

    // Add event listener to form submit
    $("#loginForm").on("submit", function(event) {
      if (!validateRecaptcha()) {
        event.preventDefault(); // Prevent form submission if reCAPTCHA not checked
      }
    });
  </script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-XYJ8LJNYGV');
</script>
</body>

</html>
