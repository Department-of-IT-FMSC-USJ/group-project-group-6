// Consolidated dashboard behavior (merged + simplified)
document.addEventListener('DOMContentLoaded', function () {
    // Ensure modal close buttons work (use .show class)
    document.querySelectorAll('.modal .close').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var modal = btn.closest('.modal');
            if (modal) modal.classList.remove('show');
        });
    });

    // Animate dashboard cards into view
    var cards = document.querySelectorAll('.dashboard-main .dashboard-card');
    cards.forEach(function(card, i){
        setTimeout(function(){ card.classList.add('visible'); }, 90 * i);
    });

    // Small safeguard: ensure at least one dashboard content is visible
    (function(){
        var anyActive = document.querySelector('.dashboard-content.active');
        if(!anyActive){
            var first = document.querySelector('.dashboard-content');
            if(first) first.classList.add('active');
        }
    })();
});

// Dashboard switching (for demo purposes)
window.showDashboard = window.showDashboard || function(type) {
    const dashboards = document.querySelectorAll('.dashboard-content');
    dashboards.forEach(dashboard => {
        dashboard.classList.remove('active');
        if (dashboard.id === `${type}Dashboard`) {
            dashboard.classList.add('active');
        }
    });
};

// Show request pickup modal
window.showRequestPickup = window.showRequestPickup || function() {
    const modal = document.getElementById('requestModal');
    if (modal) {
        modal.classList.add('show');
    }
};

// Close request pickup modal
window.closeRequestPickup = window.closeRequestPickup || function() {
    const modal = document.getElementById('requestModal');
    if (modal) {
        modal.classList.remove('show');
    }
};

// Handle pickup request submission
window.submitPickupRequest = window.submitPickupRequest || function(event) {
    event.preventDefault();
    const form = event.target;
    const date = form.pickupDate.value;
    const time = form.pickupTime.value;
    const weight = form.estimatedWeight.value;
    const plasticTypes = Array.from(form.plasticTypes || [])
        .filter(input => input.checked)
        .map(input => input.value);

    if (plasticTypes.length === 0) {
        showAlert('Please select at least one plastic type.', 'error');
        return;
    }

    console.log('Pickup Request:', { date, time, weight, plasticTypes });
    showAlert('Pickup request submitted successfully!');
    closeRequestPickup();
    // Simulate adding to scheduled pickups
    const scheduledList = document.getElementById('scheduledPickups');
    if (scheduledList) {
        const item = document.createElement('div');
        item.className = 'scheduled-item';
        item.innerHTML = `
            <span class="scheduled-date">${date}</span>
            <span class="scheduled-time">${time}</span>
            <span class="scheduled-status pending">Pending</span>
        `;
        scheduledList.appendChild(item);
    }
};

// Show redeem points (placeholder)
window.showRedeemPoints = window.showRedeemPoints || function() {
    showAlert('Redeem points feature coming soon!');
};

// Show bulk pickup modal (placeholder)
window.showBulkPickup = window.showBulkPickup || function() {
    showAlert('Schedule bulk pickup feature coming soon!');
};

// Show business rewards (placeholder)
window.showBusinessRewards = window.showBusinessRewards || function() {
    showAlert('View rewards feature coming soon!');
};

// Accept collection request
window.acceptRequest = window.acceptRequest || function(requestId) {
    console.log(`Accepting request ID: ${requestId}`);
    showAlert(`Request ${requestId} accepted!`);
    // Simulate moving to accepted collections
    const acceptedList = document.getElementById('acceptedCollections');
    if (acceptedList) {
        const item = document.createElement('div');
        item.className = 'collection-item';
        item.innerHTML = `
            <div class="collection-info">
                <span class="customer">Request ${requestId}</span>
                <span class="location">Unknown Location</span>
                <span class="scheduled-time">Today, Unknown Time</span>
            </div>
            <button class="btn btn-small btn-success" onclick="completeCollection(${requestId})">Complete Collection</button>
        `;
        acceptedList.appendChild(item);
    }
};

// Show complete collection modal
window.completeCollection = window.completeCollection || function(collectionId) {
    console.log(`Completing collection ID: ${collectionId}`);
    const modal = document.getElementById('completeModal');
    if (modal) {
        modal.classList.add('show');
    }
};

// Close complete collection modal
window.closeCompleteCollection = window.closeCompleteCollection || function() {
    const modal = document.getElementById('completeModal');
    if (modal) {
        modal.classList.remove('show');
    }
};

// Handle complete collection submission
window.submitCompleteCollection = window.submitCompleteCollection || function(event) {
    event.preventDefault();
    const form = event.target;
    const weight = form.actualWeight.value;
    console.log('Collection Completed:', { weight });
    showAlert('Collection completed successfully!');
    closeCompleteCollection();
};
