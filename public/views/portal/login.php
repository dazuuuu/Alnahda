<?php
/** Requires $error, $method, $old (array with email/phone) in scope. */
require __DIR__ . '/layout-header.php';
?>

<div class="max-w-md mx-auto">
  <div class="text-center mb-8">
    <span class="text-xs font-bold text-[#1c3d7a] uppercase tracking-widest block mb-1">Applicant Portal</span>
    <h1 class="font-serif-heading text-3xl font-bold text-[#0f2852]">Track Your Application</h1>
    <p class="text-sm text-neutral-500 mt-2">
      Sign in with the email or phone number you used when you applied — no password needed.
    </p>
  </div>

  <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-6">
    <div class="flex mb-5 bg-neutral-100 rounded-lg p-1 text-xs font-bold uppercase tracking-wider">
      <button type="button" data-tab="email" class="account-tab flex-1 py-2 rounded-md transition-colors">Email</button>
      <button type="button" data-tab="phone" class="account-tab flex-1 py-2 rounded-md transition-colors">Phone Number</button>
    </div>

    <?php if ($error): ?>
      <div class="bg-rose-50 border border-rose-300 text-rose-800 text-sm rounded-lg p-3 mb-4"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= url('/portal/login') ?>" id="email-form" class="space-y-4">
      <?= csrfField() ?>
      <input type="hidden" name="method" value="email" />
      <div>
        <label class="text-[11px] font-bold text-neutral-600 uppercase">Email Address</label>
        <input type="email" name="email" required value="<?= e($old['email'] ?? '') ?>" placeholder="you@example.com" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]" />
      </div>
      <button type="submit" class="w-full bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold py-3 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30">Send Login Code</button>
      <p class="text-[11px] text-neutral-400 text-center">We'll email you a 6-digit code that expires in 10 minutes.</p>
    </form>

    <form method="post" action="<?= url('/portal/login') ?>" id="phone-form" class="space-y-4 hidden">
      <?= csrfField() ?>
      <input type="hidden" name="method" value="phone" />
      <div>
        <label class="text-[11px] font-bold text-neutral-600 uppercase">Phone Number</label>
        <input type="tel" name="phone" required value="<?= e($old['phone'] ?? '') ?>" placeholder="0712345678" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm font-mono focus:outline-none focus:border-[#1c3d7a]" />
      </div>
      <button type="submit" class="w-full bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold py-3 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30">Continue</button>
      <p class="text-[11px] text-neutral-400 text-center">Use the exact phone number you gave when you applied.</p>
    </form>
  </div>

  <div class="mt-6 bg-[#f5f7fb] border border-[#1c3d7a]/15 rounded-xl p-5 text-xs text-neutral-600 space-y-2">
    <p class="font-bold text-neutral-800 uppercase tracking-wider text-[11px]">First time here?</p>
    <p>You don't need to sign up. The moment you submit a job application, we automatically create a tracking account using the email or phone number you gave us.</p>
    <p>Come back to this page any time and sign in with that same email or phone number to see your application status and messages from our recruiters.</p>
    <p class="pt-2"><a href="<?= url('/apply') ?>" class="font-bold text-[#1c3d7a] hover:underline">Haven't applied yet? Submit an application &rarr;</a></p>
  </div>
</div>

<script>
(function () {
  var tabs = document.querySelectorAll('.account-tab');
  var forms = { email: document.getElementById('email-form'), phone: document.getElementById('phone-form') };
  var initial = <?= json_encode($method === 'phone' ? 'phone' : 'email') ?>;

  function setActive(method) {
    tabs.forEach(function (t) {
      var active = t.getAttribute('data-tab') === method;
      t.classList.toggle('bg-white', active);
      t.classList.toggle('shadow-sm', active);
      t.classList.toggle('text-[#0f2852]', active);
      t.classList.toggle('text-neutral-500', !active);
    });
    forms.email.classList.toggle('hidden', method !== 'email');
    forms.phone.classList.toggle('hidden', method !== 'phone');
  }

  tabs.forEach(function (t) {
    t.addEventListener('click', function () { setActive(t.getAttribute('data-tab')); });
  });
  setActive(initial);
})();
</script>

<?php require __DIR__ . '/layout-footer.php'; ?>
