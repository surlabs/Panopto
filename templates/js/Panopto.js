let Panopto = {

    base_url: '',

    lti_launched: false,

    txt_open_new_tab: 'Open Panopto in New Tab',

    embed_id: 'xpan_player',

    listening: false,

    // Safari and every browser on iOS block third-party cookies, so the hidden LTI launch cannot sign the user in
    blocksThirdPartyCookies: function () {
        let ua = navigator.userAgent,
            is_ios = /iPad|iPhone|iPod/.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1),
            is_safari = /^((?!chrome|chromium|crios|fxios|edg|android).)*safari/i.test(ua);

        return is_ios || is_safari;
    },

    // Once Panopto has been opened as first party, its embedded player may ask the browser for storage access
    hasFirstPartySession: function () {
        try {
            return Date.now() - parseInt(localStorage.getItem('xpan_first_party_session') || '0', 10) < 8 * 3600 * 1000;
        } catch (e) {
            return false;
        }
    },

    launchInNewTab: function (url) {
        let tab = window.open('', 'xpan_panopto');

        try {
            localStorage.setItem('xpan_first_party_session', String(Date.now()));
        } catch (e) {
        }

        if (!Panopto.lti_launched) {
            Panopto.lti_launched = true;
            $('#lti_form').attr('target', tab ? 'xpan_panopto' : '_blank').submit();

            if (tab && url) {
                setTimeout(function () {
                    tab.location.href = url;
                }, 3000);
            }
        } else if (tab && url) {
            tab.location.href = url;
        }
    },

    // The Panopto player reports through postMessage when it shows its sign-in prompt instead of the video
    listenForLoginPrompt: function () {
        if (Panopto.listening) {
            return;
        }

        Panopto.listening = true;
        window.addEventListener('message', function (event) {
            let message;

            if (event.origin.toLowerCase() !== Panopto.base_url.toLowerCase() || typeof event.data !== 'string') {
                return;
            }

            try {
                message = JSON.parse(event.data);
            } catch (e) {
                return;
            }

            if (message && message.source === 'PanoptoEmbed' && message.msg === 'iframeLoginShown'
                && (message.id === '*' || message.id === Panopto.embed_id)) {
                Panopto.onLoginShown();
            }
        });
    },

    // The browser has no Panopto session for the player, so the next video starts again with the launch in a new tab
    onLoginShown: function () {
        try {
            localStorage.removeItem('xpan_first_party_session');
        } catch (e) {
        }

        $('#xpan_open_new_tab').show();
    },

    playVideo: function (sid, is_playlist, title) {
        let viewer_url = Panopto.base_url + '/Panopto/Pages/Viewer.aspx?' + (is_playlist ? 'p' : '') + 'id=' + sid,
            embed_params = '',
            new_tab_button = '';

        if (Panopto.blocksThirdPartyCookies() && !Panopto.hasFirstPartySession()) {
            Panopto.launchInNewTab(viewer_url);
            return;
        }

        if (Panopto.blocksThirdPartyCookies()) {
            Panopto.listenForLoginPrompt();
            embed_params = '&remoteEmbed=true&remoteHost=' + encodeURIComponent(location.protocol + '//' + location.host) + '&embedApiId=' + Panopto.embed_id;
            new_tab_button = '<button type="button" id="xpan_open_new_tab" class="btn btn-default" style="display: none; margin-top: 10px;"></button>';
        }

        let $modal = $('#xpan_video_container').parent().parent().parent().parent().parent();
        $(".modal-dialog").addClass("modal-lg").css("width", "100%").css("max-width", "800px");
        $modal.modal('show');
        let $iframe = '<iframe src="' + Panopto.base_url + '/Panopto/Pages/Embed.aspx?' + (is_playlist ? 'p' : '') + 'id=' + sid + embed_params + '" style="padding: 0px; border: 1px solid #464646; width: 100%; aspect-ratio: 16/9;" frameborder="0" allowfullscreen allow="autoplay"></iframe>' + new_tab_button;
        $modal.find('div#xpan_video_container').html($iframe);
        $modal.find('.modal-title').html(title);
        $('#xpan_open_new_tab').text(Panopto.txt_open_new_tab).on('click', function () {
            Panopto.launchInNewTab(viewer_url);
        });
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
