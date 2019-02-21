$('.js-sweetalert button[data-type="ajax-loader"]').on('click', showAjaxLoaderMessage);

function showAjaxLoaderMessage() {
    var news = $(this).data('p');
    console.log(news.id);
    swal({
        title: "Do you want to delete product: " + news.title,
        text: "Submit to delete this product",
        type: "info",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
    }, function () {
        // setTimeout(function () {
        //     swal({title: "Delete successfully!"}, function () {
        //         location.reload();
        //     });
        // }, 1000);
        var url = "/api/news/delete/" + news.id;
        console.log(url);
        $.ajax({
            url: url,
            type: 'DELETE',
            success: function (res) {
                console.log(res);
                setTimeout(function () {
                    swal({title: "Delete successfully!"}, function () {
                        location.reload();
                    });
                }, 1000);
            },
            error: function (res) {
                console.log(res);
                setTimeout(function () {
                    swal("Fail to delete. Error: " + res.message);
                }, 1000);
            }
        })
    });
}