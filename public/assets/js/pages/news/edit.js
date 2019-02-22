const UPLOAD_IMG_URL = "https://api.cloudinary.com/v1_1/dqbat91l8/upload";
$(function () {
    Dropzone.options.frmFileUpload = {
        url: UPLOAD_IMG_URL,
        paramName: "file",
        params: {
            upload_preset: 'b3uy9rh5'
        },
        maxFiles: 1,
        addRemoveLinks: true,
        error: function (file, err) {
            console.log(err);
        },
        success: function (file, res) {
            console.log(res);
            $('#url-image').val(res.url);
        },

        init: function () {
            /**
             * r dùng để phân biệt khi xóa images bằng tay và clear dropzone bằng button reset.
             *
             * Nếu xóa bằng tay(r=0), mỗi lần xóa sẽ gọi hàm validateImages trong callback của event removedfile, nếu xóa hết thì
             * sẽ thông báo 'Please choice images' dưới dropzone.
             *
             * Nếu clear dropzone bằng button reset , add event click đến button reset , gán r = 1, gọi hàm removeAllFiles()
             * khi đó event removedfile sẽ đc trigger nhưng r =1 (ko gọi validateImages).
             * @type {number}
             */
            // var r = 0;
            // var _this = this;
            var input = $('#url-image');
            var mockFile = {name: input.attr('name'), size: 1, type: 'image/jpeg'};
            this.emit("addedfile", mockFile);
            this.emit("thumbnail", mockFile, input.val());
            this.emit("complete", mockFile);
            this.files.push(mockFile);
            console.log(this.files.length);
            // if (this.files.length === 0) {
            //     this.on("success", function (file, res) {
            //         console.log(res);
            //         // console.log(this.files.length);
            //         // $('#url-image').val(file.url);
            //         // console.log($('#url-image').val());
            //         // validateImages(document.getElementById("frm-file-upload"), getValueImages());
            //     });
            // }
            this.on("removedfile", function (file) {
                console.log(file.name);
                $('#url-image').removeAttr('value');
                console.log();
                // $("#url-images > input[name='"+ file.name +"']").remove();
                // if(r === 0) validateImages(document.getElementById("frm-file-upload"), getValueImages());
            });
            this.on("maxfilesexceeded", function(file){
                alert("No more files please!");
                this.removeFile(file);
            });

            // input.val(this.files[0].url);
            // console.log(input.val());
        }
    };
});