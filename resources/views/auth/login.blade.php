<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-coffee-dark: #3c2415;
            --bs-coffee-medium: #5d4037;
            --bs-coffee-light: #8d6e63;
            --bs-orange-dark: #bf360c;
            --bs-orange-medium: #d84315;
        }

        /* Hide scrollbar but allow scrolling */
        html {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        html::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }

        body {
            scrollbar-width: none;
            -ms-overflow-style: none;
            overflow-x: hidden;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        .auth-container::-webkit-scrollbar {
            display: none;
        }

        .auth-container {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        body {
            background: linear-gradient(135deg, var(--bs-coffee-dark) 0%, var(--bs-coffee-medium) 50%, var(--bs-orange-dark) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }

        /* Pure Glass Effect - No White Background */
        .auth-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 25px;
            box-shadow:
                0 15px 35px rgba(60, 36, 21, 0.4),
                inset 0 2px 0 rgba(255, 255, 255, 0.1),
                inset 0 -2px 0 rgba(0, 0, 0, 0.05);
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            padding: 40px;
        }

        /* Glass effect shimmer overlay */
        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.08) 0%,
                    rgba(255, 255, 255, 0.02) 50%,
                    rgba(255, 255, 255, 0.08) 100%);
            border-radius: 25px;
            pointer-events: none;
        }

        .form-content {
            position: relative;
            z-index: 1;
        }

        .form-header .icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, var(--bs-coffee-dark), var(--bs-orange-dark));
            border-radius: 50%;
            box-shadow:
                0 15px 40px rgba(60, 36, 21, 0.4),
                0 5px 15px rgba(191, 54, 12, 0.3);
        }

        .form-title {
            color: rgba(255, 255, 255, 0.95);
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.8);
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding-left: 55px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow:
                0 0 0 0.2rem rgba(255, 255, 255, 0.15),
                0 5px 20px rgba(191, 54, 12, 0.3);
            background: rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.95);
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            z-index: 10;
            font-size: 1.1rem;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .form-check-input {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .form-check-input:checked {
            background: linear-gradient(45deg, var(--bs-coffee-dark), var(--bs-orange-dark));
            border-color: rgba(255, 255, 255, 0.4);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.15);
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .btn-coffee {
            background: linear-gradient(45deg, var(--bs-coffee-dark), var(--bs-orange-dark));
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            font-weight: 700;
            color: white;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow:
                0 8px 25px rgba(60, 36, 21, 0.4),
                0 3px 10px rgba(191, 54, 12, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .btn-coffee:hover {
            transform: translateY(-3px);
            box-shadow:
                0 15px 35px rgba(60, 36, 21, 0.5),
                0 5px 15px rgba(191, 54, 12, 0.4);
            background: linear-gradient(45deg, var(--bs-orange-dark), var(--bs-coffee-dark));
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .register-link {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .register-link a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .register-link a:hover {
            color: rgba(255, 255, 255, 1);
            text-shadow: 0 2px 8px rgba(255, 255, 255, 0.3);
        }

        .register-link p {
            color: rgba(255, 255, 255, 0.8);
        }

        .copyright {
            color: rgba(255, 255, 255, 0.7);
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .invalid-feedback {
            color: #ff6b6b;
            font-weight: 500;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .form-control.is-invalid {
            border-color: rgba(255, 107, 107, 0.6);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
        }
    </style>
</head>

<body>
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 py-3">
        <div class="auth-container col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <div class="form-content">
                <div class="form-header text-center mb-4">
                    <div class="icon d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-sign-in-alt fs-2 text-white"></i>
                    </div>
                    <h1 class="form-title h3 mb-2">Selamat Datang</h1>
                    <p class="form-subtitle mb-0">Masuk ke akun Anda</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="position-relative">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="nama@example.com" value="{{ old('email') }}"
                                required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="position-relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Masukkan password Anda" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <button class="btn btn-coffee btn-lg py-3" type="submit">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
                        </button>
                    </div>

                    <div class="register-link text-center mb-3 pt-3">
                        <p class="mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
                    </div>

                    <p class="copyright text-center small mb-0">© {{ date('Y') }} All rights reserved</p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
