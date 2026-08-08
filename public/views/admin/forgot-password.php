<?php
/** Requires $error, $old (array with email) in scope. */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password | Al NAHDA Agency</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= versionedAsset('assets/css/tailwind.css') ?>">
  <link rel="stylesheet" href="<?= versionedAsset('assets/css/app.css') ?>">
  <link rel="icon" href="<?= asset('assets/site/img/logo-no-bg.png') ?>" type="image/png">
</head>
<body class="bg-[#0f2038] text-white antialiased min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-sm min-w-0">
    <div class="text-center mb-8">
      <img src="<?= asset('assets/site/img/logo-no-bg.png') ?>" alt="Al NAHDA Agency" class="inline-flex items-center justify-center w-14 h-14 object-contain mb-3" />
      <h1 class="font-serif-heading text-2xl font-bold tracking-widest uppercase">Forgot Password</h1>
      <p class="text-xs text-amber-200/60 mt-1">We'll email you a 6-digit code to reset it</p>
    </div>

    <form method="post" action="<?= url('/admin/forgot-password') ?>" class="bg-[#132c5c] border border-amber-500/20 rounded-xl p-6 space-y-4 shadow-2xl">
      <?= csrfField() ?>
      <?php if ($error): ?>
        <div class="bg-rose-950 border border-rose-600/40 text-rose-300 text-xs font-semibold rounded-lg p-3"><?= e($error) ?></div>
      <?php endif; ?>
      <div>
        <label class="text-[11px] font-bold text-amber-200/80 uppercase tracking-wider">Admin Email</label>
        <input type="email" name="email" required autofocus value="<?= e($old['email'] ?? '') ?>" class="w-full mt-1 bg-[#0f2038] border border-amber-500/30 text-white text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-amber-400" />
      </div>
      <button type="submit" class="w-full bg-amber-400 hover:bg-amber-300 text-black text-xs font-bold py-3 rounded-lg uppercase tracking-widest transition-colors cursor-pointer">Send Reset Code</button>
    </form>

    <p class="text-center text-[11px] text-amber-200/40 mt-6">
      <a href="<?= url('/admin/login') ?>" class="hover:text-amber-300">&larr; Back to sign in</a>
    </p>
  </div>
</body>
</html>
