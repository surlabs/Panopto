let Panopto = {

    base_url: '',

    playVideo: function (sid, is_playlist, title) {
        let $modal = $('#xpan_video_container').closest('dialog');
        $(".modal-dialog").addClass("modal-lg").css("width", "100%").css("max-width", "800px");

        let modalElement = $modal.get(0); // obtener el DOM element

        // ⚠️ Solución: pasar los tres argumentos correctamente
        il.UI.modal.showModal(
          modalElement,
          {}, // sin ajaxRenderUrl
          { id: "panopto-video-modal" } // cualquier id único
        );
        let $iframe = '<iframe src="' + Panopto.base_url + '/Panopto/Pages/Embed.aspx?' + (is_playlist ? 'p' : '') + 'id=' + sid + '" width="720" height="405" style="padding: 0px; border: 1px solid #464646;" frameborder="0" allowfullscreen allow="autoplay"></iframe>';
        $modal.find('div#xpan_video_container').html($iframe);
        $modal.find('.modal-title').html(title);
        $('#xoct_waiter_modal').show();

        $modal.on('hidden', function() { // bootstrap 2.3.2
            let video = $('video')[0];
            if(typeof video != 'undefined') {
                video.pause();
            }
            $iframe = $('iframe');
            if (typeof $iframe != 'undefined') {
                $iframe.attr('src', '');
            }
        });

        $modal.on('hidden.bs.modal', function() {  // bootstrap 3
            let video = $('video')[0];
            if(typeof video != 'undefined') {
                video.pause();
            }
            $iframe = $('iframe');
            if (typeof $iframe != 'undefined') {
                $iframe.attr('src', '');
            }
        });

        // ILIAS 10 uses the native <dialog> element, which fires a plain 'close'
        // event (not the bootstrap 'hidden'/'hidden.bs.modal' events above) when
        // closed via the "x" button, ESC key or dialog.close(). Without this the
        // Panopto iframe is never emptied and the video/audio keeps playing.
        $modal.on('close', function() {
            $iframe = $modal.find('iframe');
            if ($iframe.length) {
                $iframe.attr('src', '');
            }
        });
    }

};
