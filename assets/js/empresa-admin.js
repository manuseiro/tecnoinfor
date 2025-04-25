jQuery(document).ready(function($) {
    // Upload de logo
    $('#upload-logo').on('click', function(e) {
        e.preventDefault();
        var frame;
        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media({
            title: 'Selecionar Logo da Empresa',
            button: { text: 'Usar esta imagem' },
            multiple: false,
            library: { type: 'image' }
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#empresa_logo').val(attachment.id);
            $('#logo-preview').html('<img src="' + attachment.url + '" alt="Logo da Empresa" style="max-width: 200px; height: auto;">');
        });
        frame.open();
    });

    $('#remover-logo').on('click', function(e) {
        e.preventDefault();
        $('#empresa_logo').val('');
        $('#logo-preview').html('');
    });

    // Máscara para telefone e WhatsApp
    $('#empresa_telefone, #empresa_whatsapp').mask('(00) 00000-0000');

    // Validação em tempo real
    function validarTelefone(valor) {
        var apenasNumeros = valor.replace(/\D/g, '');
        return apenasNumeros.length >= 10 && apenasNumeros.length <= 11;
    }

    function exibirMensagemValidacao(campo, mensagem, isErro) {
        var $campo = $('#' + campo);
        var $mensagem = $campo.next('.validacao-mensagem');
        if (!$mensagem.length) {
            $campo.after('<span class="validacao-mensagem"></span>');
            $mensagem = $campo.next('.validacao-mensagem');
        }
        $mensagem.text(mensagem).toggleClass('erro', isErro).toggleClass('sucesso', !isErro && mensagem !== '');
    }

    $('#empresa_telefone, #empresa_whatsapp').on('input', function() {
        var valor = $(this).val();
        var id = $(this).attr('id');
        var label = id === 'empresa_telefone' ? 'Telefone' : 'WhatsApp';

        if (!valor) {
            exibirMensagemValidacao(id, '', false);
        } else if (validarTelefone(valor)) {
            exibirMensagemValidacao(id, `${label} válido!`, false);
        } else {
            exibirMensagemValidacao(id, `${label} inválido. Use DDD + número (10 ou 11 dígitos).`, true);
        }
    });
});