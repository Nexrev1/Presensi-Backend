<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        .hero-title {
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-5">
        <h1 class="display-4">PRESENSI KARYAWAN</h1>
    </header>
    <section class="hero-section text-center">
        <div class="container">
            <h2 class="hero-title">Selamat Datang di Presensi Karyawan</h2>
            <p class="lead">Silahkan Login</p>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-lg">Login</a>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\presensi-backend\resources\views/welcome.blade.php ENDPATH**/ ?>