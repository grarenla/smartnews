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
            $("#url-images > input[name='urlImg']").remove();
            var ip = document.createElement("input");
            ip.setAttribute("type", "hidden");
            ip.setAttribute("class", "urlImg");
            ip.setAttribute("name", file.name);
            ip.value = res.url;
            var urlImages = document.getElementById("url-images");
            urlImages.appendChild(ip);
            // $('#url-image').val(res.url);
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
            // var input = $('#url-image');
            // var mockFile = {name: input.attr('name'), size: 1, type: 'image/jpeg'};
            // this.emit("addedfile", mockFile);
            // this.emit("thumbnail", mockFile, input.val());
            // this.emit("complete", mockFile);
            // this.files.push(mockFile);
            // console.log(this.files.length);

            var _this = this;

            var inputs = document.getElementById("url-images").querySelectorAll(".edit-img");
            for(var i=0; i< inputs.length; i++){
                var mockFile = { name: 'urlImg', size: 1, type: 'image/jpeg' };
                _this.emit("addedfile", mockFile);
                _this.emit("thumbnail", mockFile, inputs[i].value);
                _this.emit("complete", mockFile);
                _this.files.push( mockFile );
            }
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
                $("#url-images > input[name='"+ file.name +"']").remove();
            });
            this.on("maxfilesexceeded", function(file){
                alert("You can only choose 1 photo!");
                this.removeFile(file);
            });


        }
    };
});