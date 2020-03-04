var Securimage = function(options){
    this.config = options;
    var self = this;
    
    this.listen = function() {
        $('#' + self.config.refreshId).click(function() {
            self.refresh();
        });
    }
    
    this.refresh = function() {
        $('#' + self.config.captchaId).attr('src', this.config.captcha + '?sid=' + Math.random());
    }
    
    this.validate = function(code) {
        var status = false;
        $.ajax({
            url: self.config.ajax,
            type: 'POST',
            data: { code: code },
            dataType: 'json',
            async: false,
            success: function(data) {
                if(data.result === true) {
                    status = true;
                    return true;
                } else {
                    self.refresh();
                    return false;
                }
            }
        });
        
        return status;
    }
}