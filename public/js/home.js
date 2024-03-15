$(function () {
  $('select[name="states"]').on("change", function () {
    $.ajax({
      url: "/",
      method: "post",
      dataType: "json",
      data: {
        order_id: $(this).parent().find("option:selected").data("order_id"),
        state_id: $(this).val(),
      },
      success: function (data) {
        $(".success-status").show();

        setTimeout(function () {
          $(".success-status").hide();
        }, 2000);
      },
    });
  });
});
