@extends('layouts.agent')

@section('content')
<div class="p-8">

    <h1 class="text-3xl font-bold mb-6">Manage Your Location</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('agent.location.update') }}" method="POST">
        @csrf

        <div id="map"
             data-lat="{{ $agent->latitude ?? 33.88894 }}"
             data-lng="{{ $agent->longitude ?? 35.49442 }}"
             class="w-full h-96 rounded shadow mb-4">
        </div>

        <div class="grid grid-cols-2 gap-6 mt-4">
            <div>
                <label class="text-gray-700 font-semibold">Latitude</label>
                <input id="lat" name="latitude"
                       class="w-full border p-2 rounded"
                       value="{{ $agent->latitude }}">
            </div>

            <div>
                <label class="text-gray-700 font-semibold">Longitude</label>
                <input id="lng" name="longitude"
                       class="w-full border p-2 rounded"
                       value="{{ $agent->longitude }}">
            </div>
        </div>

        <button class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded shadow">
            Save Location
        </button>

    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const mapDiv = document.getElementById("map");
    const lat = parseFloat(mapDiv.dataset.lat);
    const lng = parseFloat(mapDiv.dataset.lng);

    // Init map
    const map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
    }).addTo(map);

    // Draggable marker
    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    marker.on('dragend', function (e) {
        const pos = marker.getLatLng();
        document.getElementById("lat").value = pos.lat;
        document.getElementById("lng").value = pos.lng;
    });

});
</script>
@endsection
