

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Rekap Absen</h1>

    <?php if($presensiRecords->isEmpty()): ?>
        <p>No records found.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lokasi</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $presensiRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($presensi->id); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($presensi->tanggal)->format('d M Y')); ?></td>
                        <td><?php echo e($presensi->masuk ? \Carbon\Carbon::parse($presensi->masuk)->format('H:i:s') : 'N/A'); ?></td>
                        <td><?php echo e($presensi->pulang ? \Carbon\Carbon::parse($presensi->pulang)->format('H:i:s') : 'N/A'); ?></td>
                        <td><?php echo e($presensi->location ?? 'N/A'); ?></td>
                        <td><?php echo e($presensi->user_agent ?? 'N/A'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Pagination Links -->
        <?php echo e($presensiRecords->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\presensi-backend\resources\views/users/rekap-absen.blade.php ENDPATH**/ ?>