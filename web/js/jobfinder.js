/**
 * Created by inocer on 10/10/17.
 */

jQuery(document).ready(function($) {
    $(".vacancy-row").click(function() {
        window.open($(this).data("href"));
    });
});