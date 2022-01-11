data-copy
data-clipboard-text="{{ $copyText }}"
data-bs-toggle="tooltip"
title="Copied!"
data-bs-trigger="manual"

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script>
        var clipboard = new ClipboardJS('[data-copy]');
        var tooltip = new bootstrap.Tooltip('[data-copy]');
        clipboard.on('success', function(e) {
            tooltip.show();
        });

        $('[data-copy]').on('mouseleave', function() {
            tooltip.hide();
        });
    </script>
@endpush
