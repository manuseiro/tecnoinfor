jQuery(document).ready(function($) {
    $('#upload-logo').click(function(e) {
        e.preventDefault();
        var frame = wp.media({
            title: 'Selecione ou envie o logo da empresa',
            button: { text: 'Usar este logo' },
            multiple: false
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#logo-preview').html('<img src="' + attachment.url + '" alt="Logo da Empresa" style="max-width: 200px; height: auto;">');
            $('#empresa_logo').val(attachment.id);
        });
        frame.open();
    });

    $('#remover-logo').click(function(e) {
        e.preventDefault();
        $('#empresa_logo').val('');
        $('#logo-preview').html('');
    });
});