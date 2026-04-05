// Online kullanıcı takibi
(function() {
    function sendHeartbeat() {
        const currentPage = window.location.pathname;
        
        fetch('/track_online.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'page=' + encodeURIComponent(currentPage)
        }).catch(err => {
            // Sessizce hata yönetimi
            console.debug('Tracking error:', err);
        });
    }
    
    // İlk yüklemede gönder
    sendHeartbeat();
    
    // Her 600ms'de bir gönder
    setInterval(sendHeartbeat, 600);
    
    // Sayfa değiştiğinde gönder (SPA için)
    let lastPage = window.location.pathname;
    setInterval(() => {
        if (window.location.pathname !== lastPage) {
            lastPage = window.location.pathname;
            sendHeartbeat();
        }
    }, 100);
})();
