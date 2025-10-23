<?php
$pageTitle = 'Find Locations - EcoCollect Lanka';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../helpers/utils.php';

// Get search parameters
$district = $_GET['district'] ?? '';
$type = $_GET['type'] ?? '';

// Search or get all locations
if (!empty($district) || !empty($type)) {
    $locations = Location::search($district, $type);
} else {
    $locations = Location::getAll(); // Show all locations instead of just verified
}

$districts = getDistricts();

include __DIR__ . '/includes/header.php';
?>

<main class="page-content">
    <div class="container">
        <section class="page-hero">
            <h1>Find Collection Locations</h1>
            <p>Discover nearby plastic collection points and drop-off centers across Sri Lanka</p>
        </section>

        <section class="location-search">
            <div class="search-container">
                <form method="GET" action="/views/location.php" class="search-filters">
                    <div class="filter-group">
                        <label for="district-filter">District</label>
                        <select id="district-filter" name="district">
                            <option value="">All Districts</option>
                            <?php foreach ($districts as $dist): ?>
                                <option value="<?php echo $dist; ?>" <?php echo $district === $dist ? 'selected' : ''; ?>>
                                    <?php echo $dist; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="type-filter">Collection Type</label>
                        <select id="type-filter" name="type">
                            <option value="">All Types</option>
                            <option value="drop-off" <?php echo $type === 'drop-off' ? 'selected' : ''; ?>>Drop-off Center</option>
                            <option value="pickup" <?php echo $type === 'pickup' ? 'selected' : ''; ?>>Pickup Service</option>
                            <option value="recycling" <?php echo $type === 'recycling' ? 'selected' : ''; ?>>Recycling Center</option>
                            <option value="community" <?php echo $type === 'community' ? 'selected' : ''; ?>>Community Point</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="/views/location.php" class="btn btn-secondary">Clear Filters</a>
                </form>
            </div>
        </section>

        <section class="location-results">
            <h2>Available Locations (<?php echo count($locations); ?> found)</h2>
            
            <?php if (empty($locations)): ?>
                <div class="no-results">
                    <p>No collection centers found matching your criteria.</p>
                    <p>Try adjusting your filters or <a href="/views/location.php">view all locations</a>.</p>
                </div>
            <?php else: ?>
                <div class="locations-grid">
                    <?php foreach ($locations as $location): ?>
                        <div class="location-card">
                            <div class="location-header">
                                <h3><?php echo htmlspecialchars($location['name']); ?></h3>
                                <span class="location-type type-<?php echo $location['type']; ?>">
                                    <?php echo ucfirst($location['type']); ?>
                                </span>
                            </div>
                            
                            <div class="location-details">
                                <p><strong>📍 Address:</strong><br>
                                   <?php echo htmlspecialchars($location['address']); ?></p>
                                
                                <?php if (!empty($location['district'])): ?>
                                    <p><strong>🗺️ District:</strong> <?php echo htmlspecialchars($location['district']); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($location['phone'])): ?>
                                    <p><strong>📞 Phone:</strong> 
                                       <a href="tel:<?php echo htmlspecialchars($location['phone']); ?>">
                                           <?php echo htmlspecialchars($location['phone']); ?>
                                       </a>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($location['hours'])): ?>
                                    <p><strong>🕐 Hours:</strong> <?php echo htmlspecialchars($location['hours']); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($location['description'])): ?>
                                    <p class="location-description"><?php echo htmlspecialchars($location['description']); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($is_logged_in && getCurrentUserType() === 'household'): ?>
                                <div class="location-actions">
                                    <a href="/views/request-pickup.php?center=<?php echo $location['id']; ?>" 
                                       class="btn btn-primary btn-small">Request Pickup</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <?php if (!$is_logged_in): ?>
            <section class="cta-section" style="text-align: center; padding: 60px 20px; background: linear-gradient(135deg, #2E8B57 0%, #20B2AA 100%); border-radius: 10px; color: white; margin: 40px 0; overflow: hidden;">
                <div style="max-width: 600px; margin: 0 auto; display: flex; flex-direction: column; align-items: center; gap: 25px;">
                    <h2 style="color: white; font-size: 2.5em; margin: 0; line-height: 1.2; letter-spacing: -0.5px;">Want to add your collection center?</h2>
                    <p style="font-size: 18px; line-height: 1.6; margin: 0; opacity: 0.9;color: white;">Register now and join our network of verified collection partners</p>
                    <a href="/views/register.php" style="display: inline-block; background: white; color: #2E8B57; padding: 15px 40px; font-size: 18px; font-weight: bold; text-decoration: none; border-radius: 30px; transition: all 0.3s ease; border: 2px solid transparent; margin-top: 10px; cursor: pointer;" onmouseover="this.style.background='transparent'; this.style.color='white'; this.style.borderColor='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0, 0, 0, 0.2)'" onmouseout="this.style.background='white'; this.style.color='#2E8B57'; this.style.borderColor='transparent'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">Register Your Center</a>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<style>
.search-filters {
    display: flex;
    gap: 15px;
    align-items: flex-end;
    flex-wrap: wrap;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    margin: 20px 0;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

.filter-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

.locations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
    margin: 30px 0;
}

.location-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.location-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.location-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.location-header h3 {
    margin: 0;
    color: #2E8B57;
    font-size: 18px;
}

.location-type {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.type-drop-off { background: #d4edda; color: #155724; }
.type-pickup { background: #d1ecf1; color: #0c5460; }
.type-recycling { background: #fff3cd; color: #856404; }
.type-community { background: #f8d7da; color: #721c24; }

.location-details p {
    margin: 10px 0;
    color: #555;
    line-height: 1.6;
}

.location-description {
    font-style: italic;
    color: #666;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.location-actions {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.btn-small {
    padding: 8px 16px;
    font-size: 14px;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 10px;
    color: #666;
}

.no-results p {
    font-size: 16px;
    margin: 10px 0;
}

@media (max-width: 768px) {
    .search-filters {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .locations-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
