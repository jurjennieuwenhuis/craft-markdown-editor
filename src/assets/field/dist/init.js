function initEasyMDE() {
    Array.prototype.forEach.call(document.getElementsByClassName('craft-easymde'), function(element) {
        let easyMDE = new EasyMDE({element: element});
        easyMDE.codemirror.on("change", function() {
            $(element).val(easyMDE.value());
        });
    });
}
//initEasyMDE();
