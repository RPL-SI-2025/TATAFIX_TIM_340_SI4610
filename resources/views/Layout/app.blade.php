<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TATAFIX</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .profile-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid #ddd;
        }
        
        .profile-info {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .profile-info h2 {
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .profile-info p {
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .edit-profile-btn {
            display: inline-block;
            padding: 6px 20px;
            font-size: 14px;
            border-radius: 20px;
            text-decoration: none;
        }
        
        .account-info-section {
            margin-top: 30px;
        }
        
        .account-info-section h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .info-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 500;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    @include('layout.header')

    <main>
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>