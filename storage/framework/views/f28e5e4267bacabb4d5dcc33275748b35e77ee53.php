

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Admin Dashboard</h1>
    <div class="alert alert-info" role="alert">
        Selamat datang di dashboard admin.
    </div>
    <h2 class="mb-4">Rekap Hadir Hari Ini</h2>
    
    <!-- Tampilkan rekap kehadiran -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($attendance->id); ?></td>
                <td><?php echo e($attendance->user->name); ?></td>
                <td><?php echo e($attendance->masuk); ?></td>
                <td><?php echo e($attendance->pulang); ?></td>
                <td>
                    <?php if($attendance->pulang): ?>
                        Pulang
                    <?php elseif($attendance->masuk): ?>
                        Hadir
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="text-center">Tidak ada data hadir hari ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\presensi-backend\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>