var main = (function () {
    doConstruct = function () {
        this.init_callbacks = [];
    };
    doConstruct.prototype = {
        add_init_callback : function (func) {
            if (typeof(func) == 'function') {
                this.init_callbacks.push(func);
                return true;
            }
            else {
                return false;
            }
        }
    };
    return new doConstruct;
})();
$(document).ready(function () {
    $.each(main.init_callbacks, function (index, func) {
        func();
    });
});
var admin_users = (function () {
    var doConstruct = function () {
        main.add_init_callback(this.show_user_data);

    };
    doConstruct.prototype = {
        show_user_data: function () {
            $('#users').DataTable( {
                "ajax": "<?php echo Config::get('app.url'); ?>/admin/users",

                "columns": [
                    { "data": "f_name" },
                    { "data": "email" },
                    //{ "data": "location" }
                ]

                //data:data
            } );
        },
    };
    return new doConstruct;
})();

