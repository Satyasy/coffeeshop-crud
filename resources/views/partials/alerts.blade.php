<style>
    /* Style untuk alert toast */
    .alert-toast {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 9999;
        width: 350px;
        max-width: calc(100% - 4rem);
        animation: fadeInOut 5s ease-in-out forwards;
        opacity: 0;
    }

    .alert-toast-content {
        display: flex;
        align-items: flex-start;
        padding: 1rem;
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Animasi fade in dan out */
    @keyframes fadeInOut {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }

        10% {
            opacity: 1;
            transform: translateY(0);
        }

        90% {
            opacity: 1;
            transform: translateY(0);
        }

        100% {
            opacity: 0;
            transform: translateY(-20px);
        }
    }

    /* Warna berdasarkan jenis alert */
    .alert-toast[data-type="success"] .alert-toast-content {
        background-color: #f0fdf4;
        border-left: 4px solid #10b981;
        color: #065f46;
    }

    .alert-toast[data-type="error"] .alert-toast-content {
        background-color: #fef2f2;
        border-left: 4px solid #ef4444;
        color: #991b1b;
    }

    .alert-toast[data-type="warning"] .alert-toast-content {
        background-color: #fffbeb;
        border-left: 4px solid #f59e0b;
        color: #92400e;
    }

    .alert-toast[data-type="info"] .alert-toast-content {
        background-color: #eff6ff;
        border-left: 4px solid #3b82f6;
        color: #1e40af;
    }

    /* Style untuk icon */
    .alert-toast-icon {
        margin-right: 0.75rem;
        display: flex;
        align-items: flex-start;
        padding-top: 0.125rem;
    }

    .alert-toast-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    /* Style untuk pesan */
    .alert-toast-message {
        flex: 1;
    }

    .alert-toast-message strong {
        display: block;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .alert-toast-message p {
        margin: 0;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
</style>

{{-- File ini akan menampung semua jenis alert dari session --}}
@if ($message = Session::get('success'))
    <div class="alert-toast" data-type="success">
        <div class="alert-toast-content">
            <div class="alert-toast-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="alert-toast-message">
                <strong>Success!</strong>
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert-toast" data-type="error">
        <div class="alert-toast-content">
            <div class="alert-toast-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>
            <div class="alert-toast-message">
                <strong>Error!</strong>
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert-toast" data-type="warning">
        <div class="alert-toast-content">
            <div class="alert-toast-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                    </path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="alert-toast-message">
                <strong>Warning!</strong>
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert-toast" data-type="info">
        <div class="alert-toast-content">
            <div class="alert-toast-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
            </div>
            <div class="alert-toast-message">
                <strong>Info</strong>
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>
@endif
