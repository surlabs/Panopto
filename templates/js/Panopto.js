var Panopto = {

    base_url: '',

    playVideo: function (sid, is_playlist, title) {
        console.log('playVideo ' + sid);
        console.log('is playlist ' + is_playlist);
        var $modal = $('.modal');
        $(".modal-dialog").addClass("modal-lg").css("width", "100%").css("max-width", "800px");
        $modal.modal('show');
        // $("body").addClass("modal-open").append(`<div class="modal-backdrop fade" bis_skin_checked="1"></div>`);
        // setTimeout(()=>{
        //     $(".modal-backdrop").hide().css('opacity', '0.9').fadeIn(300, function(){
        //         $modal.addClass("in");
        //     })
        //
        // }, 100)
        var $iframe = '<iframe src="' + Panopto.base_url + '/Panopto/Pages/Embed.aspx?' + (is_playlist ? 'p' : '') + 'id=' + sid + '" width="720" height="405" style="padding: 0px; border: 1px solid #464646;" frameborder="0" allowfullscreen allow="autoplay"></iframe>';
        $modal.find('div#xpan_video_container').html($iframe);
        $modal.find('.modal-title').html(title);
        $('#xoct_waiter_modal').show();

        $modal.on('hidden', function() { // bootstrap 2.3.2
            $video = $('video')[0];
            if(typeof $video != 'undefined') {
                $video.pause();
            }
            $iframe = $('iframe');
            if (typeof $iframe != 'undefined') {
                $iframe.attr('src', '');
            }
        });

        $modal.on('hidden.bs.modal', function() {  // bootstrap 3
            $video = $('video')[0];
            if(typeof $video != 'undefined') {
                $video.pause();
            }
            $iframe = $('iframe');
            if (typeof $iframe != 'undefined') {
                $iframe.attr('src', '');
            }
        });
        // $(".modal, .modal-footer .btn-default").on("click", function(e) {
        //     e.stopPropagation();
        //     $modal.removeClass("in").fadeOut(150, function(){
        //         $(".modal-backdrop").removeClass("in").remove();
        //         $modal.hide();
        //         $video = $('video')[0];
        //         if(typeof $video != 'undefined') {
        //             $video.pause();
        //         }
        //         $iframe = $('iframe');
        //         if (typeof $iframe != 'undefined') {
        //             $iframe.attr('src', '');
        //         }
        //     });
        //
        //
        //
        // });
    }

};
