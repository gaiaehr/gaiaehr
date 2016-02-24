function post(path, params, method)
{
    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form"),
        hiddenField,
        addField,
        key,
        i;

    method = method || "post"; // Set method to post by default, if not specified.

    form.setAttribute("method", method);
    form.setAttribute("action", path);

    addField = function( key, value ){
        hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", value );
        form.appendChild(hiddenField);
    };

    for(key in params) {
        if(params.hasOwnProperty(key)) {
            if( params[key] instanceof Array ){
                for(i = 0; i < params[key].length; i++){
                    addField( key + '[]', params[key][i] )
                }
            }
            else{
                addField( key, params[key] );
            }
        }
    }

    document.body.appendChild(form);
    form.submit();
}
