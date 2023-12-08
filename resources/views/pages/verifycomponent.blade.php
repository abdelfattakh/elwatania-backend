<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Email</title>

    <style>
        .card img {
            width: 80%;
        }

        .card .card-body .card-title {
            font-size: 22px;
            color: #AE0028;
        }

        .card .card-body p {
            font-size: 16px;
        }

        .card .card-body a {
            font-size: 18px;
            background: linear-gradient(90deg, #AE0028, #FAA451);
            border: none;
            border-radius: 6px;
            padding: 6px 14px;
            margin: 10px auto;
        }
    </style>
</head>
<body>
<div class="card" style="width: 28rem; margin:auto; border-color: red;">
    <div class="card-body">
        <h5 class="card-title" style="text-align: center">Al-WATANIA</h5>
        <img src="{{ asset('logoEmail.svg') }}" alt="">
        <h5 class="card-title">Dear {{ $user->first_name }}</h5>
        <a href="{{ route('verify',$user->email_verification_token) }}" style="color: white; text-decoration: none">
            Click here to verify
        </a>

        <p class="card-text">
            Your account has been created, please activate your account by clicking this link
        </p>

        <p class="card-text">Thanks</p>

    </div>
</div>
</body>
</html>
