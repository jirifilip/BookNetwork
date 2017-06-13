$(function() {

    let topHoverChapterMenu = 120;
    let bottomHoverChapterMenu = 90;

    const modalsOpen = () => $('.modal.in').length === 0;

    $(document).on("mousemove", function(e) {
        let { clientX, clientY } = e.originalEvent;
        let $element = $(".hover-menu-chapter"); 

        if (
            (clientY < topHoverChapterMenu
            || clientY > window.innerHeight - bottomHoverChapterMenu)
	    && document.documentElement.scrollTop > 20
        ) {
            $element.fadeIn(400);
        } else {
            $element.fadeOut(400);
        }
        
    })

    $(document).on("input", ".img-size-input", function() {
        let value = $(this).val();

        $(".thumbnail-book-img").css("width", "200%");
    })


    function bindChapterProgress(element) {
        $el = $(element);

        $(document).on("scroll", function(e) {
            let progress = calcChapterProgress(e);
            $el.text(progress);
        })
    }

    function calcChapterProgress(e) {
        let height = document.body.clientHeight;
        let { pageY : now } = e.originalEvent;
        now += window.innerHeight;

        let progress = Math.floor(now / height * 100);
        progress = progress < 100? progress : 100;

        return progress;
    }

    bindChapterProgress(".chapter-progress");

})