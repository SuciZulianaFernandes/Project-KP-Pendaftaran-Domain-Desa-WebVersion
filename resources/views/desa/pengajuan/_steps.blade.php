<div class="flex justify-center mb-12 px-4">
    <div class="flex items-center w-full max-w-4xl overflow-x-auto scrollbar-hide">
        
        <!-- Step 1 -->
        <div class="flex flex-col items-center min-w-[70px] sm:min-w-[90px]">
            <div class="w-8 h-8 sm:w-10 sm:h-10 
                {{ $currentStep >= 1 ? 'bg-red-700' : 'bg-gray-300' }} 
                text-white rounded-full flex items-center justify-center 
                font-semibold text-sm sm:text-base">
                1
            </div>

            <span class="text-[11px] sm:text-sm mt-2 text-center whitespace-nowrap
                {{ $currentStep >= 1 ? 'text-gray-700' : 'text-gray-500' }}">
                Cari Domain
            </span>
        </div>

        <div class="flex-1 h-1 min-w-[30px] sm:min-w-[50px]
            {{ $currentStep > 1 ? 'bg-red-700' : 'bg-gray-300' }} mx-2 sm:mx-4">
        </div>

        <!-- Step 2 -->
        <div class="flex flex-col items-center min-w-[70px] sm:min-w-[90px]">
            <div class="w-8 h-8 sm:w-10 sm:h-10 
                {{ $currentStep >= 2 ? 'bg-red-700' : 'bg-gray-300' }} 
                text-white rounded-full flex items-center justify-center 
                font-semibold text-sm sm:text-base">
                2
            </div>

            <span class="text-[11px] sm:text-sm mt-2 text-center whitespace-nowrap
                {{ $currentStep >= 2 ? 'text-gray-700' : 'text-gray-500' }}">
                Informasi
            </span>
        </div>

        <div class="flex-1 h-1 min-w-[30px] sm:min-w-[50px]
            {{ $currentStep > 2 ? 'bg-red-700' : 'bg-gray-300' }} mx-2 sm:mx-4">
        </div>

        <!-- Step 3 -->
        <div class="flex flex-col items-center min-w-[70px] sm:min-w-[90px]">
            <div class="w-8 h-8 sm:w-10 sm:h-10 
                {{ $currentStep >= 3 ? 'bg-red-700' : 'bg-gray-300' }} 
                text-white rounded-full flex items-center justify-center 
                font-semibold text-sm sm:text-base">
                3
            </div>

            <span class="text-[11px] sm:text-sm mt-2 text-center whitespace-nowrap
                {{ $currentStep >= 3 ? 'text-gray-700' : 'text-gray-500' }}">
                Dokumen
            </span>
        </div>

        <div class="flex-1 h-1 min-w-[30px] sm:min-w-[50px]
            {{ $currentStep > 3 ? 'bg-red-700' : 'bg-gray-300' }} mx-2 sm:mx-4">
        </div>

        <!-- Step 4 -->
        <div class="flex flex-col items-center min-w-[70px] sm:min-w-[90px]">
            <div class="w-8 h-8 sm:w-10 sm:h-10 
                {{ $currentStep >= 4 ? 'bg-red-700' : 'bg-gray-300' }} 
                text-white rounded-full flex items-center justify-center 
                font-semibold text-sm sm:text-base">
                4
            </div>

            <span class="text-[11px] sm:text-sm mt-2 text-center whitespace-nowrap
                {{ $currentStep >= 4 ? 'text-gray-700' : 'text-gray-500' }}">
                Pratinjau
            </span>
        </div>

    </div>
</div>