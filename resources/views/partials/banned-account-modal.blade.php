<style>
    .rainbow-text {
        background: linear-gradient(270deg, #ff6ec4, #7873f5, #1fd1f9, #76ff7a, #f9ff6e, #ff6ec4);
        background-size: 1200% 1200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
    }

    @keyframes rainbow-move {
        0% {
            background-position: 0% 50%;
        }

        100% {
            background-position: 100% 50%;
        }
    }
</style>
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 text-black">
    <div class="flex h-[420px] w-[609px] flex-col flex-wrap items-center justify-center rounded-[60px] bg-white">
        <div class="flex flex-col flex-wrap items-center justify-center pl-20 pr-20 pt-5">
            <h1 class="rainbow-text mb-5 text-[35px] font-bold transition-all duration-300">Access Denied</h1>

            <p class="flex justify-center">Your account has been&nbsp;<span
                      class="font-bold text-red-500">banned</span>&nbsp;for
                violating
                our Terms of </p>
            <p class="flex justify-center">Service or Community
                Guidelines. </p>
            <p class="mb-5 flex justify-center"> You will not be able
                to log in or use our services.</p>

            <p class="mb-5 flex justify-center">If you believe this is a mistake, &nbsp;<span
                      class="rainbow-text transition-all duration-300">please
                    contact support</span>&nbsp;</p>

        </div>

        <hr class="mb-4 w-full border-gray-300">
        <h2 class="rainbow-text mb-4 font-bold transition-all duration-300">View Details</h2>
        <hr class="mb-4 w-full border-gray-300">
        <h2 class="rainbow-text mb-4 font-bold transition-all duration-300">Contact Support</h2>
        <hr class="mb-4 w-full border-gray-300">
        <h2 class="rainbow-text font-bold transition-all duration-300">Dismiss</h2>

    </div>
</div>
