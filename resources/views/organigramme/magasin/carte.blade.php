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

                    <!-- Mode d'affichage -->
                    <div class="col-lg-12">
                        <label class="form-label">Mode d'affichage</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="view_mode" id="mode_magasin" value="magasin" checked>
                            <label class="btn btn-outline-primary" for="mode_magasin">
                                <i class="bi bi-shop me-2"></i>Vue Magasins (Points)
                            </label>

                            <input type="radio" class="btn-check" name="view_mode" id="mode_site" value="site">
                            <label class="btn btn-outline-primary" for="mode_site">
                                <i class="bi bi-pentagon me-2"></i>Vue Sites (Polygones)
                            </label>
                        </div>
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
            const locations = @json($locations);
            
            const map = L.map('map', {
                zoom: 6,
                center: defaultCenter
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            const markersGroup = L.featureGroup();
            const polygonsGroup = L.featureGroup();
            
            /**
             * Algorithme de calcul de l'enveloppe convexe (Convex Hull)
             * Algorithme de Monotone Chain (Andrew) - O(n log n)
             */
            function getConvexHull(points) {
                if (points.length <= 2) return points;

                // Trier par x (longitude) puis y (latitude)
                points.sort((a, b) => a[1] !== b[1] ? a[1] - b[1] : a[0] - b[0]);

                const crossProduct = (o, a, b) => (a[1] - o[1]) * (b[0] - o[0]) - (a[0] - o[0]) * (b[1] - o[1]);

                // Coquille inférieure
                const lower = [];
                for (const p of points) {
                    while (lower.length >= 2 && crossProduct(lower[lower.length - 2], lower[lower.length - 1], p) <= 0) {
                        lower.pop();
                    }
                    lower.push(p);
                }

                // Coquille supérieure
                const upper = [];
                for (let i = points.length - 1; i >= 0; i--) {
                    const p = points[i];
                    while (upper.length >= 2 && crossProduct(upper[upper.length - 2], upper[upper.length - 1], p) <= 0) {
                        upper.pop();
                    }
                    upper.push(p);
                }

                upper.pop();
                lower.pop();
                return lower.concat(upper);
            }

            function renderMarkers() {
                markersGroup.clearLayers();
                locations.forEach(location => {
                    if (location.latitude && location.longitude) {
                        const color = location.code_couleur || '#1a73e8';
                        const markerHtml = `<div class="marker-icon" style="background-color: ${color};"><i class="bi bi-shop"></i></div>`;

                        const customMarker = L.divIcon({
                            html: markerHtml,
                            className: 'custom-marker',
                            iconSize: [34, 34],
                            iconAnchor: [17, 34],
                            popupAnchor: [0, -34]
                        });

                        let popupContent = `
                            <div class="popup-content">
                                <strong><a href="{{ url("magasin") }}/${location.id}">${location.nom}</a></strong>
                                ${location.entite ? `<span class="popup-badge" style="background-color: ${color};">${location.entite}</span>` : ''}
                                <div class="popup-item"><i class="bi bi-diagram-3 me-1"></i><small>${location.groupe || '-'}</small></div>
                                <div class="popup-item"><i class="bi bi-geo-alt me-1"></i><small>${location.site_nom || '-'}</small></div>
                                <div class="popup-item mt-2">
                                    <a href="https://maps.google.com/?q=${location.latitude},${location.longitude}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-link-45deg"></i> Google Maps
                                    </a>
                                </div>
                            </div>`;

                        const marker = L.marker([location.latitude, location.longitude], { icon: customMarker })
                            .bindPopup(popupContent);
                        
                        markersGroup.addLayer(marker);
                    }
                });
            }

            function renderPolygons() {
                polygonsGroup.clearLayers();
                
                // Grouper par site
                const sites = {};
                locations.forEach(loc => {
                    if (!loc.id_site) return;
                    if (!sites[loc.id_site]) {
                        sites[loc.id_site] = {
                            nom: loc.site_nom,
                            entite: loc.entite,
                            couleur: loc.code_couleur || '#1a73e8',
                            points: []
                        };
                    }
                    sites[loc.id_site].points.push([loc.latitude, loc.longitude]);
                });

                for (const siteId in sites) {
                    const site = sites[siteId];
                    if (site.points.length === 0) continue;

                    let layer;
                    if (site.points.length === 1) {
                        // Un seul magasin : un cercle ou un marqueur special
                        layer = L.circle(site.points[0], {
                            radius: 500,
                            color: site.couleur,
                            fillColor: site.couleur,
                            fillOpacity: 0.4
                        });
                    } else if (site.points.length === 2) {
                        // Deux magasins : une ligne epaisse
                        layer = L.polyline(site.points, {
                            color: site.couleur,
                            weight: 8,
                            opacity: 0.6
                        });
                    } else {
                        // Plus de deux : Polygone Convex Hull
                        const hullPoints = getConvexHull(site.points);
                        layer = L.polygon(hullPoints, {
                            color: site.couleur,
                            fillColor: site.couleur,
                            fillOpacity: 0.35,
                            weight: 3
                        });
                    }

                    layer.bindPopup(`
                        <div class="popup-content">
                            <strong style="color: ${site.couleur}">${site.nom}</strong>
                            <span class="popup-badge" style="background-color: ${site.couleur}">${site.entite}</span>
                            <div class="popup-item"><i class="bi bi-shop me-1"></i>${site.points.length} magasin(s)</div>
                        </div>
                    `);

                    polygonsGroup.addLayer(layer);
                }
            }

            function updateView() {
                const mode = document.querySelector('input[name="view_mode"]:checked').value;
                
                if (mode === 'magasin') {
                    map.removeLayer(polygonsGroup);
                    markersGroup.addTo(map);
                    if (markersGroup.getLayers().length > 0) {
                        map.fitBounds(markersGroup.getBounds().pad(0.1));
                    }
                } else {
                    map.removeLayer(markersGroup);
                    polygonsGroup.addTo(map);
                    if (polygonsGroup.getLayers().length > 0) {
                        map.fitBounds(polygonsGroup.getBounds().pad(0.1));
                    }
                }
            }

            // Initialisation
            renderMarkers();
            renderPolygons();
            updateView();

            // Event Listeners
            document.querySelectorAll('input[name="view_mode"]').forEach(radio => {
                radio.addEventListener('change', updateView);
            });

            // Filtre Select dynamiques (existant)
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

            if (groupeSelect.value) filterSelectOptions(entiteSelect, 'data-groupe', groupeSelect.value);
            if (entiteSelect.value) filterSelectOptions(siteSelect, 'data-entite', entiteSelect.value);

            // Mise à jour de la légende
            const legend = L.control({ position: 'bottomright' });
            legend.onAdd = function() {
                const div = L.DomUtil.create('div', 'map-legend');
                const entiteCouleurs = {};
                locations.forEach(l => { if(l.entite) entiteCouleurs[l.entite] = l.code_couleur; });

                let legendHtml = '<strong class="d-block mb-2">Légende par Entité</strong>';
                for (const [entite, couleur] of Object.entries(entiteCouleurs)) {
                    legendHtml += `<div class="map-legend-item"><div class="legend-color" style="background-color: ${couleur};"></div><small>${entite}</small></div>`;
                }
                div.innerHTML = legendHtml || '<small>Aucune donnée</small>';
                return div;
            };
            legend.addTo(map);
        });
    </script>
@endpush
