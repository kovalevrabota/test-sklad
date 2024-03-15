$(function () {
  $(".form-authorization").submit(function (event) {
    event.preventDefault();

    let form = $(".form-authorization");

    $.ajax({
      type: "POST",
      url: "/login",
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: function () {
        //Скрываем див с ошибкой
        form.find("#message").html("");
        form.find("#message").hide();

        //Блокируем кнопки
        form.find("#submit").prop("disabled", true);
      },
      success: function (data) {
        if (data.result) {
          location.reload();
        } else {
          form.find("#message").html(data.message);
          form.find("#message").show();

          form.find("#submit").prop("disabled", false);
        }
      },
    });
  });
});
