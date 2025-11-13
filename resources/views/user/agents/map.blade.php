{{-- resources/views/user/agents/map.blade.php --}}
@extends('layouts.user')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <div>
      <h1 class="text-2xl font-semibold text-slate-800 flex items-center gap-2">
        🗺️ Masref Agent Locator
      </h1>
      <p class="text-sm text-slate-500">Find your nearest Masref agent quickly and easily.</p>
    </div>
    <div>
      <button onclick="mapLocateUser()"
        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg 
               bg-blue-600 text-white hover:bg-blue-700 transition">
        📍 Center on My Location
      </button>
    </div>
  </div>

  {{-- Map Container --}}
  <div class="bg-white border border-slate-200 rounded-2xl shadow-sm relative">
    <div id="agentsMap" class="w-full rounded-2xl" style="height: 640px;"></div>

    {{-- Popup container (custom feedback messages) --}}
    <div id="mapMessage" 
         class="hidden absolute top-4 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-lg z-[9999] transition">
    </div>
  </div>

  <p class="text-xs text-slate-400 text-center">Tip: Tap a marker to view agent details.</p>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
  crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
  crossorigin=""></script>

<script>
(function() {
  const agents = @json($agents);

  // Map setup (always light theme)
  const map = L.map('agentsMap', {
    scrollWheelZoom: true,
    zoomControl: true
  }).setView([33.8938, 35.5018], 11);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19
  }).addTo(map);

  // Custom marker icon
  const blueIcon = new L.Icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
    iconSize: [30, 30],
    iconAnchor: [15, 30],
    popupAnchor: [0, -28]
  });

  const markers = [];
  agents.forEach(agent => {
    if (!agent.latitude || !agent.longitude) return;

    const marker = L.marker([agent.latitude, agent.longitude], { icon: blueIcon }).addTo(map);

    const phone = agent.phone
      ? `<div class="mt-1 text-sm text-gray-700">☎ ${agent.phone}</div>`
      : '';

    const hours = agent.opening_hours
      ? Object.entries(agent.opening_hours)
              .map(([day, val]) => `<div><strong>${day}</strong>: ${val}</div>`)
              .join('')
      : '<div class="text-gray-400">Hours not available</div>';

    const popup = `
      <div style="min-width:220px; font-family:sans-serif;">
        <h3 style="font-size:15px; margin:0; font-weight:600; color:#1e3a8a;">${agent.name}</h3>
        <div style="font-size:13px; color:#555; margin-top:2px;">
          ${agent.address ?? ''}${agent.city ? ', ' + agent.city : ''}${agent.country ? ', ' + agent.country : ''}
        </div>
        ${phone}
        <div style="margin-top:6px; font-size:12px; color:#666;">${hours}</div>
        <div style="margin-top:8px;">
          <a href="https://www.google.com/maps?q=${agent.latitude},${agent.longitude}" target="_blank"
             style="display:inline-flex;align-items:center;gap:6px;
                    background-color:#2563eb;color:white;padding:6px 12px;
                    border-radius:6px;font-size:12px;text-decoration:none;">
            📍 Open in Google Maps
          </a>
        </div>
      </div>
    `;
    marker.bindPopup(popup);
    markers.push(marker);
  });

  if (markers.length) {
    const group = L.featureGroup(markers);
    map.fitBounds(group.getBounds().pad(0.2));
  }

  // Small animated popup messages
  const messageBox = document.getElementById('mapMessage');
  function showMapMessage(text, color = 'bg-blue-600') {
    messageBox.textContent = text;
    messageBox.className = `absolute top-4 left-1/2 transform -translate-x-1/2 ${color} text-white text-sm font-medium px-4 py-2 rounded-lg shadow-lg z-[9999] transition`;
    messageBox.classList.remove('hidden');
    setTimeout(() => messageBox.classList.add('hidden'), 3000);
  }

  // Locate user with popup feedback
  window.mapLocateUser = function() {
    if (!navigator.geolocation) {
      showMapMessage('Geolocation not supported ❌', 'bg-red-600');
      return;
    }
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const { latitude, longitude } = pos.coords;
        const userMarker = L.circleMarker([latitude, longitude], {
          radius: 7,
          color: '#22c55e',
          fillColor: '#22c55e',
          fillOpacity: 0.8
        }).addTo(map);
        userMarker.bindTooltip('📍 You are here').openTooltip();
        map.setView([latitude, longitude], 13);
        showMapMessage('✅ Your location detected successfully');
      },
      () => {
        showMapMessage('⚠️ Unable to detect your location', 'bg-yellow-600');
      }
    );
  };
})();
</script>
@endsection
