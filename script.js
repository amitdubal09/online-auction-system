// script.js
document.addEventListener('DOMContentLoaded', () => {
    // For auction page: hook bid button
    const bidBtn = document.getElementById('bid-btn');
    if (bidBtn) {
        bidBtn.addEventListener('click', async () => {
            const amountEl = document.getElementById('bid-amount');
            const amount = parseFloat(amountEl.value);
            const msg = document.getElementById('bid-msg');
            if (!amount || amount <= 0) { msg.textContent = 'Enter valid amount'; return; }
            const form = new FormData();
            form.append('auction_id', auctionId);
            form.append('amount', amount);
            const res = await fetch('place_bid.php', { method: 'POST', body: form });
            const json = await res.json();
            msg.textContent = json.msg;
            if (json.ok) {
                document.getElementById('current-price').textContent = parseFloat(json.new_price).toFixed(2);
                // append latest bid
                const bidsList = document.getElementById('bids-list');
                const li = document.createElement('li');
                li.textContent = `You — ₹${parseFloat(json.new_price).toFixed(2)} (just now)`;
                bidsList.prepend(li);
            }
        });
    }

    // simple polling to refresh current price every 5s when on auction page
    if (typeof auctionId !== 'undefined') {
        setInterval(async () => {
            const res = await fetch('auction_status.php?id=' + auctionId);
            if (res.ok) {
                const j = await res.json();
                document.getElementById('current-price').textContent = parseFloat(j.current_price).toFixed(2);
            }
        }, 5000);
    }
});
