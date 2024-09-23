

<?php $__env->startSection('title', 'Manage Izin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Manage Izin</h1>
    
    <!-- Tampilkan pesan sukses jika ada -->
    <?php if(session('success')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="alert alert-info" role="alert">
        Kelola izin karyawan di sini.
    </div>
    
    <!-- Tampilkan tabel izin -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis Izin</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Lama Waktu Izin</th> <!-- Kolom Lama Waktu Izin -->
                    <th>Status</th>
                    <th>Aksi</th> <!-- Kolom Aksi -->
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $izin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i->id); ?></td>
                    <td><?php echo e($i->user->name); ?></td>
                    <td><?php echo e($i->jenis_izin); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($i->tanggal_mulai)->format('d M Y')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($i->tanggal_selesai)->format('d M Y')); ?></td>
                    <td>
                        <?php echo e(\Carbon\Carbon::parse($i->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($i->tanggal_selesai))); ?> hari
                    </td>
                    <td>
                        <?php switch($i->status):
                            case ('approved'): ?>
                                Disetujui
                                <?php break; ?>
                            <?php case ('rejected'): ?>
                                Ditolak
                                <?php break; ?>
                            <?php default: ?>
                                Menunggu
                        <?php endswitch; ?>
                    </td>
                    <td>
                        <?php if($i->status === 'pending'): ?>
                            <form action="<?php echo e(route('admin.approve-izin', $i->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui izin ini?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form action="<?php echo e(route('admin.reject-izin', $i->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menolak izin ini?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                        <?php endif; ?>
                        <?php if($i->dokumen): ?>
                            <a href="<?php echo e(asset('storage/' . $i->dokumen)); ?>" class="btn btn-info btn-sm" target="_blank">Lihat Dokumen</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($izin->isEmpty()): ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada izin yang diajukan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\presensi-backend\resources\views/admin/manage-izin.blade.php ENDPATH**/ ?>