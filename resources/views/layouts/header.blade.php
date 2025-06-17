<header class="bg-white shadow-sm border-b border-gray-200 px-6 py-3 fixed top-0 left-0 right-0 z-10">
    <div class="flex items-center justify-end">
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <p class="text-sm text-gray-600" id="current-time"></p>
                <p class="text-sm text-gray-600" id="current-date"></p>
            </div>

        </div>
    </div>
</header>
<!-- Add padding to the body to prevent content from being hidden under the fixed header -->
<style>
    body {
        padding-top: 80px; /* Adjust based on header height */
    }
</style>
<!-- JavaScript to update time and date in real-time -->
<script>
    function updateTimeDate() {
        const now = new Date().toLocaleString('en-US', { timeZone: 'Asia/Jakarta' });
        const date = new Date(now);

        // Format time (HH:mm:ss)
        const time = date.toLocaleTimeString('id-ID', { hour12: false });

        // Format date (Hari, DD MMMM YYYY)
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dayName = days[date.getDay()];
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        const formattedDate = `${dayName}, ${day} ${month} ${year}`;

        // Update DOM
        document.getElementById('current-time').textContent = time;
        document.getElementById('current-date').textContent = formattedDate;
    }

    // Update immediately and every second
    updateTimeDate();
    setInterval(updateTimeDate, 1000);
</script>
