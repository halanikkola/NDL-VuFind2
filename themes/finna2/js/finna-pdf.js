/*global VuFind, finna */
finna.pdf = (function finnaPdf() {

  function initPdfJS() {
    console.log("init");

    const pdfDataElement = $("#pdf-data");
    const pdfUrl = pdfDataElement.data("pdfurl");
    const recordId = pdfDataElement.data("recordid");

    if (typeof pdfUrl === "undefined") {
      return;
    }
    /*
    var options = {
      page: 1,
      pdfOpenParams: {
        navpanes: false,
      }
    };
    */
    /*
    const btn = $("<button class=\"pdf-btn\">Open PDF (native)</button>").click(function nativebutton() {
      $(".recordcover-container").css({"width": "100%", "height": "600px"});
      PDFObject.embed(pdfUrl, el);
      $(".pdf-btn").remove();
    });
    */
    const el = $(".image-popup-trigger").parent();


    const proxyUrl = VuFind.path + "/Cover/download?size=master&format=pdf&&id=" + recordId;
    const iframe = $("<iframe/>")
      .attr("id", "pdf-js-viewer")
      .attr("src", VuFind.path + "/themes/finna2/js/pdfjs/web/viewer.html?" + proxyUrl)
      .attr("frameborder", "0")
      .attr("width", "100%")
      .attr("height", "600");

    const btn2 = $("<button class=\"pdf-btn\">Open PDF (pdfjs)</button>").click(function pdfjsbutton() {
      el.replaceWith(iframe);
      $(".pdf-btn").remove();
    });
    $(".image-popup-trigger").parent().after(btn2);
  }

  var my = {
    initPdfJS: initPdfJS,
    init: function init() {
      initPdfJS();
    }
  };

  return my;
})();