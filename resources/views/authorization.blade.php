<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>HR Portal</title>
  <link href="{{ Utility::loadAsset('assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ Utility::loadAsset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ Utility::loadAsset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
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
  <main>
    <div class="container">

      <section class="section register min-vh-100 flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 flex-column align-items-center justify-content-center text-center">

              <div class="flex-row align-items-center justify-content-center py-4">
                <a href="#" class="logo text-decoration-none align-items-center">
                  <img src="{{ Utility::loadAsset('assets/img/logo.webp') }}" alt="">
                  <span class="d-none d-lg-block">HR Portal</span>
                </a>
                <!-- <small class="d-none d-lg-block">Sistem Informasi Penelitian, Abdimas dan PubliKaSI</small> -->
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div>
                    {{ Utility::showNotif() }}
                    <h5 class="card-title text-center pb-0 fs-4">Masuk ke akun Anda</h5>
                    <p class="text-center small">Masukkan username & password Anda untuk masuk</p>
                  </div>
                  
                  <form class="row g-3 needs-validation" action="{{route('auth.authentication')}}" method="post">
                    @csrf
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">NIDN/Username</label>
                      <div class="input-group has-validation">
                        <!-- <span class="input-group-text" id="inputGroupPrepend">@</span> -->
                        <input type="text" name="username" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Silakan Masukkan NIDN / Nama Pengguna Anda.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Silahkan Masukkan Kata Sandi Anda!</div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->
  <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  @stack('scripts')
  <script>

</script>
</body>

</html>