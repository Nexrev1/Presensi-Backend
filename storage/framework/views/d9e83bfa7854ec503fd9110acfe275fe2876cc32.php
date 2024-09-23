

<?php $__env->startSection('title', 'Manage Presensi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Manage Presensi</h1>

    <!-- Tabel data presensi -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Waktu Kerja</th>
                    <th>Lokasi</th>
                    <th>User Agent</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $presensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($presensi->id); ?></td>
                        <td><?php echo e($presensi->user->name); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($presensi->tanggal)->format('d M Y')); ?></td>
                        <td><?php echo e($presensi->masuk ? \Carbon\Carbon::parse($presensi->masuk)->format('H:i:s') : 'N/A'); ?></td>
                        <td><?php echo e($presensi->pulang ? \Carbon\Carbon::parse($presensi->pulang)->format('H:i:s') : 'N/A'); ?></td>
                        <td>
                            <?php if($presensi->masuk && $presensi->pulang): ?>
                                <?php
                                    $start = \Carbon\Carbon::parse($presensi->masuk);
                                    $end = \Carbon\Carbon::parse($presensi->pulang);
                                    $hoursWorked = $end->diffInHours($start);
                                    $minutesWorked = $end->diffInMinutes($start) % 60;
                                ?>
                                <?php echo e($hoursWorked); ?> jam <?php echo e($minutesWorked); ?> menit
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($presensi->location ?? 'N/A'); ?></td>
                        <td><?php echo e($presensi->user_agent ?? 'N/A'); ?></td>
                        <td class="actions">
                            <a href="<?php echo e(route('admin.edit-presensi', $presensi->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="<?php echo e(route('admin.delete-presensi', $presensi->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($presensis->links('pagination::bootstrap-4')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        .container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        .thead-dark th {
            background-color: #343a40;
            color: white;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .actions {
            display: flex;
            justify-content: space-between; /* Posisi tombol di kanan dan kiri */
            gap: 10px; /* Jarak antar tombol */
        }
        .actions a, .actions button {
            text-decoration: none;
            font-size: 14px; /* Ukuran font tombol */
            padding: 8px 12px; /* Padding tombol */
            border-radius: 4px; /* Sudut tombol */
            border: none;
        }
        .actions .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .actions .btn-warning:hover {
            background-color: #e0a800;
        }
        .actions .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .actions .btn-danger:hover {
            background-color: #c82333;
        }
        /* Styling Pagination */
        .pagination {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            justify-content: center;
            font-size: 14px;
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\presensi-backend\resources\views/admin/manage-presensi.blade.php ENDPATH**/ ?>