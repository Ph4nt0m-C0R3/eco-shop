<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EcoShop | Reset Password</title>

    <!-- Favicon -->
        <link rel="icon"
        type="image/png"
        href="{{ setting('favicon')
            ? asset('storage/' . setting('favicon'))
            : asset('default/favicon.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f8f1, #b7e4c7, #74c69d);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        input[type="password"]::-ms-reveal {
            display: none;
        }

        .reset-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            max-width: 480px;
            width: 100%;
            padding: 50px 40px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
            text-align: center;
            animation: fadeUp 0.8s ease forwards;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reset-card .eco-logo {
            width: 80px;
            height: 80px;
            background: #fff;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 35px rgba(0,0,0,0.12);
            animation: floatIcon 4s ease-in-out infinite;
        }

        @keyframes floatIcon {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .reset-card h2 {
            font-weight: 700;
            color: #27ae60;
            margin-bottom: 15px;
        }

        .reset-card p {
            color: #2d6a4f;
            font-size: 0.95rem;
            margin-bottom: 25px;
        }

        /* Floating Inputs */
        .form-floating .form-control {
            border-radius: 12px;
            padding: 14px 16px;
            box-shadow: none;
            transition: box-shadow 0.3s, border-color 0.3s;
        }

        .form-floating .form-control:focus {
            border-color: #27ae60;
            box-shadow: 0 0 15px rgba(39,174,96,0.3);
        }

        /* Password toggle */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .btn-eco {
            background-color: #27ae60;
            border-radius: 12px;
            border: none;
            padding: 14px;
            font-weight: 600;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .btn-eco:hover {
            background-color: #1e8449;
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(39,174,96,0.4);
        }

        .btn-eco:active {
            transform: scale(0.97);
        }

        .input-error {
            font-size: 0.85rem;
            color: #d9534f;
            margin-top: 4px;
            text-align: left;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2d6a4f;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            color: #1e8449;
        }

        /* Remove Bootstrap invalid icons, keep red border */
        .form-control.is-invalid {
            background-image: none !important;
            padding-right: 1rem; /* reset spacing */
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.15);
        }

    </style>
</head>

<body>

<div class="reset-card">

    <div class="eco-logo">
        <i class="fas fa-seedling fa-2x" style="color:#27ae60;"></i>
    </div>

    <h2>Reset Your Password</h2>
    <p>Enter your new password below to securely update your account.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="form-floating text-start">
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Email"
                   value="{{ old('email', $request->email) }}"
                   autofocus>
            <label for="email">Email Address</label>
        </div>
        @error('email')
            <div class="input-error">{{ $message }}</div>
        @enderror

        <!-- Password -->
        <div class="form-floating text-start mt-3 password-wrapper">
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="New Password"
                   autocomplete="new-password">
            <label for="password">New Password</label>
            <i class="fas fa-eye-slash password-toggle" onclick="togglePassword('password', this)"></i>
        </div>
        @error('password')
            <div class="input-error">{{ $message }}</div>
        @enderror

        <!-- Confirm Password -->
        <div class="form-floating mt-3 text-start password-wrapper">
            <input type="password"
                   name="password_confirmation"
                   id="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="Confirm Password"
                   autocomplete="new-password">
            <label for="password_confirmation">Confirm Password</label>
            <i class="fas fa-eye-slash password-toggle" onclick="togglePassword('password_confirmation', this)"></i>
            @error('password_confirmation')
            <div class="input-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-eco text-white w-100 mt-4">
            Reset Password
        </button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
        <i class="fas fa-arrow-left me-1"></i> Back to Login
    </a>

</div>

<script>
    function togglePassword(id, icon) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye-slash');
        icon.classList.toggle('fa-eye');
    }
</script>

</body>
</html>
