<?php
/*
Template Name: Login Page
*/
?>

<?php

ob_start();
get_header();

$message_data = custom_get_login_message();
$message      = $message_data['text'] ?? '';
$message_type = $message_data['type'] ?? '';
$form_mode    = $message_data['form'] ?? 'login';


?>

<style>
    .slide-image {
        transition: transform 0.8s ease-in-out;
    }

    .form-panel {
        transition: opacity 0.5s ease;
    }

    .hidden-panel {
        opacity: 0;
        pointer-events: none;
    }

    .visible-panel {
        opacity: 1;
        pointer-events: auto;
    }
</style>

<!-- banners -->
<?php
$args = array(
    'post_type' => 'banner',
    'posts_per_page' => 1, // فقط 1 بنر
    'tax_query' => array(
        array(
            'taxonomy' => 'banner_category',
            'field' => 'slug',
            'terms' => 'login', // اسم دسته
        ),
    ),
);
$banners = new WP_Query($args);
?>




<?php if (!empty($message)): ?>
    <div id="customAlert" class="absolute lg:top-[24%] lg:right-3 top-37 right-0 ml-6 md:top-[20%]  transform  mt-2 z-50 flex items-start gap-4 px-5 py-4 rounded-lg shadow-xl text-[#fafafa] text-sm leading-relaxed
    <?php echo ($message_type === 'success') ? 'bg-[#74c69d]' : 'bg-[#e35053]'; ?>">

        <div class="flex-1"><?php echo $message; ?></div>

        <button onclick="closeAlert()" class="text-[#fafafa] text-xl font-bold hover:text-gray-200">&times;</button>
    </div>
<?php endif; ?>

<div class="relative w-[90%] md:w-[70%] lg:w-[60%]  h-[580px] mx-auto my-5 shadowbox overflow-hidden">





    <!-- بنر -->
    <?php
    if ($banners->have_posts()):
        while ($banners->have_posts()):
            $banners->the_post(); ?>
            <div id="imageContainer" class="absolute top-0 left-0 w-1/2 h-full z-10 slide-image hidden lg:block">
                <?php the_post_thumbnail('full', array('class' => 'class="w-full h-full object-cover"')); ?>
            </div>
        <?php endwhile;
        wp_reset_postdata();
    else:
        echo "<p>هیچ بنری پیدا نشد</p>";
    endif;
    ?>


    <!-- فرم‌ها -->
    <div class="w-full h-full flex relative">
        <!-- فرم ثبت‌نام -->
        <div id="registerForm"
            class="form-panel absolute left-0 lg:w-1/2 w-full h-full flex flex-col justify-center items-center px-10 bg-[#fafafa] <?php echo ($form_mode === 'register') ? 'visible-panel' : 'hidden-panel' ?> opacity-0 lg:opacity-100">
            <h2 class="text-3xl font-bold text-[#4a4a4a] mb-2">ثبت‌نام</h2>
            <form method="post" class=" w-full ">
                <label for="reg_username " class="text-[#4a4a4a] text-base text-end font-semibold ">نام کاربری</label>
                <input type="text" name="reg_username" required
                    class="w-full px-4 py-2 mt-1 mb-4 border border-[#c0c0c0] rounded focus:outline focus:outline-[#c9a6df] border-1.5 focus:ring-[#c9a6df]">

                <label for="reg_email" class="text-[#4a4a4a] text-base text-end font-semibold ">ایمیل</label>
                <input type="email" name="reg_email" class="
                           w-full px-4 py-2.5 mt-1
                           border border-[#c0c0c0]
                           rounded mb-4
                           invalid:border-red-400 invalid:text-red-500 focus:border-sky-500
                           focus:outline focus:outline-[#c9a6df] focus:invalid:border-red-500
                           focus:invalid:outline-red-400 disabled:border-gray-200 disabled:bg-gray-50
                           disabled:text-gray-500 disabled:shadow-none
                           text-sm" />
                <p class="hidden peer-focus:peer-invalid:not-placeholder-shown:block text-xs text-red-400 mb-2">
                    ایمیل را درست وارد کنید.
                </p>





                <label for="login_password" class="text-[#4a4a4a] text-base text-end font-semibold">رمز عبور</label>
                <input type="password" id="reg_password" name="reg_password" required
                    class="w-full px-4 mt-1 py-1.5 border border-[#c0c0c0] rounded focus:outline focus:outline-[#c9a6df] border-1.5 focus:ring-[#c9a6df] ">
                <label class="flex items-center gap-2  text-xs text-[#4a4a4a] ml-auto mt-1 mb-4">
                    <input type="checkbox" onclick="togglePassword()" class="cursor-pointer">
                    نمایش رمز عبور
                </label>


                <label for="repassword" class="text-[#4a4a4a] text-base text-end font-semibold">تکرار رمز عبور</label>
                <input type="password" id="reg_repassword" name="reg_repassword" required
                    class="w-full mt-1 px-4 py-2 border border-[#c0c0c0] rounded focus:outline focus:outline-[#c9a6df] border-1.5 focus:ring-[#c9a6df] ">
                <label class="flex items-center gap-2  text-xs text-[#4a4a4a] ml-auto mt-1">
                    <input type="checkbox" onclick="togglePassword2()" class="cursor-pointer ">
                    نمایش رمز عبور
                </label>


                <input type="submit" name="register_user" value="ثبت‌نام"
                    class="w-full bg-[var(--primary-color)] text-[#fafafa] py-2 rounded hover:bg-[var(--secondary-color-1)] transition mt-4">
            </form>
            <p class="mt-4 text-sm text-[#4a4a4a]">قبلاً ثبت‌نام کردید؟
                <button onclick="showLogin()"
                    class="text-[var(--primary-color)] hover:underline hover:text-[var(--secondary-color-1)]">وارد
                    شوید</button>
            </p>
        </div>

        <!-- فرم ورود -->
        <div id="loginForm"
            class="form-panel absolute right-0 lg:w-1/2 w-full  h-full  items-center px-10 bg-[#fafafa] <?php echo ($form_mode === 'login') ? 'visible-panel' : 'hidden-panel'; ?> flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-[#4a4a4a] mb-2">ورود</h2>
            <p class="text-[#4a4a4a] opacity-50 text-sm mb-7">خوش آمدید!لطفا اطلاعات خود را وارد کنید.</p>
            <form method="post" class="space-y-5 w-full ">
                <label for="login_username " class="text-[#4a4a4a] text-base text-end font-semibold ">نام کاربری</label>
                <input type="text" name="login_username" required
                    class="w-full px-4 py-2 mt-1 border border-[#c0c0c0] rounded focus:outline focus:outline-[#c9a6df] border-1.5 focus:ring-[#c9a6df]">
                <label for="login_password" class="text-[#4a4a4a] text-base text-end font-semibold">رمز عبور</label>
                <input type="password" id="login_password" name="login_password" required
                    class="w-full mt-1 px-4 py-2 border border-[#c0c0c0] rounded focus:outline focus:outline-[#c9a6df] border-1.5 focus:ring-[#c9a6df]">
                <!-- چک‌باکس برای نمایش رمز -->

                <label class="flex items-center gap-2  text-xs text-[#4a4a4a] ml-auto">
                    <input type="checkbox" onclick="togglePassword3()" class="cursor-pointer">
                    نمایش رمز عبور
                </label>
                <input type="submit" name="login_user" value="ورود"
                    class="w-full bg-[var(--primary-color)] text-[#fafafa] py-2 rounded hover:bg-[var(--secondary-color-1)] transition mt-8">

            </form>

            <p class="mt-4 text-sm text-[#4a4a4a] ">ثبت‌نام نکردید؟
                <button onclick="showRegister()"
                    class="text-[var(--primary-color)] hover:underline hover:text-[var(--secondary-color-1)]">همین حالا
                    ثبت‌نام کنید</button>
            </p>
        </div>

    </div>
</div>

<script>

    const imageContainer = document.getElementById('imageContainer');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    function showRegister() {
        loginForm.classList.remove('visible-panel');
        loginForm.classList.add('hidden-panel');
        registerForm.classList.remove('hidden-panel');
        registerForm.classList.add('visible-panel');
        imageContainer.style.transform = 'translateX(100%)';
        localStorage.setItem('form_mode', 'register');
    }

    function showLogin() {
        registerForm.classList.remove('visible-panel');
        registerForm.classList.add('hidden-panel');
        loginForm.classList.remove('hidden-panel');
        loginForm.classList.add('visible-panel');
        imageContainer.style.transform = 'translateX(0%)';
        localStorage.setItem('form_mode', 'login');
    }

    // وقتی صفحه لود شد، حالت ذخیره‌شده رو اعمال کن
    window.addEventListener('load', () => {
        const savedMode = localStorage.getItem('form_mode');
        if (savedMode === 'register') {
            showRegister();
        } else {
            showLogin();
        }
    });



    function closeAlert() {
        const alertBox = document.getElementById('customAlert');
        if (alertBox) {
            alertBox.style.opacity = '0';
            alertBox.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alertBox.style.transform = 'translateY(-10px)';
            setTimeout(() => alertBox.remove(), 500);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const alertBox = document.getElementById('customAlert');
        if (alertBox) {
            setTimeout(() => closeAlert(), 10000); // بعد از ۱۰ ثانیه محو می‌شه
        }
    });

    function togglePassword() {
        const input = document.getElementById("reg_password");
        input.type = input.type === "password" ? "text" : "password";

    }
    function togglePassword2() {
        const input = document.getElementById("reg_repassword");
        input.type = input.type === "password" ? "text" : "password";

    }
    function togglePassword2() {
        const input = document.getElementById("login_password");
        input.type = input.type === "password" ? "text" : "password";

    }
</script>

<?php get_footer();
ob_end_flush(); ?>



