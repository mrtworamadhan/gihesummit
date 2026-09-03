<x-filament-panels::page>
    
    <!-- Panggil Infolist yang sudah diatur di PHP -->
    {{ $this->infolist }}

    <!-- Script CSS khusus untuk mode pencetakan (Ctrl+P) -->
    <style>
        @media print {
            /* Sembunyikan semua elemen bawaan Filament */
            body * { 
                visibility: hidden; 
            }
            
            /* Tampilkan hanya area Infolist yang kita beri class 'print-area' */
            .print-area, .print-area * { 
                visibility: visible; 
            }
            
            /* Posisikan Infolist di tengah kertas putih */
            .print-area { 
                position: absolute; 
                left: 0; 
                top: 0; 
                width: 100%; 
                border: none; 
                box-shadow: none; 
            }
            
            /* Hilangkan Navbar atas dan Sidebar secara paksa */
            .fi-topbar, .fi-sidebar, .fi-header { 
                display: none !important; 
            }
        }
    </style>
</x-filament-panels::page>