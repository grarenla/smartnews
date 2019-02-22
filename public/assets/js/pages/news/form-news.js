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
            this.on("success", function (file) {
                console.log(file.name);
                // validateImages(document.getElementById("frm-file-upload"), getValueImages());
            });
            this.on("removedfile", function (file) {
                console.log(file.name);
                // $("#url-images > input[name='"+ file.name +"']").remove();
                // if(r === 0) validateImages(document.getElementById("frm-file-upload"), getValueImages());
            });
            this.on("maxfilesexceeded", function(file){
                alert("No more files please!");
                this.removeFile(file);
            });

            // var _this = this;
            // document.getElementById("btn-reset").addEventListener("click", function() {
            //     r = 1;
            //     _this.removeAllFiles();
            // });
            //
            // var inputs = document.getElementById("url-images").querySelectorAll(".edit-img");
            // for(var i=0; i< inputs.length; i++){
            //     var mockFile = { name: inputs[i].name, size: 1, type: 'image/jpeg' };
            //     _this.emit("addedfile", mockFile);
            //     _this.emit("thumbnail", mockFile, inputs[i].value);
            //     _this.emit("complete", mockFile);
            //     _this.files.push( mockFile );
            // }
        }
    };
});