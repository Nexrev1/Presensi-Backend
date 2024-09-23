

<?php $__env->startSection('title', 'Laporan Absensi Bulanan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1 class="mb-4">Laporan Absensi Bulanan</h1>

    <!-- Header Bulan dan Tahun -->
    <div class="mb-4">
        <h4>Bulan: <?php echo e(\Carbon\Carbon::create($year, $month)->format('F Y')); ?></h4>
        <!-- Navigasi Bulan -->
        <div class="btn-group" role="group" aria-label="Navigasi Bulan">
            <a href="<?php echo e(route('admin.monthly-report', ['month' => $previousMonth, 'year' => $year])); ?>" class="btn btn-nav">Bulan Sebelumnya</a>
            <a href="<?php echo e(route('admin.monthly-report', ['month' => $nextMonth, 'year' => $year])); ?>" class="btn btn-nav">Bulan Berikutnya</a>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Karyawan</th>
                    <th>Nomor Karyawan</th>
                    <?php for($i = 1; $i <= $dates; $i++): ?>
                        <?php
                            $date = \Carbon\Carbon::create($year, $month)->day($i);
                        ?>
                        <th class="date-col">
                            <div class="day"><?php echo e($date->locale('id')->isoFormat('dddd')); ?></div>
                            <div class="date"><?php echo e($date->format('d M')); ?></div>
                        </th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->nik); ?></td> <!-- Mengganti employee_number dengan nik -->
                        <?php for($i = 1; $i <= $dates; $i++): ?>
                            <?php
                                $date = \Carbon\Carbon::create($year, $month)->day($i)->format('Y-m-d');
                                $status = isset($presensi[$user->id][$date]) ? $presensi[$user->id][$date] : 'A'; // 'A' untuk Absen
                            ?>
                            <td class="status-col"><?php echo e($status); ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Catatan Keterangan -->
    <div class="mt-4">
        <h5>Keterangan:</h5>
        <ul>
            <li><strong>H</strong> - Hadir</li>
            <li><strong>I</strong> - Izin</li>
            <li><strong>A</strong> - Absen</li>
            <!-- Tambahkan keterangan lain jika perlu -->
        </ul>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        <ul class="pagination">
            <!-- Contoh elemen pagination, sesuaikan dengan kebutuhan -->
            <li class="page-item disabled"><span class="page-link">«</span></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">»</a></li>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        .container-fluid {
            max-width: 100%;
        }
        .table {
            font-size: 0.875rem; /* Ukuran font tabel */
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            padding: 8px; /* Tambahkan padding untuk meningkatkan keterbacaan */
        }
        .table thead th {
            background-color: #2d2d2d;
            color: #fff;
        }
        .date-col div {
            padding: 4px;
        }
        .date-col .day {
            font-size: 0.75rem; /* Ukuran font untuk hari */
            font-weight: bold;
        }
        .date-col .date {
            font-size: 0.875rem; /* Ukuran font untuk tanggal */
        }
        .status-col {
            font-size: 0.875rem;
            padding: 8px;
        }
        .btn-nav {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 0.875rem;
            margin-right: 5px;
            text-align: center;
            display: inline-block;
        }
        .btn-nav:hover {
            background-color: #0056b3;
        }
        .pagination {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            justify-content: center;
            font-size: 0.875rem;
        }
        .pagination li {
            margin: 0 2px;
        }
        .pagination li a, .pagination li span {
            display: block;
            padding: 8px 12px;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
        }
        .pagination li a:hover {
            background-color: #0056b3;
        }
        .pagination li.active a {
            background-color: #0056b3;
            cursor: default;
        }
        .pagination li.disabled a {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\presensi-backend\resources\views/admin/monthly-report.blade.php ENDPATH**/ ?>