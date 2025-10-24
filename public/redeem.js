// Simple redeem page script (demo-only)
(function(){
    const STORAGE_KEY = 'ecocollect_user_points';

    // default points if not present
    function getPoints(){
        const v = localStorage.getItem(STORAGE_KEY);
        return v ? parseInt(v,10) : 1247;
    }
    function setPoints(n){ localStorage.setItem(STORAGE_KEY, String(n)); }

    // sample reward options
    const rewards = [
        { id: 'cash50', title: 'Cash Transfer - Rs.50', cost: 100, desc: 'Instant bank/mobile transfer equivalent to Rs.50.' },
        { id: 'mobile25', title: 'Mobile Credit - Rs.25', cost: 50, desc: 'Top up your mobile balance.' },
        { id: 'ecoPack', title: 'Eco Product Pack', cost: 300, desc: 'Redeem for eco-friendly products.' },
        { id: 'tree', title: 'Plant a Tree', cost: 200, desc: 'Donate points to plant a tree on your behalf.' }
    ];

    function formatPoints(n){ return n.toLocaleString(); }

    function renderOptions(){
        const container = document.getElementById('redeemOptions');
        container.innerHTML = rewards.map(r => `
            <div class="reward-card" data-id="${r.id}">
                <div>
                    <strong>${r.title}</strong>
                    <div class="reward-meta">${r.desc}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-weight:800;color:var(--primary-color)">${r.cost} pts</div>
                    <button class="btn btn-small btn-secondary select-btn" data-id="${r.id}">Select</button>
                </div>
            </div>
        `).join('');

        container.querySelectorAll('.select-btn').forEach(btn => {
            btn.addEventListener('click', () => selectOption(btn.getAttribute('data-id')));
        });
    }

    let selected = null;
    function selectOption(id){
        selected = rewards.find(r=>r.id===id);
        document.querySelectorAll('.reward-card').forEach(c=> c.classList.toggle('selected', c.getAttribute('data-id')===id));
        const preview = document.getElementById('previewArea');
        preview.innerHTML = `
            <h4>${selected.title}</h4>
            <p class="muted">${selected.desc}</p>
            <p style="margin-top:8px"><strong>Cost:</strong> ${selected.cost} points</p>
        `;
        document.getElementById('redeemMessage').textContent = '';
    }

    function updateBalanceDisplays(){
        const points = getPoints();
        const el = document.getElementById('redeemCurrentPoints');
        const dash = document.getElementById('userPoints');
        // animate current element from previous value
        const prev = parseInt((el && el.textContent) ? el.textContent.replace(/,/g,'') : getPoints(), 10) || 0;
        if (el && typeof window.countUp === 'function') {
            window.countUp(el, prev, points, 900);
        } else if (el) {
            el.textContent = formatPoints(points);
        }
        if (dash) {
            const prevDash = parseInt((dash && dash.textContent) ? dash.textContent.replace(/,/g,'') : getPoints(), 10) || 0;
            if (typeof window.countUp === 'function') window.countUp(dash, prevDash, points, 900);
            else dash.textContent = formatPoints(points);
        }
    }

    function showConfirm(){
        if(!selected){ document.getElementById('redeemMessage').textContent = 'Please select a reward option first.'; return; }
        const points = getPoints();
        if(points < selected.cost){ document.getElementById('redeemMessage').textContent = 'Not enough points to redeem this option.'; return; }
        const body = document.getElementById('confirmBody');
        body.innerHTML = `<p>Redeem <strong>${selected.cost}</strong> points for <strong>${selected.title}</strong>?</p>`;
        document.getElementById('confirmModal').classList.add('show');
    }

    function confirmRedeem(){
        const points = getPoints();
        if(!selected) return;
        if(points < selected.cost) { alert('Not enough points'); return; }
        const newPoints = points - selected.cost;
        setPoints(newPoints);
        updateBalanceDisplays();
        document.getElementById('confirmModal').classList.remove('show');
        alert('Redemption successful! You will receive further instructions via email in this demo.');
    }

    function donatePoints(){
        const points = getPoints();
        const donate = Math.min(200, points);
        if(donate<=0) { alert('No points to donate'); return; }
        if(!confirm(`Donate ${donate} points to environmental causes?`)) return;
        setPoints(points - donate);
        updateBalanceDisplays();
        alert('Thank you for donating!');
    }

    document.addEventListener('DOMContentLoaded', function(){
        if(!localStorage.getItem(STORAGE_KEY)) setPoints(1247);
        renderOptions();
        updateBalanceDisplays();
        document.getElementById('redeemBtn').addEventListener('click', showConfirm);
        document.getElementById('confirmRedeem').addEventListener('click', confirmRedeem);
        document.getElementById('donateBtn').addEventListener('click', donatePoints);
    });

})();
