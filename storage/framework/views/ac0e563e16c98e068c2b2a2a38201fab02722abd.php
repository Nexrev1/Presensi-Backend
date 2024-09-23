

<?php $__env->startSection('title', 'Manage Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Manage Karyawan</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Search Form -->
    <form action="<?php echo e(route('admin.manage-users')); ?>" method="GET" class="mb-4">
        <div class="form-group">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo e(request('search')); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Tabel data pengguna -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>NIK</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($user->id); ?></td>
                <td><?php echo e($user->name); ?></td>
                <td><?php echo e($user->email); ?></td>
                <td><?php echo e($user->role); ?></td>
                <td><?php echo e($user->nik); ?></td>
                <td class="actions">
                    <a href="<?php echo e(route('admin.edit-user', $user->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?php echo e(route('admin.delete-user', $user->id)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="text-center">Tidak ada pengguna.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Pagination Links -->
    <?php echo e($users->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions a, .actions button {
            padding: 5px 10px;
            text-decoration: none;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .actions a {
            background-color: #ffc107;
        }
        .actions button {
            background-color: #dc3545;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\presensi-backend\resources\views/admin/manage-users.blade.php ENDPATH**/ ?>