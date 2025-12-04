import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

// Toast notification preset
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

// Confirm delete function
window.confirmDelete = function(formId, title = 'Hapus Data?', text = 'Data yang dihapus tidak dapat dikembalikan!') {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Confirm action function (generic)
window.confirmAction = function(formId, options = {}) {
    const defaults = {
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin melanjutkan?',
        icon: 'question',
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    };
    
    const settings = { ...defaults, ...options };
    
    Swal.fire({
        title: settings.title,
        text: settings.text,
        icon: settings.icon,
        showCancelButton: true,
        confirmButtonColor: settings.confirmButtonColor,
        cancelButtonColor: settings.cancelButtonColor,
        confirmButtonText: settings.confirmButtonText,
        cancelButtonText: settings.cancelButtonText,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Confirm logout function
window.confirmLogout = function(formId) {
    Swal.fire({
        title: 'Logout?',
        text: 'Apakah Anda yakin ingin keluar dari sistem?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

Alpine.start();
