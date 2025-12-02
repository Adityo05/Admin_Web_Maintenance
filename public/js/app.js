// Sidebar Toggle
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    
    if (window.innerWidth <= 768) {
        // Mobile: toggle open class (with overlay)
        sidebar.classList.toggle("open");
    } else {
        // Desktop: toggle collapsed class (no overlay)
        sidebar.classList.toggle("collapsed");
    }
}

// Close sidebar when clicking overlay (mobile only)
document.addEventListener("DOMContentLoaded", function() {
    const overlay = document.querySelector(".sidebar-overlay");
    if (overlay) {
        overlay.addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            if (window.innerWidth <= 768) {
                sidebar.classList.remove("open");
            }
        });
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = "opacity 0.5s";
            alert.style.opacity = "0";
            setTimeout(function () {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Confirm delete
function confirmDelete(message) {
    return confirm(message || "Apakah Anda yakin ingin menghapus data ini?");
}
