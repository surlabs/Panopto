let Panopto = {

    base_url: '',
    playVideo: function (sid, is_playlist, title) {
        let $container = $('#panopto-modal-video-container');
        if ($container.length === 0) return;

        let $modal = $container.closest('dialog');
        if ($modal.length === 0) return;

        let modalElement = $modal.get(0);
        // Fix: Pass all three arguments correctly
        il.UI.modal.showModal(
            modalElement,
            {}, // No ajaxRenderUrl provided
            { id: "panopto-video-modal" } // Any unique ID
        );
        let $iframe = '<iframe src="' + Panopto.base_url + '/Panopto/Pages/Embed.aspx?' + (is_playlist ? 'p' : '') + 'id=' + sid + '" width="720" height="405" style="padding: 0px; border: 1px solid #464646; width: 100%; aspect-ratio: 16/9;" frameborder="0" allowfullscreen allow="autoplay"></iframe>';
        $modal.find('div#panopto-modal-video-container').html($iframe);
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
    }

};