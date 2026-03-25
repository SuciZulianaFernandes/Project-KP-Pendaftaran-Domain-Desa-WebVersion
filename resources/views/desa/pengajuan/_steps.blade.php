<div class="flex justify-center mb-12">
    <div class="flex items-center w-full max-w-4xl">
        <!-- Step 1 -->
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 {{ $currentStep >= 1 ? 'bg-red-700' : 'bg-gray-300' }} text-white rounded-full flex items-center justify-center font-semibold">1</div>
            <span class="text-sm mt-2 {{ $currentStep >= 1 ? 'text-gray-700' : 'text-gray-500' }}">Cari Domain</span>
        </div>
        <div class="flex-1 h-1 {{ $currentStep > 1 ? 'bg-red-700' : 'bg-gray-300' }} mx-4"></div>

        <!-- Step 2 -->
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 {{ $currentStep >= 2 ? 'bg-red-700' : 'bg-gray-300' }} text-white rounded-full flex items-center justify-center font-semibold">2</div>
            <span class="text-sm mt-2 {{ $currentStep >= 2 ? 'text-gray-700' : 'text-gray-500' }}">Informasi</span>
        </div>
        <div class="flex-1 h-1 {{ $currentStep > 2 ? 'bg-red-700' : 'bg-gray-300' }} mx-4"></div>

        <!-- Step 3 -->
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 {{ $currentStep >= 3 ? 'bg-red-700' : 'bg-gray-300' }} text-white rounded-full flex items-center justify-center font-semibold">3</div>
            <span class="text-sm mt-2 {{ $currentStep >= 3 ? 'text-gray-700' : 'text-gray-500' }}">Dokumen</span>
        </div>
        <div class="flex-1 h-1 {{ $currentStep > 3 ? 'bg-red-700' : 'bg-gray-300' }} mx-4"></div>

        <!-- Step 4 -->
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 {{ $currentStep >= 4 ? 'bg-red-700' : 'bg-gray-300' }} text-white rounded-full flex items-center justify-center font-semibold">4</div>
            <span class="text-sm mt-2 {{ $currentStep >= 4 ? 'text-gray-700' : 'text-gray-500' }}">Pratinjau</span>
        </div>
    </div>
</div>