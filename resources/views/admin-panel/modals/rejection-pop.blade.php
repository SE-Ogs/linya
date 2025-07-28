<div id="rejectionModal" style="display:none;" class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50 modal-lexend">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-[400px] space-y-4">
        <p class="text-base mb-2 font-noto-sans">
            Enter the reason for <strong class="font-bold font-lexend">rejection</strong>:
        </p>
        <textarea 
            class="w-full h-32 border border-black rounded-md p-2 resize-none focus:outline-none focus:ring-2 focus:ring-orange-400 font-noto-sans" 
            placeholder="Type your reason here..."
        ></textarea>
        <div class="flex justify-end">
            <button 
                type="button" 
                onclick="submitRejection()" 
                class="bg-orange-400 text-white px-6 py-2 rounded-md hover:bg-orange-500 transition font-lexend"
            >
                Yes
            </button>
        </div>
    </div>
</div>
