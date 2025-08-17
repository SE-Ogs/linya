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

    .ban-details-enter {
        max-height: 0;
        opacity: 0;
        transform: translateY(-10px);
    }

    .ban-details-active {
        max-height: 200px;
        opacity: 1;
        transform: translateY(0);
        transition: all 0.3s ease-out;
    }
</style>

<div id="bannedModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 text-black">
    <div id="bannedModalContent"
         class="flex min-h-[420px] w-[609px] flex-col flex-wrap items-center justify-center rounded-[60px] bg-white transition-all duration-300">
        <div class="flex flex-col flex-wrap items-center justify-center px-20 pt-5">
            <h1 class="rainbow-text mb-5 text-[35px] font-bold transition-all duration-300">Access Denied</h1>

            <p class="flex justify-center">Your account has been&nbsp;<span
                      class="font-bold text-red-500">banned</span>&nbsp;for violating our Terms of</p>
            <p class="flex justify-center">Service or Community Guidelines.</p>
            <p class="mb-5 flex justify-center">You will not be able to log in or use our services.</p>

            <p class="mb-5 flex justify-center">If you believe this is a mistake, &nbsp;<span
                      class="rainbow-text transition-all duration-300">please contact support</span>&nbsp;</p>
        </div>

        <!-- Ban Details Section (Initially Hidden) -->
        <div id="banDetails"
             class="ban-details-enter w-full overflow-hidden px-8">
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
                <h3 class="mb-2 font-bold text-red-800">Ban Details:</h3>
                <div class="text-sm text-red-700">
                    <p><strong>Reason:</strong> {{ session('ban_details')?->reason ?? 'No specific reason provided' }}
                    </p>
                    <p><strong>Banned on:</strong>
                        {{ session('ban_details')?->banned_at ? session('ban_details')->banned_at->format('M d, Y \a\t g:i A') : 'Unknown' }}
                    </p>

                    @if (session('ban_details')?->unbanned_at)
                        <p><strong>Ban expires:</strong>
                            {{ session('ban_details')->unbanned_at->format('M d, Y \a\t g:i A') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <hr class="mb-4 w-full border-gray-300">

        <button id="viewDetailsBtn"
                class="rainbow-text mb-4 font-bold transition-all duration-300 hover:cursor-pointer">
            <span id="viewDetailsText">View Details</span>
        </button>

        <hr class="mb-4 w-full border-gray-300">

        <button id="contactUs"
                class="rainbow-text mb-4 font-bold transition-all duration-300 hover:cursor-pointer">
            Contact Support
        </button>

        <hr class="mb-4 w-full border-gray-300">

        <button id="closeBannedModal"
                class="rainbow-text cursor-pointer font-bold transition-all duration-300">
            Dismiss
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewDetailsBtn = document.getElementById('viewDetailsBtn');
        const viewDetailsText = document.getElementById('viewDetailsText');
        const banDetails = document.getElementById('banDetails');
        const modalContent = document.getElementById('bannedModalContent');
        let detailsVisible = false;

        // Auto-show modal if ban details exist
        @if (session('ban_details'))
            document.getElementById('bannedModal').classList.remove('hidden');
        @endif

        if (viewDetailsBtn && banDetails) {
            viewDetailsBtn.addEventListener('click', function() {
                if (!detailsVisible) {
                    // Show details
                    banDetails.classList.remove('ban-details-enter');
                    banDetails.classList.add('ban-details-active');
                    viewDetailsText.textContent = 'Hide Details';
                    detailsVisible = true;
                } else {
                    // Hide details
                    banDetails.classList.remove('ban-details-active');
                    banDetails.classList.add('ban-details-enter');
                    viewDetailsText.textContent = 'View Details';
                    detailsVisible = false;
                }
            });
        }
    });
</script>
