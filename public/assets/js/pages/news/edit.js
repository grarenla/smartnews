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
            $("#url-images > input[name='img']").val(res.url);
            // var ip = document.createElement("input");
            // ip.setAttribute("type", "hidden");
            // ip.setAttribute("class", "urlImg");
            // ip.setAttribute("name", 'img');
            // ip.value = res.url;
            // var urlImages = document.getElementById("url-images");
            // urlImages.appendChild(ip);
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
            var _this = this;

            var inputs = document.getElementById("url-images").querySelectorAll(".edit-img");
            for(var i=0; i< inputs.length; i++){
                var mockFile = { name: 'img', size: 1, type: 'image/jpeg' };
                _this.emit("addedfile", mockFile);
                _this.emit("thumbnail", mockFile, inputs[i].value);
                _this.emit("complete", mockFile);
                _this.files.push( mockFile );
            }
            this.on("removedfile", function (file) {
                console.log(file.name);
                $("#url-images > input[name='"+ file.name +"']").val('');
            });
            this.on("maxfilesexceeded", function(file){
                alert("You can only choose 1 photo!");
                this.removeFile(file);
            });
        }
    };
});