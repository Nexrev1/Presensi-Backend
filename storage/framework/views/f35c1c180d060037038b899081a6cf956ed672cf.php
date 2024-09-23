

<?php $__env->startSection('title', 'Tambah Karyawan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Tambah Karyawan</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Perhatikan:</strong>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            Formulir Tambah Karyawan
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.add-user')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                    <small class="form-text text-muted">Masukkan nama lengkap karyawan.</small>
                </div>

                <div class="form-group">
                    <label for="nik">Nomor Pegawai (NIK)</label>
                    <input type="text" class="form-control" id="nik" name="nik" value="<?php echo e(old('nik')); ?>" required>
                    <small class="form-text text-muted">Masukkan nomor induk kepegawaian karyawan.</small>
                </div>
                

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                    <small class="form-text text-muted">Masukkan email yang valid untuk karyawan.</small>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <small class="form-text text-muted">Password harus minimal 8 karakter.</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin" <?php echo e(old('role') === 'admin' ? 'selected' : ''); ?>>Admin</option>
                        <option value="user" <?php echo e(old('role') === 'user' ? 'selected' : ''); ?>>User</option>
                    </select>
                    <small class="form-text text-muted">Pilih role untuk karyawan ini.</small>
                </div>

                <button type="submit" class="btn btn-primary">Tambah Karyawan</button>
                <a href="<?php echo e(route('admin.manage-users')); ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\presensi-backend\resources\views/admin/add-user.blade.php ENDPATH**/ ?>