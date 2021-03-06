$(function() {
    $('#nav-accordion').dcAccordion({
        eventType: 'click',
        autoClose: true,
        saveState: true,
        disableLink: true,
        speed: 'slow',
        showCount: false,
        autoExpand: true,
        classExpand: 'dcjq-current-parent'
    });

    $('#lista-usuarios').DataTable(
        {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
            }
        }
    );
    $('#lista-tecnologias').DataTable(
        {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
            }
        }
    );
    $('#lista-empresas').DataTable(
        {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
            }
        }
    );
    $('#lista-vagas').DataTable(
        {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
            }
        }
    );
    $('#lista-perfis').DataTable(
        {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
            }
        }
    );
});

var Script = function () {
    $(function() {
        function responsiveView() {
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('#container').addClass('sidebar-close');
                $('#sidebar > ul').hide();
            }

            if (wSize > 768) {
                $('#container').removeClass('sidebar-close');
                $('#sidebar > ul').show();
            }
        }
        $(window).on('load', responsiveView);
        $(window).on('resize', responsiveView);
    });

    $('.fa-bars').click(function () {
        if ($('#sidebar > ul').is(":visible") === true) {
            $('#main-content').css({
                'margin-left': '0px'
            });
            $('#sidebar').css({
                'margin-left': '-210px'
            });
            $('#sidebar > ul').hide();
            $("#container").addClass("sidebar-closed");
        } else {
            $('#main-content').css({
                'margin-left': '210px'
            });
            $('#sidebar > ul').show();
            $('#sidebar').css({
                'margin-left': '0'
            });
            $("#container").removeClass("sidebar-closed");
        }
    });

    jQuery('.panel .tools .fa-chevron-down').click(function () {
        var el = jQuery(this).parents(".panel").children(".panel-body");
        if (jQuery(this).hasClass("fa-chevron-down")) {
            jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            el.slideUp(200);
        } else {
            jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            el.slideDown(200);
        }
    });

    jQuery('.panel .tools .fa-times').click(function () {
        jQuery(this).parents(".panel").parent().remove();
    });
}();