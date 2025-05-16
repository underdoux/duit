<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ url('/duit') }}">

    <title>Money Pro Management</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #2563EB !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        button:hover {
            background-color: #1d4ed8 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        button:active {
            transform: translateY(0);
        }

        a:hover {
            color: #1d4ed8 !important;
        }

        @media (max-width: 640px) {
            .login-container {
                padding: 1rem !important;
            }

            .login-box {
                padding: 1.5rem !important;
            }
        }
    </style>
</head>
<body>
    @yield('content')

    <script>
        // Add base URL to all relative URLs
        document.addEventListener('DOMContentLoaded', function() {
            const base = '{{ url("/duit") }}';
            document.querySelectorAll('a[href^="/"]').forEach(link => {
                if (!link.getAttribute('href').startsWith(base)) {
                    link.href = base + link.getAttribute('href');
                }
            });
            document.querySelectorAll('form').forEach(form => {
                if (form.action && !form.action.startsWith(base)) {
                    form.action = base + form.getAttribute('action');
                }
            });
        });
    </script>
</body>
</html>
