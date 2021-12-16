/*global VuFind, finna */
finna.pdf = (function finnaPdf() {

  function initPdfJS() {
    
    const pdfDataElement = $("#pdf-data");
    const pdfUrl = pdfDataElement.data("pdfurl");
    const recordId = pdfDataElement.data("recordid");

    if (typeof pdfUrl === "undefined") {
      return;
    }

    const el = $(".image-popup-trigger").parent();

    const proxyUrl = VuFind.path + "/Cover/download?size=master&format=pdf&&id=" + recordId;
    const iframe = $("<iframe/>")
      .attr("id", "pdf-js-viewer")
      .attr("src", VuFind.path + "/themes/finna2/js/vendor/pdfjs/web/viewer.html?" + proxyUrl)
      .attr("frameborder", "0")
      .attr("width", "100%")
      .attr("height", "600");

    const desktopBtn = $("<button class=\"btn btn-primary pdf-btn desktop-pdf-btn\">" + VuFind.translate('open_online_link', {'%%format%%': 'PDF'}) + "</button>").click(function pdfjsbutton() {
      el.replaceWith(iframe);
      $(".desktop-pdf-btn").remove();
    });
    $(".image-popup-trigger").parent().after(desktopBtn);

    
    const mobileBtn = $("<button class=\"btn btn-primary pdf-btn mobile-pdf-btn\">" + VuFind.translate('open_online_link', {'%%format%%': 'PDF'}) + "</button>").click(function pdfjsbutton() {
      var parent;
      var translations = {
        close: VuFind.translate('close')
      };
      $('body').find('[data-embed-pdf]').each(function initVideo() {
        $(this).finnaPopup({
          id: 'recordpdf',
          modal: iframe,
          classes: 'pdf-popup',
          parent: parent,
          embed: iframe,
          translations: translations,
          onPopupInit: function onPopupInit(t) {
            if (this.parent) {
              t.removeClass('active-pdf');
            }
          }
        });
      });
      $(".mobile-pdf-btn").remove();
    });
    $(".image-popup-trigger").parent().after(mobileBtn);
  }

  var my = {
    initPdfJS: initPdfJS,
    init: function init() {
      initPdfJS();
    }
  };

  return my;
})();