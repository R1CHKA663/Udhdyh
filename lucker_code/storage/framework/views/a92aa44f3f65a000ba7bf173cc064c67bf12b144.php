<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="shortcut icon" href="/assets/image/logo.png">
        <title><?php echo e($settings->title); ?></title>
        <meta name="description" content="<?php echo e($settings->description); ?>">
        <meta name="keywords" content="<?php echo e($settings->keywords); ?>">
        <link rel="preconnect" href="https://fonts.gstatic.com">
	    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/fingerprintjs2/1/fingerprint2.min.js"></script>
    </head>
    <body>
        <noscript>You need to enable JavaScript to run this app.</noscript>
        <div id="root"></div>
        <div class="errors" style="display: none"><?php echo e(session('error')); ?></div>
    </body>
    <?php
        if(isset($_GET['invite'])) {
            session_start();
            $_SESSION['ref'] = $_GET['invite'];
        }
    ?>
    <script>
		const gl = document.createElement("canvas").getContext("webgl");
		const ext = gl.getExtension("WEBGL_debug_renderer_info");

		if (ext) {
			$.post('/user/videocard', {video: gl.getParameter(ext.UNMASKED_RENDERER_WEBGL)})
        }

		new Fingerprint2().get(function(result, components){
			var print = result;
			$.post('/user/fingerprint', {finger: print})
		});
	</script>
    <script src="/js/app.js?v=<?php echo e(time()); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('assets/app.css')); ?>?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" class="theme" href="#">
    <link rel="stylesheet" href="<?php echo e(asset('assets/wheel.css')); ?>?v=<?php echo e(time()); ?>">
    <script src="/js/theme.js?v=<?php echo e(time()); ?>"></script>
</html><?php /**PATH /var/www/stimule/resources/views/app.blade.php ENDPATH**/ ?>