<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Calificar Viaje') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <!-- Trip Info -->
                <div class="mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detalles del Viaje</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Origen</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $trip->origin_zone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Destino</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $trip->destination_zone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Fecha y Hora</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $trip->departure_time->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Estado</p>
                            <p class="font-semibold text-gray-900 dark:text-white">Completado</p>
                        </div>
                    </div>
                </div>

                <!-- Users to Rate -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Califica a los participantes</h3>
                    
                    @foreach($usersToRate as $user)
                        @php
                            $existingRating = $trip->ratings->where('from_user_id', auth()->id())->where('to_user_id', $user->id)->first();
                        @endphp

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                            <!-- User Info -->
                            <div class="flex items-center mb-6">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="h-16 w-16 rounded-full object-cover mr-4">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center mr-4 text-lg font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-lg text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        @if($trip->driver_id === $user->id)
                                            Conductor
                                        @else
                                            Pasajero
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($existingRating)
                                <!-- Already Rated -->
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded p-4">
                                    <p class="text-green-800 dark:text-green-200 font-medium mb-3">✓ Ya calificado</p>
                                    <div class="space-y-2">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Calificación:</p>
                                            <div class="flex gap-1 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $existingRating->rating)
                                                        <span class="text-2xl">⭐</span>
                                                    @else
                                                        <span class="text-2xl text-gray-300 dark:text-gray-600">☆</span>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        @if($existingRating->review)
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Reseña:</p>
                                                <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $existingRating->review }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Rating Form -->
                                <form action="{{ route('trips.submit-rating', $trip) }}" method="POST" class="space-y-4">
                                    @csrf

                                    <input type="hidden" name="to_user_id" value="{{ $user->id }}">

                                    <!-- Star Rating -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                            Calificación
                                        </label>
                                        <div class="flex gap-2" id="rating-{{ $user->id }}">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label class="cursor-pointer group">
                                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden" required>
                                                    <span class="text-4xl transition-colors" data-rating="{{ $i }}">
                                                        ☆
                                                    </span>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Review -->
                                    <div>
                                        <label for="review-{{ $user->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Reseña (Opcional)
                                        </label>
                                        <textarea id="review-{{ $user->id }}" name="review" rows="3"
                                            placeholder="Comparte tu experiencia con este usuario..."
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                    </div>

                                    <!-- Submit -->
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                                        Enviar Calificación
                                    </button>
                                </form>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const ratingDiv = document.getElementById('rating-{{ $user->id }}');
                                        const stars = ratingDiv.querySelectorAll('span[data-rating]');
                                        const radios = ratingDiv.querySelectorAll('input[type="radio"]');

                                        stars.forEach(star => {
                                            star.addEventListener('mouseover', function() {
                                                const rating = this.dataset.rating;
                                                stars.forEach(s => {
                                                    if (s.dataset.rating <= rating) {
                                                        s.textContent = '⭐';
                                                        s.style.color = '#fbbf24';
                                                    } else {
                                                        s.textContent = '☆';
                                                        s.style.color = '#d1d5db';
                                                    }
                                                });
                                            });

                                            star.addEventListener('click', function() {
                                                const rating = this.dataset.rating;
                                                radios.forEach((radio, idx) => {
                                                    if (idx + 1 == rating) {
                                                        radio.checked = true;
                                                    }
                                                });
                                            });
                                        });

                                        ratingDiv.addEventListener('mouseout', function() {
                                            const checked = ratingDiv.querySelector('input[type="radio"]:checked');
                                            if (checked) {
                                                const rating = checked.value;
                                                stars.forEach(s => {
                                                    if (s.dataset.rating <= rating) {
                                                        s.textContent = '⭐';
                                                        s.style.color = '#fbbf24';
                                                    } else {
                                                        s.textContent = '☆';
                                                        s.style.color = '#d1d5db';
                                                    }
                                                });
                                            } else {
                                                stars.forEach(s => {
                                                    s.textContent = '☆';
                                                    s.style.color = '#d1d5db';
                                                });
                                            }
                                        });
                                    });
                                </script>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Back Button -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium">
                        ← Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>