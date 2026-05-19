<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Vendor Login - Homewala</title>

    <!-- Professional Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

   <style>
    :root{
        --primary:#1EA4EE;
        --primary-dark:#0d8cd1;
        --dark:#0f172a;
        --text:#334155;
        --border:#dbe4ee;
        --bg:#f1f5f9;
        --glass:rgba(255,255,255,.72);
    }

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:'Inter',sans-serif;
    }

    body{
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        background:
            radial-gradient(circle at top left,#c7e9ff 0%,transparent 35%),
            radial-gradient(circle at bottom right,#dbeafe 0%,transparent 35%),
            linear-gradient(135deg,#f8fafc,#eef6ff);
        overflow:hidden;
        position:relative;
        padding:20px;
    }

    /* ===== Animated Background ===== */
    .bg-blur{
        position:absolute;
        width:420px;
        height:420px;
        border-radius:50%;
        filter:blur(100px);
        z-index:0;
        animation:floatBlob 10s ease-in-out infinite;
    }

    .bg-blur.one{
        top:-120px;
        left:-120px;
        background:rgba(30,164,238,.18);
    }

    .bg-blur.two{
        bottom:-120px;
        right:-120px;
        background:rgba(59,130,246,.18);
        animation-delay:3s;
    }

    .bg-blur.three{
        top:45%;
        left:50%;
        transform:translate(-50%,-50%);
        background:rgba(147,197,253,.15);
        animation-delay:5s;
    }

    @keyframes floatBlob{
        0%,100%{
            transform:translateY(0px) scale(1);
        }
        50%{
            transform:translateY(30px) scale(1.08);
        }
    }

    /* ===== Wrapper ===== */
    .login-wrapper{
        width:100%;
        max-width:540px;
        position:relative;
        z-index:2;
        animation:wrapperFade 1s ease;
    }

    @keyframes wrapperFade{
        from{
            opacity:0;
            transform:translateY(25px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    /* ===== Logo ===== */
    .logo-area{
        text-align:center;
        margin-bottom:30px;
        animation:logoDrop 1s ease;
    }

    .logo-area img{
        height:56px;
        filter:drop-shadow(0 8px 18px rgba(30,164,238,.18));
        transition:.35s ease;
    }

    .logo-area img:hover{
        transform:scale(1.05) rotate(-2deg);
    }

    @keyframes logoDrop{
        from{
            opacity:0;
            transform:translateY(-20px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    /* ===== Login Card ===== */
    .login-card{
        position:relative;
        overflow:hidden;
        background:var(--glass);
        backdrop-filter:blur(22px);
        border:1px solid rgba(255,255,255,.65);
        border-radius:34px;
        padding:44px;
        box-shadow:
            0 25px 60px rgba(15,23,42,.08),
            inset 0 1px 0 rgba(255,255,255,.6);
        animation:cardReveal 1s cubic-bezier(.22,1,.36,1);
    }

    .login-card::before{
        content:'';
        position:absolute;
        inset:0;
        background:linear-gradient(
            135deg,
            rgba(255,255,255,.18),
            transparent 40%,
            rgba(30,164,238,.08)
        );
        pointer-events:none;
    }

    .login-card::after{
        content:'';
        position:absolute;
        top:-120px;
        right:-120px;
        width:240px;
        height:240px;
        border-radius:50%;
        background:rgba(30,164,238,.08);
        filter:blur(30px);
    }

    @keyframes cardReveal{
        from{
            opacity:0;
            transform:translateY(35px) scale(.96);
        }
        to{
            opacity:1;
            transform:translateY(0) scale(1);
        }
    }

    /* ===== Header ===== */
    .header{
        text-align:center;
        margin-bottom:38px;
        animation:fadeSlide .9s ease;
    }

    .subtitle{
        color:#64748b;
        font-size:15px;
        margin-bottom:10px;
        font-weight:600;
        letter-spacing:.5px;
    }

    .title{
        font-size:44px;
        font-weight:800;
        color:var(--dark);
        letter-spacing:-1.5px;
        line-height:1.1;
    }

    .title span{
        background:linear-gradient(135deg,#1EA4EE,#2563eb);
        -webkit-background-clip:text;
        -webkit-text-fill-color:transparent;
    }

    @keyframes fadeSlide{
        from{
            opacity:0;
            transform:translateY(18px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    /* ===== Form ===== */
    .form-group{
        margin-bottom:24px;
        animation:fadeSlide .8s ease;
    }

    .label{
        display:block;
        margin-bottom:10px;
        font-size:14px;
        font-weight:700;
        color:var(--dark);
    }

    .input-wrap{
        position:relative;
    }

    .input-wrap::before{
        content:'';
        position:absolute;
        inset:0;
        border-radius:18px;
        padding:1px;
        background:linear-gradient(135deg,transparent,rgba(30,164,238,.2));
        -webkit-mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite:xor;
        pointer-events:none;
    }

    .input-wrap input{
        width:100%;
        height:58px;
        border-radius:18px;
        border:1px solid var(--border);
        background:rgba(255,255,255,.92);
        padding:0 18px;
        font-size:15px;
        color:var(--dark);
        outline:none;
        transition:.35s cubic-bezier(.22,1,.36,1);
    }

    .input-wrap input:focus{
        border-color:var(--primary);
        box-shadow:
            0 0 0 4px rgba(30,164,238,.12),
            0 10px 24px rgba(30,164,238,.08);
        transform:translateY(-2px);
        background:#fff;
    }

    .input-wrap input::placeholder{
        color:#94a3b8;
    }

    /* ===== Options ===== */
    .options{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin:10px 0 30px;
        animation:fadeSlide 1s ease;
    }

    .remember{
        display:flex;
        align-items:center;
        gap:10px;
        font-size:14px;
        color:#475569;
        cursor:pointer;
        font-weight:500;
    }

    .remember input{
        width:16px;
        height:16px;
        accent-color:var(--primary);
    }

    .forgot{
        color:var(--primary);
        text-decoration:none;
        font-size:14px;
        font-weight:700;
        position:relative;
        transition:.25s ease;
    }

    .forgot::after{
        content:'';
        position:absolute;
        left:0;
        bottom:-2px;
        width:0;
        height:2px;
        background:var(--primary);
        transition:.3s ease;
    }

    .forgot:hover::after{
        width:100%;
    }

    .forgot:hover{
        color:var(--primary-dark);
    }

    /* ===== Button ===== */
    .btn{
        position:relative;
        overflow:hidden;
        width:100%;
        height:58px;
        border:none;
        border-radius:20px;
        background:linear-gradient(135deg,#1EA4EE,#0f8ed3,#2563eb);
        background-size:200% 200%;
        animation:btnGradient 5s ease infinite;
        color:#fff;
        font-size:16px;
        font-weight:800;
        letter-spacing:.2px;
        cursor:pointer;
        transition:.35s cubic-bezier(.22,1,.36,1);
        box-shadow:
            0 18px 35px rgba(30,164,238,.28);
    }

    .btn:hover{
        transform:translateY(-3px) scale(1.01);
        box-shadow:
            0 22px 40px rgba(30,164,238,.38);
    }

    .btn:active{
        transform:scale(.98);
    }

    .btn::before{
        content:'';
        position:absolute;
        top:0;
        left:-120%;
        width:120%;
        height:100%;
        background:linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.28),
            transparent
        );
        transition:.8s;
    }

    .btn:hover::before{
        left:120%;
    }

    @keyframes btnGradient{
        0%{
            background-position:0% 50%;
        }
        50%{
            background-position:100% 50%;
        }
        100%{
            background-position:0% 50%;
        }
    }

    /* ===== Error ===== */
    .error-msg{
        background:rgba(254,226,226,.9);
        color:#dc2626;
        padding:14px;
        border-radius:16px;
        margin-bottom:24px;
        font-size:14px;
        text-align:center;
        border:1px solid #fecaca;
        animation:shakeIn .5s ease;
    }

    @keyframes shakeIn{
        0%{
            opacity:0;
            transform:translateX(-10px);
        }
        50%{
            transform:translateX(6px);
        }
        100%{
            opacity:1;
            transform:translateX(0);
        }
    }

    /* ===== Footer ===== */
    .footer-text{
        margin-top:28px;
        text-align:center;
        font-size:13px;
        color:#64748b;
        font-weight:500;
        animation:fadeSlide 1.1s ease;
    }

    /* ===== Responsive ===== */
    @media(max-width:600px){

        body{
            padding:16px;
        }

        .login-card{
            padding:30px 24px;
            border-radius:26px;
        }

        .title{
            font-size:34px;
        }

        .options{
            flex-direction:column;
            gap:14px;
            align-items:flex-start;
        }
    }
</style>
</head>

<body>

    <!-- Animated Background -->
    <div class="bg-blur one"></div>
    <div class="bg-blur two"></div>

    <div class="login-wrapper">

        <!-- Logo -->
        <div class="logo-area">
            <img src="{{ asset('images/homewala-logo.png') }}"
                 alt="Homewala Logo">
        </div>

        <!-- Login Card -->
        <div class="login-card">

            <div class="header">
                <p class="subtitle">Welcome back to</p>
                <h1 class="title">Vendor <span>Portal</span></h1>
            </div>

            @if ($errors->any())
                <div class="error-msg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('vendor.login.submit') }}" method="POST">

                @csrf

                <!-- Username -->
                <div class="form-group">
                    <label class="label">
                        Username or Email Address
                    </label>

                    <div class="input-wrap">
                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Enter your username or email"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="label">
                        Password
                    </label>

                    <div class="input-wrap">
                        <input
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                </div>

                <!-- Options -->
                <div class="options">

                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>

                    <a href="#" class="forgot">
                        Forgot Password?
                    </a>

                </div>

                <!-- Button -->
                <button type="submit" class="btn">
                    Login to Vendor Dashboard
                </button>

            </form>

            <div class="footer-text">
                © {{ date('Y') }} Homewala. All rights reserved.
            </div>

        </div>

    </div>

</body>
</html>