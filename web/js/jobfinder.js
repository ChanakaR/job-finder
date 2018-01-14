/**
 * Created by inocer on 10/10/17.
 */
// $(".btn-vacancy-more").click(function() {
//     console.info("Clicked");
//     $('#vacancy_details_modal').modal('show')
// });

$('#vacancy_details_modal').on('show.bs.modal', function (e) {
    alert("here we go");
    $('.modal .modal-body').css('overflow-y', 'auto');
    $('.modal .modal-body').css('height', $(window).height() * 0.7);
    $("#vacancy-pdf-viewer").attr("width",$(window).height()*1.12);
    $("#vacancy-pdf-viewer").attr("height",$(window).height()*0.62);
})
