@extends('layouts.app')

@section('title', 'Carte des Magasins')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css" />
    <style>
        #map {
            height: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .map-legend {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
        }

        .map-legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            margin-right: 8px;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }

        .marker-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .popup-content {
            font-size: 12px;
        }

        .popup-content strong {
            display: block;
            font-size: 13px;
            color: #1a73e8;
            margin-bottom: 5px;
        }

        .popup-item {
            margin: 3px 0;
            line-height: 1.5;
        }

        .popup-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            color: white;
            margin-bottom: 5px;
        }

        .summary-card {
            background: linear-gradient(135deg, #1a73e8 0%, #0052cc 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-card h6 {
            font-size: 12px;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .summary-card .number {
            font-size: 28px;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <!-- Résumé des Magasins -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="summary-card">
                <h6><i class="bi bi-shop me-2"></i>Total des Magasins</h6>
                <div class="number">{{ $totalMagasins }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="summary-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <h6><i class="bi bi-geo-alt me-2"></i>Magasins Affichés</h6>
                <div class="number">{{ $magasinsAffiches }}</div>
            </div>
        </div>
    </div>

    <!-- Filtres de Recherche -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtres
            </h6>
        </div>
        <div class="card-body p-3">
            <form method="GET" action="{{ route('magasin.carte') }}" id="filterForm">
                <div class="row g-3">
                    <!-- Filtre Groupe -->
                    <div class="col-lg-3 col-md-6">
                        <label for="id_groupe" class="form-label">Groupe</label>
                        <select class="form-select" id="id_groupe" name="id_groupe">
                            <option value="">Tous les groupes</option>
                            @foreach($groupes as $groupe)
                                <option value="{{ $groupe->id_groupe }}" {{ request('id_groupe') == $groupe->id_groupe ? 'selected' : '' }}>
                                    {{ $groupe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre Entité -->
                    <div class="col-lg-3 col-md-6">
                        <label for="id_entite" class="form-label">Entité</label>
                        <select class="form-select" id="id_entite" name="id_entite">
                            <option value="">Toutes les entités</option>
                            @foreach($entites as $entite)
                                <option value="{{ $entite->id_entite }}" 
                                    data-groupe="{{ $entite->id_groupe }}"
                                    data-couleur="{{ $entite->code_couleur }}"
                                    {{ request('id_entite') == $entite->id_entite ? 'selected' : '' }}>
                                    {{ $entite->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre Site -->
                    <div class="col-lg-3 col-md-6">
                        <label for="id_site" class="form-label">Site</label>
                        <select class="form-select" id="id_site" name="id_site">
                            <option value="">Tous les sites</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id_site }}" 
                                    data-entite="{{ $site->id_entite }}"
                                    {{ request('id_site') == $site->id_site ? 'selected' : '' }}>
                                    {{ $site->localisation }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre Nom -->
                    <div class="col-lg-3 col-md-6">
                        <label for="nom" class="form-label">Nom du Magasin</label>
                        <input type="text" class="form-control" id="nom" name="nom"
                            placeholder="Chercher par nom..." value="{{ request('nom') }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Rechercher
                            </button>
                            <a href="{{ route('magasin.carte') }}" class="btn btn-secondary" title="Réinitialiser">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Carte -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-geo-alt me-2"></i>Carte des Magasins
            </h6>
        </div>
        <div class="card-body p-3">
            <div id="map"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const defaultCenter = [-18.8792, 47.5079];

            const map = L.map('map', {
                zoom: 6,
                center: defaultCenter
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            const entiteCouleurs = {};
            const locations = @json($locations);
            const markersGroup = L.featureGroup();

            locations.forEach((location, index) => {
                if (location.latitude && location.longitude) {
                    const color = location.code_couleur || '#1a73e8';
                    
                    if (location.entite && !entiteCouleurs[location.entite]) {
                        entiteCouleurs[location.entite] = color;
                    }

                    const markerHtml = '<div class="marker-icon" style="background-color: ' + color + ';"><i class="bi bi-shop" style="font-size: 14px;"></i></div>';

                    const customMarker = L.divIcon({
                        html: markerHtml,
                        className: 'custom-marker',
                        iconSize: [34, 34],
                        iconAnchor: [17, 34],
                        popupAnchor: [0, -34]
                    });

                    let popupContent = '<div class="popup-content">';
                    popupContent += '<strong><a href="{{ url("magasin") }}/' + location.id + '" style="color: #1a73e8; text-decoration: none;">' + location.nom + '</a></strong>';
                    if (location.entite) {
                        popupContent += '<span class="popup-badge" style="background-color: ' + color + ';">' + location.entite + '</span>';
                    }
                    popupContent += '<div class="popup-item"><i class="bi bi-diagram-3 me-1"></i><small>' + (location.groupe || '-') + '</small></div>';
                    popupContent += '<div class="popup-item"><i class="bi bi-geo-alt me-1"></i><small>' + (location.site || '-') + '</small></div>';
                    popupContent += '<div class="popup-item"><i class="bi bi-crosshair me-1"></i><small style="color: #999;">' + location.latitude.toFixed(4) + ', ' + location.longitude.toFixed(4) + '</small></div>';
                    popupContent += '<div class="popup-item mt-2"><a href="https://maps.google.com/?q=' + location.latitude + ',' + location.longitude + '" target="_blank" class="text-decoration-none"><i class="bi bi-link-45deg"></i> Google Maps</a></div>';
                    popupContent += '</div>';

                    const marker = L.marker([location.latitude, location.longitude], {
                        icon: customMarker
                    }).bindPopup(popupContent, {
                        maxWidth: 300
                    });

                    markersGroup.addLayer(marker);
                }
            });

            markersGroup.addTo(map);

            if (markersGroup.getLayers().length > 0) {
                map.fitBounds(markersGroup.getBounds().pad(0.1));
            }

            const legend = L.control({ position: 'bottomright' });
            
            legend.onAdd = function() {
                const div = L.DomUtil.create('div', 'map-legend');
                let legendHtml = '<strong class="d-block mb-2">Légende par Entité</strong>';
                
                for (const [entite, couleur] of Object.entries(entiteCouleurs)) {
                    legendHtml += '<div class="map-legend-item"><div class="legend-color" style="background-color: ' + couleur + ';"></div><small>' + entite + '</small></div>';
                }

                if (Object.keys(entiteCouleurs).length === 0) {
                    legendHtml += '<div class="map-legend-item"><small>Aucun magasin affiché</small></div>';
                }

                div.innerHTML = legendHtml;
                return div;
            };
            legend.addTo(map);

            const groupeSelect = document.getElementById('id_groupe');
            const entiteSelect = document.getElementById('id_entite');
            const siteSelect = document.getElementById('id_site');

            function filterSelectOptions(select, filterAttr, filterValue) {
                const options = select.querySelectorAll('option');
                const currentValue = select.value;
                let hasValidSelection = false;

                options.forEach(option => {
                    if (option.value === '') {
                        option.style.display = '';
                        return;
                    }
                    
                    const attrValue = option.getAttribute(filterAttr);
                    if (!filterValue || attrValue === filterValue) {
                        option.style.display = '';
                        if (option.value === currentValue) hasValidSelection = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                if (!hasValidSelection && currentValue !== '') {
                    select.value = '';
                }
            }

            groupeSelect.addEventListener('change', function() {
                filterSelectOptions(entiteSelect, 'data-groupe', this.value);
                entiteSelect.dispatchEvent(new Event('change'));
            });

            entiteSelect.addEventListener('change', function() {
                filterSelectOptions(siteSelect, 'data-entite', this.value);
            });

            if (groupeSelect.value) {
                filterSelectOptions(entiteSelect, 'data-groupe', groupeSelect.value);
            }
            if (entiteSelect.value) {
                filterSelectOptions(siteSelect, 'data-entite', entiteSelect.value);
            }
        });
    </script>
@endpush
