<?php
/*
Template Name: فرم تماس سفارشی
*/
defined('ABSPATH') || exit;
get_header();
?>
<?php
/*
Template Name: فرم تماس سفارشی
*/
defined('ABSPATH') || exit;
get_header();

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form_submitted'])) {
  $first_name = sanitize_text_field($_POST['first_name'] ?? '');
  $last_name = sanitize_text_field($_POST['last_name'] ?? '');
  $email = sanitize_email($_POST['email'] ?? '');
  $message = sanitize_textarea_field($_POST['message'] ?? '');

  if (empty($first_name) || empty($last_name) || empty($message)) {
    $error_message = 'اطلاعات کامل نیست. لطفاً همه فیلدهای ضروری را پر کنید.';
  } else {
    $success_message = 'پیام شما با موفقیت ارسال شد ✅';
  }
}

?>
<style>
  @keyframes slideDown {
    0% {
      transform: translateY(-50px);
      opacity: 0;
    }

    100% {
      transform: translateY(0);
      opacity: 1;
    }
  }

  .animate-slideDown {
    animation: slideDown 0.8s ease-out forwards;
  }

  .error-border {
    border-color: #f87171 !important;
  }
</style>

<div class="animate-slideDown max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8 items-center justify-center    " dir="rtl">
  <!-- فرم تماس -->
  <div class="bg-white shadow-lg p-6 rounded-xl ">
    <h2 class="text-xl font-bold text-gray-800 mb-6 text-right">فرم ارتباط با ما</h2>

    <div id="formAlert" class="hidden bg-red-100 text-red-800 p-4 rounded mb-4 text-right font-medium">
      اطلاعات کامل نیست. لطفاً همه فیلدهای ضروری را پر کنید.
    </div>

    <div id="formSuccess" class="hidden bg-green-100 text-green-800 p-4 rounded mb-4 text-right font-medium">
      پیام شما با موفقیت ارسال شد ✅
    </div>
    <?php if (!empty($error_message)): ?>
      <div class="bg-red-100 text-red-800 p-4 rounded mb-4 text-right font-medium">
        <?= esc_html($error_message); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
      <div class="bg-green-100 text-green-800 p-4 rounded mb-4 text-right font-medium">
        <?= esc_html($success_message); ?>
      </div>
    <?php endif; ?>
    <form method="post" id="contactForm" novalidate>
      <input type="hidden" name="contact_form_submitted" value="1">

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <input type="text" name="first_name" placeholder="نام"
          class="w-full p-3 rounded border border-gray-300 text-right required-field">
        <input type="text" name="last_name" placeholder="نام خانوادگی"
          class="w-full p-3 rounded border border-gray-300 text-right required-field">
      </div>
      <br />
      <input type="email" name="email" placeholder="ایمیل (اختیاری)"
        class="w-full p-3 rounded border border-gray-300 text-right">
      <br /><br />
      <textarea name="message" rows="5" placeholder="توضیحات"
        class="w-full p-3 rounded border border-gray-300 text-right required-field"></textarea>
      <br /><br />
      <button type="submit"
        class="w-full bg-[var(--primary-color)] text-white py-3 rounded hover:bg-[var(--secondary-color-1)] transition">ارسال
        پیام ←</button>
    </form>
  </div>

</div>



<?php get_footer(); ?>