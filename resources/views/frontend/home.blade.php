<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coming Soon | Trackag</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body{
            height:100vh;
            background:linear-gradient(135deg,#f5f7fa,#e4ecf7);
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .container{
            text-align:center;
        }

        .logo{
            width:180px;
            margin-bottom:30px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float{
            0%{ transform:translateY(0px);}
            50%{ transform:translateY(-10px);}
            100%{ transform:translateY(0px);}
        }

        h1{
            font-size:42px;
            color:#1f2937;
            margin-bottom:15px;
        }

        p{
            font-size:18px;
            color:#6b7280;
            margin-bottom:25px;
        }

        .countdown{
            font-size:20px;
            color:#224abe;
            font-weight:600;
        }

        .footer{
            position:absolute;
            bottom:20px;
            width:100%;
            text-align:center;
            font-size:14px;
            color:#888;
        }

        @media(max-width:768px){
            h1{ font-size:32px; }
            .logo{ width:140px; }
        }
    </style>
</head>
<body>

<div class="container">

    <img src="{{ asset('img/TRACKAGLOGO.jpeg') }}" alt="Trackag Logo" class="logo">

    <h1>Coming Soon 🚀</h1>

    <p>We are working hard to launch our new website.</p>

    <div class="countdown">
        Stay Tuned!
    </div>

</div>

<div class="footer">
    © {{ date('Y') }} Trackag. All Rights Reserved.
</div>

</body>
</html>