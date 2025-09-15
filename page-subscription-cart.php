<?php get_header() ?>



<?php
$plans = [
  'monthly' => ['label' => 'اشتراک ماهانه', 'price' => 120000],
  'quarterly' => ['label' => 'اشتراک سه‌ماهه', 'price' => 240000],
  'semiannual' => ['label' => 'اشتراک شش‌ماهه', 'price' => 500000],
];

$selected = $_POST['selected_plan'] ?? null;
$discount_code = $_POST['custom_discount_code'] ?? '';
$valid_code = 'abcd1234';
$discount_applied = ($discount_code === $valid_code);
$selected_plan = $selected && isset($plans[$selected]) ? $plans[$selected] : null;
?>

<div class="max-w-4xl mx-auto mt-7 px-4 sm:px-6 lg:px-8 py-6" dir="rtl">
  <h1 class="text-xl sm:text-2xl font-bold mb-8 text-gray-800 text-right">انتخاب اشتراک سوپرمارکت</h1>

  <!-- پلن‌های اشتراک -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
    <?php foreach ($plans as $key => $plan): ?>
      <form method="post" class="bg-white shadow-lg  rounded-lg p-4 shadow hover:shadow-xl transition text-right">
        <h2 class="text-base sm:text-lg font-semibold mb-2"><?php echo $plan['label']; ?></h2>
        <p class="text-gray-600 mb-2 text-sm">
          ارسال هر <?php echo $key === 'monthly' ? 'ماه' : ($key === 'quarterly' ? '۳ ماه' : '۶ ماه'); ?>
        </p>
        <p class="font-bold text-[var(--primary-color)] text-sm sm:text-base"><?php echo number_format($plan['price']); ?>
          تومان</p>
        <input type="hidden" name="selected_plan" value="<?php echo $key; ?>">
        <button type="submit"
          class="bg-[var(--primary-color)] text-white px-4 py-2 rounded w-full mt-2 hover:bg-[var(--secondary-color-1)] text-sm sm:text-base">
          انتخاب
        </button>
      </form>
    <?php endforeach; ?>
  </div>

  <!-- کد تخفیف -->
  <form method="post" class="discount-form mb-4">
    <label for="custom_discount_code" class="block mb-2 text-sm font-medium text-gray-700">کد تخفیف:</label>
    <div class="flex flex-row-reverse gap-2">
      <input type="text" name="custom_discount_code" id="custom_discount_code"
        class="border border-gray-600 rounded px-3 py-2 w-full text-right" placeholder="مثلاً: abcd1234">
      <input type="hidden" name="selected_plan" value="<?php echo $selected; ?>">
      <button type="submit" name="apply_custom_discount"
        class="bg-[var(--primary-color)] text-white px-4 py-2 rounded hover:bg-[var(--secondary-color-1)]">
        اعمال
      </button>
    </div>
  </form>

  <!-- سبد خرید -->
  <?php if ($selected_plan):
    $base_price = $selected_plan['price'];
    $discount_amount = $discount_applied ? $base_price * 0.2 : 0;
    $final_price = $base_price - $discount_amount;
    ?>
    <div class=" bg-white rounded-lg shadow-lg p-4 mt-8 text-right">
      <h2 class="text-lg font-semibold mb-4">سبد خرید شما</h2>
      <div class="flex justify-between mb-2 text-gray-700 text-sm sm:text-base">
        <span>پلن انتخابی:</span>
        <span class="font-bold"><?php echo $selected_plan['label']; ?></span>
      </div>
      <div class="flex justify-between mb-2 text-gray-700 text-sm sm:text-base">
        <span>قیمت اولیه:</span>
        <span class="font-bold"><?php echo number_format($base_price); ?> تومان</span>
      </div>
      <?php if ($discount_applied): ?>
        <div class="flex justify-between mb-2 text-green-600 text-sm sm:text-base">
          <span>تخفیف:</span>
          <span class="font-bold"><?php echo number_format($discount_amount); ?> تومان</span>
        </div>
      <?php endif; ?>
      <div class="flex justify-between mb-4 text-gray-800 font-bold text-sm sm:text-base">
        <span>مجموع نهایی:</span>
        <span><?php echo number_format($final_price); ?> تومان</span>
      </div>
      <!-- دکمه‌ها -->
      <div class="flex flex-col sm:flex-row-reverse gap-4">

        <button
          class="bg-[var(--primary-color)] text-white px-4 py-2 rounded hover:bg-[var(--secondary-color-1)] w-full text-sm sm:text-base">
          ادامه جهت پرداخت
        </button>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php get_footer() ?>