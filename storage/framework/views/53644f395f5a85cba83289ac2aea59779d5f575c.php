

<?php $__env->startSection('content'); ?>
    <div class="container">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.update-presensi', $presensi->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?> <!-- Menggunakan PUT karena rute update dengan metode PUT -->
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo e(old('tanggal', $presensi->tanggal)); ?>" required>
            </div>
            <div class="form-group">
                <label for="masuk">Jam Masuk</label>
                <input type="time" name="masuk" id="masuk" class="form-control" value="<?php echo e(old('masuk', $presensi->masuk)); ?>" required>
            </div>
            <div class="form-group">
                <label for="pulang">Jam Pulang</label>
                <input type="time" name="pulang" id="pulang" class="form-control" value="<?php echo e(old('pulang', $presensi->pulang)); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Presensi</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\presensi-backend\resources\views/admin/edit-presensi.blade.php ENDPATH**/ ?>