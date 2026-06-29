
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
<head>
    <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-white dark:bg-[#161618] text-gray-900 dark:text-gray-100 antialiased min-h-screen flex">

    <!-- Sidebar -->
    <?php if (isset($component)) { $__componentOriginala12ee38770dfc9ba212665cdb25e4cfd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala12ee38770dfc9ba212665cdb25e4cfd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala12ee38770dfc9ba212665cdb25e4cfd)): ?>
<?php $attributes = $__attributesOriginala12ee38770dfc9ba212665cdb25e4cfd; ?>
<?php unset($__attributesOriginala12ee38770dfc9ba212665cdb25e4cfd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala12ee38770dfc9ba212665cdb25e4cfd)): ?>
<?php $component = $__componentOriginala12ee38770dfc9ba212665cdb25e4cfd; ?>
<?php unset($__componentOriginala12ee38770dfc9ba212665cdb25e4cfd); ?>
<?php endif; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 bg-gray-50 dark:bg-[#161618] overflow-auto">
            <?php echo e($slot); ?>

        </main>
    </div>

    <?php app('livewire')->forceAssetInjection(); ?>
<?php echo app('flux')->scripts(); ?>

</body>
</html>
<?php /**PATH /home/gabriel/Escritorio/Metodologia/sistema-de-informacion-para-venta-de-computadoras-/resources/views/components/layouts/app.blade.php ENDPATH**/ ?>