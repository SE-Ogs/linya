<style>
  @keyframes gradientMove {
    0% { background-position: 0% 0%; }
    50% { background-position: 100% 100%; }
    100% { background-position: 0% 0%; }
  }

  .bg-animate {
    animation: gradientMove 16s ease-in-out infinite;
    background-size: 400% 400%;
  }
</style>


<div class="max-w-5xl mx-auto my-12 px-6 animate-fade-up">
    @if(!isset($ad))
    <div
        class="cursor-pointer"
        data-bs-toggle="modal"
        data-bs-target="#contactModal"
    >
        <div class="rounded-2xl bg-gradient-to-r from-blue-500 via-indigo-400 to-orange-400 bg-opacity-40 backdrop-blur-md border border-white border-opacity-30 shadow-lg p-6 md:p-10 text-center animate-fade-in transition-transform duration-300 hover:scale-[1.02] hover:shadow-xl bg-animate">
            <h2 class="text-2xl font-semibold text-white/90 mb-2">🚀 Sponsored</h2>
            <p class="text-white/70 text-sm md:text-base">Looking to promote your product here? Contact us for ad opportunities!</p>
        </div>
    </div>
    @else
    {{-- Placeholder for when actual ad is provided --}}
    <div class="rounded-2xl bg-white shadow-md p-6 text-center">
        {{-- Your ad content goes here --}}
        <h2 class="text-xl font-bold">[Ad Title]</h2>
        <p>[Ad Description]</p>
    </div>
    @endif
</div>
